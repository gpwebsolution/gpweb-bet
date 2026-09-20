<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use App\Models\EfiPayment;
use App\Traits\Gateways\EfiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    use EfiTrait;

    public function handlePix(Request $request)
    {
        $payload = $request->all();
        Log::info('Webhook Pix recebido', ['payload' => $payload]);

        if (! self::validateWebhookOrigin($request)) {
            Log::warning('Webhook Pix: origem inválida', ['ip' => $request->ip()]);

            return response()->json(['mensagem' => 'Origem não autorizada'], 403);
        }

        $pixData = $payload['pix'] ?? [];

        if (empty($pixData)) {
            Log::warning('Webhook Pix: payload sem campo pix', ['payload' => $payload]);

            return response()->json(['mensagem' => 'Payload inválido'], 422);
        }

        foreach ($pixData as $pix) {
            $txid = $pix['txid'] ?? null;
            $e2eId = $pix['endToEndId'] ?? null;
            $valor = $pix['valor'] ?? null;
            $horario = $pix['horario'] ?? null;

            if (! $txid || ! $e2eId) {
                Log::warning('Webhook Pix: pix sem txid ou endToEndId', ['pix' => $pix]);

                continue;
            }

            $exists = DB::table('efi_payments')
                ->where('e2eid', $e2eId)
                ->exists();

            if ($exists) {
                Log::info('Webhook Pix: duplicata ignorada', ['e2eId' => $e2eId]);

                continue;
            }

            $payment = EfiPayment::where('txid', $txid)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->first();

            if (! $payment) {
                $existingPaid = EfiPayment::where('txid', $txid)
                    ->where('status', 'paid')
                    ->first();
                if ($existingPaid) {
                    Log::info('Webhook Pix: pagamento já processado', ['txid' => $txid, 'e2eId' => $e2eId]);
                } else {
                    Log::warning('Webhook Pix: cobrança não encontrada', ['txid' => $txid, 'e2eId' => $e2eId]);
                }

                continue;
            }

            $valorEsperado = (float) $payment->amount;
            $valorRecebido = (float) ($valor ?? 0);

            if (abs($valorRecebido - $valorEsperado) > 0.01) {
                Log::warning('Webhook Pix: valor divergente - rejeitado', [
                    'txid' => $txid,
                    'esperado' => $valorEsperado,
                    'recebido' => $valorRecebido,
                ]);

                continue;
            }

            $payment->update(['e2eid' => $e2eId]);

            if (self::finalizePayment($payment)) {
                Log::info('Webhook Pix: pagamento finalizado com sucesso', [
                    'txid' => $txid,
                    'e2eId' => $e2eId,
                    'valor' => $valor,
                    'user_id' => $payment->user_id,
                ]);
            } else {
                Log::error('Webhook Pix: erro ao finalizar pagamento', [
                    'txid' => $txid,
                    'e2eId' => $e2eId,
                    'payment_id' => $payment->payment_id,
                ]);
            }
        }

        return response()->json(['mensagem' => 'Recebido com sucesso'], 200);
    }
}
