<footer class="footer">
    <div class="footer-inner">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
            <div class="d-flex flex-column gap-2">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $setting?->logoUrl() }}" alt="MarioBET" style="height: 32px;">
                    <img src="{{ asset('/assets/images/mais18.svg') }}" alt="18+" height="50">
                </div>
                <p class="mb-0 small" style="color: rgba(255,255,255,0.45); max-width: 380px; line-height: 1.5;">
                    <strong>{{ config('setting')['software_name'] ?? 'MarioBET' }}</strong> — Sua plataforma de jogos online.
                    Jogue com responsabilidade. Proibido para menores de 18 anos.
                </p>
            </div>

            <div class="d-flex flex-column align-items-center align-items-md-end gap-2">
                <div class="d-flex gap-2">
                    @if(!empty(config('setting')['instagram']))
                        <a href="{{ config('setting')['instagram'] }}" target="_blank" class="social-icon" title="Instagram">
                            <img src="https://cdn.simpleicons.org/instagram/E4405F" alt="Instagram" width="28" height="28">
                        </a>
                    @endif
                    @if(!empty(config('setting')['discord']))
                        <a href="{{ config('setting')['discord'] }}" target="_blank" class="social-icon" title="Discord">
                            <img src="https://cdn.simpleicons.org/discord/5865F2" alt="Discord" width="28" height="28">
                        </a>
                    @endif
                    @if(!empty(config('setting')['telegram']))
                        <a href="{{ config('setting')['telegram'] }}" target="_blank" class="social-icon" title="Telegram">
                            <img src="https://cdn.simpleicons.org/telegram/26A5E4" alt="Telegram" width="28" height="28">
                        </a>
                    @endif
                    @if(!empty(config('setting')['twitter']))
                        <a href="{{ config('setting')['twitter'] }}" target="_blank" class="social-icon" title="Twitter / X">
                            <img src="https://cdn.simpleicons.org/x/ffffff" alt="Twitter" width="28" height="28">
                        </a>
                    @endif
                    @if(!empty(config('setting')['whatsapp']))
                        <a href="{{ config('setting')['whatsapp'] }}" target="_blank" class="social-icon" title="WhatsApp">
                            <img src="https://cdn.simpleicons.org/whatsapp/25D366" alt="WhatsApp" width="28" height="28">
                        </a>
                    @endif
                    @if(!empty(config('setting')['tiktok']))
                        <a href="{{ config('setting')['tiktok'] }}" target="_blank" class="social-icon" title="TikTok">
                            <img src="https://cdn.simpleicons.org/tiktok/000000" alt="TikTok" width="28" height="28">
                        </a>
                    @endif
                </div>
                <small style="color: rgba(255,255,255,0.3); font-size: 0.75rem; text-align: center;">
                    &copy;{{ date('Y') }} {{ config('setting')['software_name'] ?? config('app.name') }}. Todos os direitos reservados.
                    <br class="d-md-none">
                    Feito com <span style="color: var(--cor-principal);">&#9829;</span> por
                    <a href="{{ config('setting')['instagram'] ?? '#' }}" style="color: var(--cor-principal);">GPWebSolution</a>
                </small>
            </div>
        </div>
    </div>
</footer>