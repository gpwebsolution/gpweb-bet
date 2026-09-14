<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedido de Afiliado</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding:40px 20px;">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:8px;overflow:hidden;">
                    <tr>
                        <td style="padding:40px 30px;text-align:center;background:#1a1a2e;">
                            <h1 style="color:#fff;margin:0;font-size:24px;">{{ config('app.name') }}</h1>
                            <p style="color:#e63946;margin:8px 0 0;font-size:14px;">Programa de Afiliados</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px;">
                            <h2 style="margin:0 0 20px;font-size:20px;color:#333;">Novo Pedido de Afiliado</h2>
                            <p style="margin:0 0 10px;color:#555;font-size:14px;line-height:1.6;">
                                Um novo usuário solicitou participação no programa de afiliados:
                            </p>
                            <table width="100%" cellpadding="10" style="background:#f9f9f9;border-radius:4px;margin:20px 0;">
                                <tr>
                                    <td style="font-size:14px;color:#888;width:100px;">E-mail:</td>
                                    <td style="font-size:14px;color:#333;font-weight:bold;">{{ $email }}</td>
                                </tr>
                            </table>
                            <p style="margin:20px 0 0;color:#555;font-size:14px;">
                                Acesse o painel administrativo para gerenciar este afiliado.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 30px;background:#f4f4f4;text-align:center;">
                            <p style="margin:0;color:#999;font-size:12px;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>