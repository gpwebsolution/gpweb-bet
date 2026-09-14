<?php

namespace App\Traits\Gateways;

use App\Models\EfiPayment;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Traits\Affiliates\AffiliateHistoryTrait;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

trait EfiTrait
{
    use AffiliateHistoryTrait;

    protected static string $baseUrl;
    protected static string $gnUrl;
    protected static string $oauthUrl;
    protected static string $clientId;
    protected static string $clientSecret;
    protected static string $chavePix;
    protected static string $certPath;
    protected static string $keyPath;
    protected static string $certPassword;
    protected static string $accessToken;
    protected static bool $isSandbox;

    private static function generateCredentials(): bool
    {
        self::$clientId = config('efi.client_id');
        self::$clientSecret = config('efi.client_secret');
        self::$chavePix = config('efi.chave_pix');
        self::$baseUrl = config('efi.base_url');
        self::$gnUrl = config('efi.gn_url');
        self::$oauthUrl = config('efi.oauth_url');
        self::$certPath = config('efi.cert_path');
        self::$keyPath = config('efi.key_path');
        self::$certPassword = config('efi.cert_password');
        self::$isSandbox = config('efi.sandbox', false);

        if (empty(self::$clientId) || empty(self::$clientSecret)) {
            \Log::error('EFI: Credenciais vazias');
            return false;
        }

        $cacheKey = 'efi_access_token_' . md5(self::$clientId);

        if (Cache::has($cacheKey)) {
            self::$accessToken = Cache::get($cacheKey);
            return !empty(self::$accessToken);
        }

        try {
            $client = self::getCertClient(false);

            $response = $client->post(self::$oauthUrl, [
                'auth' => [self::$clientId, self::$clientSecret],
                'form_params' => ['grant_type' => 'client_credentials'],
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                self::$accessToken = $data['access_token'] ?? '';

                if (!empty(self::$accessToken)) {
                    $expiresIn = $data['expires_in'] ?? 3600;
                    Cache::put($cacheKey, self::$accessToken, now()->addSeconds($expiresIn - 60));
                    \Log::info('EFI OAuth: token obtido e cacheado por ' . $expiresIn . 's');
                }

                return !empty(self::$accessToken);
            }

            \Log::error('EFI OAuth falhou: status ' . $response->getStatusCode() . ' - ' . $response->getBody());
        } catch (\Exception $e) {
            \Log::error('EFI OAuth Error: ' . $e->getMessage());
        }

        return false;
    }

    private static function getCertClient(bool $withToken = true): Client
    {
        $headers = ['Content-Type' => 'application/json'];

        if ($withToken && !empty(self::$accessToken)) {
            $headers['Authorization'] = 'Bearer ' . self::$accessToken;
            $headers['x-api-version'] = '2';
        }

        $options = [
            'verify' => filter_var(env('EFI_SSL_VERIFY', true), FILTER_VALIDATE_BOOLEAN),
            'headers' => $headers,
        ];

        if (file_exists(self::$certPath) && file_exists(self::$keyPath)) {
            $options['cert'] = [self::$certPath, self::$certPassword];
            $options['ssl_key'] = [self::$keyPath, self::$certPassword];
        }

        return new Client($options);
    }

    public static function requestQrcode($request)
    {
        $setting = \Helper::getSetting();

        $rules = [
            'amount' => ['required', 'numeric', 'min:' . ($setting->min_deposit ?? 1)],
            'cpf'    => ['required', 'max:255'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ['status' => false, 'errors' => $validator->errors()];
        }

        $user = auth()->user();
        if (!$user) {
            return ['status' => false, 'error' => 'Usuário não autenticado'];
        }
        if (!self::generateCredentials()) {
            return ['status' => false, 'error' => 'Erro na autenticação com o gateway.'];
        }

        $amount = floatval(str_replace(',', '.', str_replace('.', '', $request->amount)));
        $amount = max($amount, 0.01);
        $cpf = preg_replace('/\D/', '', $request->cpf);

        if ($user->cpf_confirmed) {
            if ($user->cpf !== $cpf) {
                return ['status' => false, 'error' => 'CPF já confirmado na sua conta. Use o CPF cadastrado.'];
            }
        } else {
            if (!self::validateCpf($cpf)) {
                return ['status' => false, 'error' => 'CPF inválido. Verifique os dígitos.'];
            }

            $existing = \App\Models\User::where('cpf', $cpf)->where('id', '!=', $user->id)->exists();
            if ($existing) {
                return ['status' => false, 'error' => 'Este CPF já está cadastrado em outra conta.'];
            }

            $user->update(['cpf' => $cpf, 'cpf_confirmed' => 1]);
        }
        $paymentId = 'EFI' . time() . random_int(1000, 9999);
        $txid = strtoupper(substr(md5(uniqid('', true)), 0, 35));

        try {
            $client = self::getCertClient();

            $payload = [
                'calendario' => ['expiracao' => 3600],
                'devedor' => [
                    'cpf' => $cpf,
                    'nome' => mb_substr($user->name ?? 'Cliente', 0, 50),
                ],
                'valor' => ['original' => number_format($amount, 2, '.', '')],
                'chave' => self::$chavePix,
                'solicitacaoPagador' => 'Depósito ' . $user->name,
            ];

            $response = $client->put(self::$baseUrl . 'cob/' . $txid, [
                'json' => $payload,
            ]);

            if (in_array($response->getStatusCode(), [200, 201])) {
                $data = json_decode($response->getBody(), true);

                $pixCopyPaste = $data['pixCopiaECola'] ?? '';
                $qrcode = $pixCopyPaste;

                $locId = $data['loc']['id'] ?? null;
                if ($locId) {
                    try {
                        $qrResp = $client->get(self::$baseUrl . 'loc/' . $locId . '/qrcode');
                        if ($qrResp->getStatusCode() === 200) {
                            $qrData = json_decode($qrResp->getBody(), true);
                            $qrcode = $qrData['qrcode'] ?? $pixCopyPaste;
                        }
                    } catch (\Exception $e) {
                        $qrcode = $pixCopyPaste;
                    }
                }

                $expiresAt = Carbon::now()->addHour();
                $token = Str::random(64);

                EfiPayment::create([
                    'payment_id' => $paymentId,
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'cpf' => $cpf,
                    'pix_qrcode' => $qrcode,
                    'pix_copy_paste' => $pixCopyPaste,
                    'txid' => $txid,
                    'status' => 'pending',
                    'token' => $token,
                    'expires_at' => $expiresAt,
                ]);

                self::generateTransaction($paymentId, $amount);

                return [
                    'status' => true,
                    'idTransaction' => $paymentId,
                    'qrcode' => $qrcode,
                    'pixCopiaECola' => $pixCopyPaste,
                    'txid' => $txid,
                    'token' => $token,
                    'expires_at' => $expiresAt->toIso8601String(),
                ];
            }

            $body = $response->getBody()->getContents();
            \Log::error('EFI cobrança falhou: ' . $body);
            return ['status' => false, 'error' => 'Erro ao gerar QR Code no gateway.'];
        } catch (\Exception $e) {
            \Log::error('EFI requestQrcode: ' . $e->getMessage());
            return ['status' => false, 'error' => 'Erro ao comunicar com o gateway.'];
        }
    }

    public static function consultStatusTransaction($request)
    {
        $payment = EfiPayment::where('payment_id', $request->idTransaction)
            ->where('status', 'pending')
            ->first();

        if (!$payment) {
            return response()->json(['status' => 'PAID']);
        }

        if ($payment->expires_at && Carbon::now()->greaterThan($payment->expires_at)) {
            $payment->update(['status' => 'expired']);
            return response()->json(['status' => 'EXPIRED']);
        }

        if (!self::generateCredentials()) {
            return response()->json(['status' => 'ERROR']);
        }

        try {
            $client = self::getCertClient();
            $response = $client->get(self::$baseUrl . 'cob/' . $payment->txid);

            $payment->update(['last_efi_checked_at' => Carbon::now()]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                $efiStatus = $data['status'] ?? '';

                if (in_array($efiStatus, ['CONCLUIDA', 'REALIZADA'])) {
                    if (self::finalizePayment($payment)) {
                        return response()->json(['status' => 'PAID']);
                    }
                    return response()->json(['status' => 'ERROR']);
                }

                return response()->json(['status' => 'PENDING']);
            }

            return response()->json(['status' => 'PENDING']);
        } catch (\Exception $e) {
            \Log::error('EFI consultStatus: ' . $e->getMessage());
            return response()->json(['status' => 'PENDING']);
        }
    }

    public static function streamPaymentStatus(EfiPayment $payment): void
    {
        if (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');

        $maxDuration = 3600;
        $startTime = time();

        while (time() - $startTime < $maxDuration) {
            $fresh = EfiPayment::find($payment->id);

            if (!$fresh || $fresh->status === 'paid') {
                echo "event: paid\n";
                echo "data: " . json_encode(['status' => 'PAID']) . "\n\n";
                flush();
                return;
            }

            if ($fresh->status === 'expired') {
                echo "event: expired\n";
                echo "data: " . json_encode(['status' => 'EXPIRED']) . "\n\n";
                flush();
                return;
            }

            echo "event: ping\n";
            echo "data: " . json_encode(['status' => 'PENDING']) . "\n\n";
            flush();

            $canCheck = !$fresh->last_efi_checked_at ||
                Carbon::now()->diffInSeconds($fresh->last_efi_checked_at) >= 15;

            if ($canCheck) {
                self::consultEfiStatus($fresh);
            }

            sleep(3);
        }

        echo "event: timeout\n";
        echo "data: " . json_encode(['status' => 'TIMEOUT']) . "\n\n";
        flush();
    }

    private static function consultEfiStatus(EfiPayment $payment): void
    {
        try {
            if (!self::generateCredentials()) {
                return;
            }

            $client = self::getCertClient();
            $response = $client->get(self::$baseUrl . 'cob/' . $payment->txid);

            $payment->update(['last_efi_checked_at' => Carbon::now()]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                if (($data['status'] ?? '') === 'CONCLUIDA') {
                    self::finalizePayment($payment);
                }
            }
        } catch (\Exception $e) {
            \Log::error('EFI consultEfiStatus: ' . $e->getMessage());
        }
    }

    public static function finalizePayment(EfiPayment $payment): bool
    {
        $transaction = Transaction::where('payment_id', $payment->payment_id)
            ->where('status', 0)
            ->first();

        $setting = \Helper::getSetting();

        if (!empty($transaction)) {
            $wallet = Wallet::where('user_id', $transaction->user_id)->first();
            if (!empty($wallet)) {
                $count = Transaction::where('user_id', $transaction->user_id)->count();
                if ($count <= 1 && ($setting->initial_bonus ?? 0) > 0) {
                    $bonus = \Helper::porcentagem_xn($setting->initial_bonus, $transaction->price);
                    $wallet->increment('balance_bonus', $bonus);
                }

                if ($wallet->increment('balance', $transaction->price)) {
                    if ($transaction->update(['status' => 1])) {
                        $payment->update(['status' => 'paid', 'paid_at' => Carbon::now()]);
                        self::updateAffiliate($transaction->payment_id, $transaction->user_id, $transaction->price);
                        return true;
                    }
                }
            }
            return false;
        }

        $active = Transaction::where('payment_id', $payment->payment_id)->where('status', 1)->first();
        if ($active) {
            $payment->update(['status' => 'paid', 'paid_at' => Carbon::now()]);
            return true;
        }

        return false;
    }

    private static function generateTransaction($idTransaction, $amount): void
    {
        Transaction::create([
            'payment_id' => $idTransaction,
            'user_id' => auth()->user()->id,
            'payment_method' => 'pix',
            'price' => $amount,
            'currency' => \Helper::getSetting()->currency_code ?? 'BRL',
            'status' => 0,
        ]);
    }

    public static function pixCashOut(array $array): array
    {
        $result = ['status' => false, 'error' => ''];

        if (!self::generateCredentials()) {
            $result['error'] = 'Erro na autenticação com o gateway.';
            return $result;
        }

        try {
            $client = self::getCertClient();
            $amount = floatval(str_replace(',', '.', str_replace('.', '', $array['amount'])));
            $idempotencyKey = strtoupper(substr(md5(uniqid('saque_', true)), 0, 35));

            $payload = [
                'valor' => number_format($amount, 2, '.', ''),
                'pagador' => [
                    'chave' => self::$chavePix,
                ],
                'favorecido' => [
                    'chave' => $array['pix_key'],
                ],
            ];

            $response = $client->post(self::$gnUrl . 'pix', [
                'json' => $payload,
                'headers' => [
                    'x-idempotency-key' => $idempotencyKey,
                ],
            ]);

            if (in_array($response->getStatusCode(), [200, 201])) {
                $data = json_decode($response->getBody(), true);
                $status = $data['status'] ?? '';
                $endToEndId = $data['endToEndId'] ?? '';

                if (in_array($status, ['REALIZADO', 'CONCLUIDA'])) {
                    if (isset($array['payment_id'])) {
                        $efiPayment = EfiPayment::lockForUpdate()->find($array['payment_id']);
                        if ($efiPayment) {
                            $efiPayment->update(['status' => 'paid', 'paid_at' => Carbon::now()]);
                        }
                    }

                    $result['status'] = true;
                    $result['endToEndId'] = $endToEndId;
                    $result['txid'] = $data['txid'] ?? $idempotencyKey;
                    return $result;
                }

                $result['error'] = 'Status inesperado: ' . $status;
                \Log::error('EFI pixCashOut status inesperado: ' . json_encode($data));
                return $result;
            }

            $body = $response->getBody()->getContents();
            \Log::error('EFI pixCashOut falhou: status ' . $response->getStatusCode() . ' - ' . $body);
            $result['error'] = 'Erro ao processar Pix no gateway.';
            return $result;
        } catch (\Exception $e) {
            \Log::error('EFI pixCashOut: ' . $e->getMessage());
            $result['error'] = 'Erro ao comunicar com o gateway: ' . $e->getMessage();
            return $result;
        }
    }

    public static function pixDevolution(string $e2eId, string $txid, float $amount): array
    {
        $result = ['status' => false, 'error' => ''];

        if (!self::generateCredentials()) {
            $result['error'] = 'Erro na autenticação com o gateway.';
            return $result;
        }

        try {
            $client = self::getCertClient();

            $response = $client->put(self::$baseUrl . 'pix/' . $e2eId . '/devolucao/' . $txid, [
                'json' => [
                    'valor' => number_format($amount, 2, '.', ''),
                ],
            ]);

            if (in_array($response->getStatusCode(), [200, 201])) {
                $data = json_decode($response->getBody(), true);
                $result['status'] = true;
                $result['data'] = $data;
                return $result;
            }

            $body = $response->getBody()->getContents();
            \Log::error('EFI pixDevolution falhou: ' . $body);
            $result['error'] = 'Erro ao realizar devolução Pix.';
            return $result;
        } catch (\Exception $e) {
            \Log::error('EFI pixDevolution: ' . $e->getMessage());
            $result['error'] = 'Erro ao comunicar com o gateway.';
            return $result;
        }
    }

    public static function listPixTransfers(array $filters = []): array
    {
        $result = ['status' => false, 'data' => []];

        if (!self::generateCredentials()) {
            return $result;
        }

        try {
            $client = self::getCertClient();
            $query = http_build_query($filters);

            $response = $client->get(self::$gnUrl . 'pix' . ($query ? '?' . $query : ''));

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                $result['status'] = true;
                $result['data'] = $data;
                return $result;
            }

            return $result;
        } catch (\Exception $e) {
            \Log::error('EFI listPixTransfers: ' . $e->getMessage());
            return $result;
        }
    }

    public static function cancelPixCharge(string $txid): array
    {
        $result = ['status' => false, 'error' => ''];

        if (!self::generateCredentials()) {
            $result['error'] = 'Erro na autenticação com o gateway.';
            return $result;
        }

        try {
            $client = self::getCertClient();

            $response = $client->patch(self::$baseUrl . 'cob/' . $txid, [
                'json' => [
                    'status' => 'REMOVIDA_PELO_USUARIO_RECEBEDOR',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                $result['status'] = true;
                $result['data'] = $data;

                EfiPayment::where('txid', $txid)->update(['status' => 'cancelled']);

                \Log::info('EFI cobrança cancelada', ['txid' => $txid]);
                return $result;
            }

            $body = $response->getBody()->getContents();
            \Log::error('EFI cancelar cobrança falhou: ' . $body);
            $result['error'] = 'Erro ao cancelar cobrança no gateway.';
            return $result;
        } catch (\Exception $e) {
            \Log::error('EFI cancelPixCharge: ' . $e->getMessage());
            $result['error'] = 'Erro ao comunicar com o gateway.';
            return $result;
        }
    }

    public static function validateWebhookOrigin(Request $request): bool
    {
        $efiIps = [
            '34.95.255.233',
            '34.95.122.52',
            '34.95.196.114',
            '35.247.225.182',
            '35.247.248.121',
            '35.247.235.54',
        ];

        $clientIp = $request->ip();

        if (self::$isSandbox) {
            return true;
        }

        if (in_array($clientIp, $efiIps)) {
            return true;
        }

        \Log::warning('EFI Webhook: IP não autorizado tentou acessar: ' . $clientIp);
        return false;
    }

    private static function validateCpf(string $cpf): bool
    {
        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) !== 11) {
            return false;
        }

        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ((int) $cpf[$c] !== $d) {
                return false;
            }
        }

        return true;
    }
}
