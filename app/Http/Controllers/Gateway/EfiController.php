<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use App\Models\EfiPayment;
use App\Models\Wallet;
use App\Models\Saque;
use App\Traits\Gateways\EfiTrait;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class EfiController extends Controller
{
    use EfiTrait;

    /**
     * Status da integração Efí
     */
    public function status()
    {
        try {
            $creds = self::generateCredentials();

            return response()->json([
                'status' => $creds ? 'online' : 'offline',
                'ambiente' => config('efi.sandbox') ? 'sandbox' : 'producao',
                'token_valido' => !empty(self::$accessToken),
                'chave_pix' => substr(self::$chavePix ?? config('efi.chave_pix'), 0, 8) . '...',
                'certificado' => file_exists(config('efi.cert_path')) ? 'instalado' : 'ausente',
                'timestamp' => Carbon::now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function confirmPayment(Request $request)
    {
        $paymentId = $request->idTransaction;

        $payment = EfiPayment::where('payment_id', $paymentId)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if (!$payment) {
            return response()->json(['status' => 'NOT_FOUND']);
        }

        if ($payment->expires_at && Carbon::now()->greaterThan($payment->expires_at)) {
            $payment->update(['status' => 'expired']);
            return response()->json(['status' => 'EXPIRED']);
        }

        if (self::finalizePayment($payment)) {
            return response()->json(['status' => 'PAID']);
        }

        return response()->json(['status' => 'ERROR']);
    }

    public function paymentStream(Request $request, $paymentId)
    {
        $payment = EfiPayment::where('payment_id', $paymentId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        if ($payment->status === 'paid') {
            return response()->json(['status' => 'PAID']);
        }

        self::streamPaymentStatus($payment);
    }

    public function callbackMethod(Request $request)
    {
        $data = $request->all();
        \Log::info('EFI Callback recebido', $data);

        $txid = $data['txid'] ?? ($data['pix'][0]['txid'] ?? null);

        if ($txid) {
            $payment = EfiPayment::where('txid', $txid)->where('status', 'pending')->first();
            if ($payment) {
                \Log::info('EFI Callback: finalizando pagamento ' . $payment->payment_id);
                self::finalizePayment($payment);
            } else {
                \Log::warning('EFI Callback: pagamento não encontrado para txid ' . $txid);
            }
        }

        return response()->json([], 200);
    }

    public function getQRCodePix(Request $request)
    {
        return self::requestQrcode($request);
    }

    public function consultStatusTransactionPix(Request $request)
    {
        return self::consultStatusTransaction($request);
    }

    public function cancelSaqueFromModal($id)
    {
        if (!auth()->user()->hasRole(['admin'])) {
            Notification::make()->title('Sem permissão')->danger()->send();
            return back();
        }

        $saque = Saque::find($id);
        if (!empty($saque)) {
            $wallet = Wallet::where('user_id', $saque->user_id)->first();

            if (!empty($wallet)) {
                $wallet->increment('balance', $saque->amount);

                $saque->update(['status' => 2]);
                Notification::make()
                    ->title('Saque cancelado')
                    ->body('Saque cancelado com sucesso')
                    ->success()
                    ->send();

                return back();
            }
            return back();
        }
        return back();
    }

    public function saqueFromModal($id)
    {
        if (!auth()->user()->hasRole(['admin'])) {
            Notification::make()->title('Sem permissão')->danger()->send();
            return back();
        }

        $saque = Saque::find($id);
        if (!empty($saque)) {
            $efiPayment = EfiPayment::create([
                'payment_id' => 'WTH' . time() . random_int(1000, 9999),
                'user_id' => $saque->user_id,
                'amount' => $saque->amount,
                'cpf' => $saque->document ?? '',
                'status' => 'pending',
            ]);

            if ($efiPayment) {
                $parm = [
                    'pix_key' => $saque->chave_pix,
                    'pix_type' => $saque->tipo_chave,
                    'amount' => $saque->amount,
                    'payment_id' => $efiPayment->id,
                    'document' => $saque->document ?? '',
                ];

                $resp = self::pixCashOut($parm);

                if ($resp['status'] ?? false) {
                    $saque->update([
                        'status' => 1,
                        'proof' => $resp['endToEndId'] ?? null,
                    ]);
                    Notification::make()
                        ->title('Saque processado')
                        ->body('Saque processado com sucesso')
                        ->success()
                        ->send();

                    return back();
                } else {
                    Notification::make()
                        ->title('Erro no saque')
                        ->body($resp['error'] ?? 'Erro ao solicitar o saque')
                        ->danger()
                        ->send();

                    return back();
                }
            }
        }
    }
}
