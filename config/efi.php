<?php

$isSandbox = env('EFI_SANDBOX', false);

if ($isSandbox) {
    $baseUrl = 'https://pix-h.api.efipay.com.br/';
} else {
    $baseUrl = 'https://pix.api.efipay.com.br/';
}

return [
    'client_id' => env('EFI_CLIENT_ID'),
    'client_secret' => env('EFI_CLIENT_SECRET'),
    'chave_pix' => env('EFI_CHAVE_PIX'),
    'cert_password' => env('EFI_CERT_PASSWORD', ''),

    'base_url' => $baseUrl . 'v2/',
    'gn_url' => $baseUrl . 'v2/gn/',
    'oauth_url' => $baseUrl . 'oauth/token',

    'cert_path' => env('EFI_CERT_PATH') ?: storage_path('app/cert/certificado.pem'),
    'key_path' => env('EFI_KEY_PATH') ?: storage_path('app/cert/chave.pem'),

    'sandbox' => $isSandbox,

    'gateway_selected' => env('GATEWAY_SELECTED', 'efi'),

    'webhook_url' => env('EFI_WEBHOOK_URL', 'https://subsonic-frantic-manager.ngrok-free.dev/webhook/pix'),
];