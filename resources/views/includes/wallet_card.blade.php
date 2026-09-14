<div class="wallet-card mb-4">
    <div class="row align-items-center">
        <div class="col-lg-12">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div>
                    <div class="wallet-label"><i class="fa-regular fa-wallet me-1"></i> Saldo Disponível</div>
                    <div class="wallet-balance">
                        @if(auth()->user()->wallet->hide_balance == 1)
                            ****
                        @else
                            {{ \Helper::amountFormatDecimal(auth()->user()->wallet->balance) }}
                        @endif
                    </div>
                </div>
                <a href="{{ route('panel.wallet.hidebalance') }}" class="text-muted" style="font-size: 0.85rem;">
                    <i class="fa-regular fa-eye{{ auth()->user()->wallet->hide_balance == 1 ? '-slash' : '' }}"></i>
                </a>
            </div>
            <div class="d-flex gap-3 flex-wrap">
                <div>
                    <div class="wallet-label">Bônus</div>
                    <strong style="color: var(--cor-principal);">
                        @if(auth()->user()->wallet->hide_balance == 1) ****
                        @else {{ \Helper::amountFormatDecimal(auth()->user()->wallet->balance_bonus) }}
                        @endif
                    </strong>
                </div>
                <div>
                    <div class="wallet-label">Prêmios</div>
                    <strong style="color: #fbbf24;">
                        @if(auth()->user()->wallet->hide_balance == 1) ****
                        @else {{ \Helper::amountFormatDecimal(auth()->user()->wallet->refer_rewards) }}
                        @endif
                    </strong>
                </div>
                <div>
                    <div class="wallet-label">Total Apostado</div>
                    <strong style="color: rgba(255,255,255,0.6);">{{ \Helper::amountFormatDecimal(auth()->user()->wallet->total_bet ?? 0) }}</strong>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('panel.wallet.deposit_form') }}" class="btn btn-primary-theme" style="padding: 8px 20px; font-size: 0.85rem;">
                    <i class="fa-regular fa-plus me-1"></i> Depositar
                </a>
                <a href="{{ route('panel.wallet.saque_form') }}" class="btn btn-primary-theme" style="padding: 8px 20px; font-size: 0.85rem; background: rgba(255,255,255,0.08);">
                    <i class="fa-regular fa-arrow-up-from-bracket me-1"></i> Sacar
                </a>
            </div>
        </div>
    </div>
</div>
