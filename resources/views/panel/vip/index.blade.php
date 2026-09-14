@extends('layouts.web')

@push('styles')
<style>
    .vip-card {
        background: #1a1c1f;
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .vip-card:hover {
        border-color: rgba(230, 57, 70, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    }
    .vip-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        font-weight: 800;
        color: #fff;
        flex-shrink: 0;
    }
    .vip-bar {
        height: 6px;
        background: rgba(255,255,255,0.08);
        border-radius: 3px;
        overflow: hidden;
    }
    .vip-bar-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.8s ease;
    }
    .vip-tbl {
        border-radius: 12px;
        overflow: hidden;
        background: #1a1c1f;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .vip-tbl thead th {
        background: #0d0d0d;
        color: #e63946;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.65rem;
        letter-spacing: 0.04em;
        padding: 10px 10px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        white-space: nowrap;
    }
    .vip-tbl tbody td {
        padding: 9px 10px;
        border-bottom: 1px solid rgba(255,255,255,0.03);
        color: #e0e0e0;
        font-size: 0.8rem;
        vertical-align: middle;
    }
    .vip-tbl tbody tr:hover { background: rgba(230, 57, 70, 0.05); }
    .vip-tbl tbody tr:last-child td { border-bottom: none; }
    .vip-tbl tbody tr.current-row { background: rgba(230, 57, 70, 0.08); }
    .vip-dot {
        width: 10px; height: 10px;
        border-radius: 50%; display: inline-block; flex-shrink: 0;
    }
    .badge-vip-cur { background: rgba(230,57,70,0.15); color: #e63946; }
    .badge-vip-nxt { background: rgba(245,158,11,0.15); color: #f59e0b; }
    .badge-vip-lck { background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.3); }
    .badge-vip-done { background: rgba(16,185,129,0.15); color: #10b981; }

    .bonus-block {
        background: #1a1c1f;
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 16px;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s ease;
        flex: 1;
        min-width: 0;
    }
    .bonus-block:hover {
        border-color: rgba(230, 57, 70, 0.2);
        transform: translateY(-1px);
    }
    .bonus-block .icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-size: 1.1rem;
    }
    .bonus-block .value {
        font-size: 1.25rem;
        font-weight: 800;
        line-height: 1.2;
    }
    .bonus-block .label {
        font-size: 0.65rem;
        color: rgba(255,255,255,0.4);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 8px;
    }
    .bonus-block .sub {
        font-size: 0.6rem;
        color: rgba(255,255,255,0.3);
        margin-top: 4px;
    }
    .bonus-btn {
        display: inline-block;
        padding: 5px 16px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        color: #fff;
        width: 100%;
        max-width: 140px;
    }
    .bonus-btn:disabled {
        cursor: not-allowed;
        opacity: 0.4;
    }
    .bonus-btn.available {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    .bonus-btn.available:hover {
        filter: brightness(1.1);
    }
    .bonus-btn.claimed {
        background: rgba(255,255,255,0.06);
        color: rgba(255,255,255,0.4);
    }
    .bonus-btn.locked {
        background: rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.25);
    }

    .upgrade-list {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-top: 8px;
    }
    .upgrade-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        background: rgba(16,185,129,0.06);
        border: 1px solid rgba(16,185,129,0.12);
        border-radius: 10px;
        padding: 8px 10px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .upgrade-item:hover {
        background: rgba(16,185,129,0.1);
    }
    .upgrade-item .upgrade-name {
        color: rgba(255,255,255,0.7);
        font-weight: 600;
    }
    .upgrade-item .upgrade-value {
        color: #10b981;
        font-weight: 800;
    }
    .upgrade-item .upgrade-arrow {
        color: rgba(255,255,255,0.3);
        font-size: 0.6rem;
    }

    @media (max-width: 576px) {
        .vip-tbl thead th, .vip-tbl tbody td { padding: 7px 6px; font-size: 0.7rem; }
        .vip-circle { width: 38px; height: 38px; font-size: 0.95rem; }
        .bonus-block { padding: 0.75rem; }
        .bonus-block .value { font-size: 1.1rem; }
        .bonus-block .icon-wrap { width: 36px; height: 36px; font-size: 0.9rem; }
    }
</style>
@endpush

@section('content')
    @include('includes.navbar_top')
    @include('includes.navbar_left')

    <div class="page__content">
        <div class="container">

            {{-- Header --}}
            <div class="d-flex align-items-center gap-2 mb-3">
                <div style="width: 42px; height: 42px; background: linear-gradient(135deg,#e63946,#c1121f); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-crown" style="color: #fff; font-size: 1.1rem;"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #fff;">VIP</h4>
                    <p class="mb-0" style="color: rgba(255,255,255,0.45); font-size: 0.8rem;">Deposite e aposte para subir</p>
                </div>
            </div>

            {{-- Current level + progress --}}
            @php $vipColor = $currentVip?->color ?? '#555'; @endphp
            <div class="p-3 mb-3" style="border-radius:16px;background:linear-gradient(135deg, {{ $vipColor }}ee 0%, {{ $vipColor }}88 100%);border:1px solid {{ $vipColor }}55;">
                <div class="row align-items-center g-2">
                    <div class="col-auto">
                        <div class="vip-circle" style="background: {{ $currentVip?->color ?? '#555' }};">
                            {!! $currentVip ? $currentVip->level : '<i class="fa-regular fa-star" style="font-size:1.2rem;"></i>' !!}
                        </div>
                    </div>
                    <div class="col">
                        <p class="mb-0" style="color: rgba(255,255,255,0.6); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.04em;">{{ $currentVip ? 'Nível ' . $currentVip->level : 'Sem nível' }}</p>
                        <h5 class="fw-bold mb-0" style="color: #fff;">{{ $currentVip?->name ?? 'Iniciante' }}</h5>
                    </div>
                    <div class="col-lg-5 mt-2 mt-lg-0">
                        @if($nextVip)
                            <div style="font-size: 0.7rem; color: rgba(255,255,255,0.6);">
                                <span><i class="fa-regular fa-arrow-right"></i> {{ $nextVip->name }}</span>
                            </div>
                            <div class="mt-2">
                                <div class="d-flex justify-content-between" style="font-size: 0.65rem; color: rgba(255,255,255,0.5);">
                                    <span><i class="fa-regular fa-arrow-up-to-bracket"></i> Depósito</span>
                                    <span>R$ {{ number_format($userDeposit, 2, ',', '.') }} / R$ {{ number_format($nextVip->min_deposit, 2, ',', '.') }}</span>
                                </div>
                                <div class="vip-bar mt-1">
                                    <div class="vip-bar-fill" style="width: {{ min(100, $progressDeposit) }}%; background: linear-gradient(90deg,#fbbf24,#f59e0b);"></div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="d-flex justify-content-between" style="font-size: 0.65rem; color: rgba(255,255,255,0.5);">
                                    <span><i class="fa-regular fa-dice"></i> Apostas</span>
                                    <span>R$ {{ number_format($userBets, 2, ',', '.') }} / R$ {{ number_format($nextVip->min_bets, 2, ',', '.') }}</span>
                                </div>
                                <div class="vip-bar mt-1">
                                    <div class="vip-bar-fill" style="width: {{ min(100, $progressBets) }}%; background: linear-gradient(90deg,#3b82f6,#2563eb);"></div>
                                </div>
                            </div>
                        @elseif($currentVip)
                            <p class="mb-0 mt-1" style="color: #fbbf24; font-size: 0.85rem;"><i class="fa-regular fa-trophy"></i> Nível máximo!</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 3 Bonus Blocks --}}
            @php
                $isMonday = (int) date('N') === 1;
                $isFirstDay = (int) date('j') === 1;
            @endphp
            <div class="d-flex gap-2 mb-3 flex-wrap">
                {{-- Weekly --}}
                <div class="bonus-block">
                    <div class="icon-wrap" style="background: rgba(16,185,129,0.1); color: #10b981;">
                        <i class="fa-regular fa-calendar-week"></i>
                    </div>
                    <div class="label">Semanal</div>
                    <div class="value" style="color: #10b981;">
                        @if($currentVip)
                            R$ {{ number_format($currentVip->weekly_bonus, 2, ',', '.') }}
                        @else
                            —
                        @endif
                    </div>
                    <div class="sub">
                        @if(!$currentVip)
                            Sem nível VIP
                        @elseif($weeklyClaimed)
                            Já resgatado
                        @elseif(!$isMonday)
                            Disponível segundas
                        @else
                            Disponível agora
                        @endif
                    </div>
                    @if($currentVip)
                        <button class="bonus-btn claim-btn mt-2" data-type="weekly"
                            {{ $weeklyClaimed || !$isMonday ? 'disabled' : '' }}>
                            {{ $weeklyClaimed ? 'Recebido' : ($isMonday ? 'Resgatar' : 'Aguardar') }}
                        </button>
                    @endif
                </div>

                {{-- Monthly --}}
                <div class="bonus-block">
                    <div class="icon-wrap" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                        <i class="fa-regular fa-calendar"></i>
                    </div>
                    <div class="label">Mensal</div>
                    <div class="value" style="color: #3b82f6;">
                        @if($currentVip)
                            R$ {{ number_format($currentVip->monthly_bonus, 2, ',', '.') }}
                        @else
                            —
                        @endif
                    </div>
                    <div class="sub">
                        @if(!$currentVip)
                            Sem nível VIP
                        @elseif($monthlyClaimed)
                            Já resgatado
                        @elseif(!$isFirstDay)
                            Disponível dia 1
                        @else
                            Disponível agora
                        @endif
                    </div>
                    @if($currentVip)
                        <button class="bonus-btn claim-btn mt-2" data-type="monthly"
                            {{ $monthlyClaimed || !$isFirstDay ? 'disabled' : '' }}>
                            {{ $monthlyClaimed ? 'Recebido' : ($isFirstDay ? 'Resgatar' : 'Aguardar') }}
                        </button>
                    @endif
                </div>

                {{-- Upgrade --}}
                <div class="bonus-block">
                    <div class="icon-wrap" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                        <i class="fa-regular fa-arrow-up"></i>
                    </div>
                    <div class="label">Upgrade</div>
                    @if($pendingRewards->count() > 0)
                        <div class="value" style="color: #f59e0b;">
                            +{{ $pendingRewards->count() }}
                        </div>
                        <div class="sub">Recompensa{{ $pendingRewards->count() > 1 ? 's' : '' }} pendente{{ $pendingRewards->count() > 1 ? 's' : '' }}</div>
                        <div class="upgrade-list">
                            @foreach($pendingRewards as $reward)
                                <div class="upgrade-item level-reward-btn" data-id="{{ $reward->id }}">
                                    <span class="upgrade-name">{{ $reward->vip?->name ?? 'Nível' }}</span>
                                    <span class="upgrade-value">R$ {{ number_format($reward->amount, 2, ',', '.') }}</span>
                                    <span class="upgrade-arrow"><i class="fa-regular fa-arrow-right"></i></span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="value" style="color: rgba(255,255,255,0.2);">—</div>
                        <div class="sub">Nenhuma pendente</div>
                    @endif
                </div>
            </div>

            {{-- Mini stats --}}
            <div class="row g-2 mb-3">
                <div class="col-3 col-lg-3">
                    <div class="vip-card p-2 text-center">
                        <p class="mb-0" style="color: rgba(255,255,255,0.4); font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.04em;"><i class="fa-regular fa-layer-group"></i> Nv</p>
                        <div style="font-size: 1.2rem; font-weight: 800; color: {{ $currentVip?->color ?? '#888' }};">{{ $currentVip?->level ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-3 col-lg-3">
                    <div class="vip-card p-2 text-center">
                        <p class="mb-0" style="color: rgba(255,255,255,0.4); font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.04em;"><i class="fa-regular fa-calendar-week"></i> Sem</p>
                        <div style="font-size: 1.2rem; font-weight: 800; color: #10b981;">R$ {{ number_format($currentVip?->weekly_bonus ?? 0, 2, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-3 col-lg-3">
                    <div class="vip-card p-2 text-center">
                        <p class="mb-0" style="color: rgba(255,255,255,0.4); font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.04em;"><i class="fa-regular fa-calendar"></i> Mês</p>
                        <div style="font-size: 1.2rem; font-weight: 800; color: #3b82f6;">R$ {{ number_format($currentVip?->monthly_bonus ?? 0, 2, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-3 col-lg-3">
                    <div class="vip-card p-2 text-center">
                        <p class="mb-0" style="color: rgba(255,255,255,0.4); font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.04em;"><i class="fa-regular fa-list"></i> Total</p>
                        <div style="font-size: 1.2rem; font-weight: 800; color: #e63946;">{{ $levels->count() }}</div>
                    </div>
                </div>
            </div>

            {{-- VIP levels table --}}
            <div class="vip-tbl mb-3">
                <div class="px-3 py-2" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <h6 class="mb-0" style="color: #fff; font-size: 0.9rem;"><i class="fa-regular fa-list me-1" style="color: #e63946;"></i> Níveis</h6>
                </div>
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table vip-tbl mb-0" style="min-width: 520px;">
                        <thead>
                            <tr>
                                <th><i class="fa-regular fa-tag"></i></th>
                                <th><i class="fa-regular fa-arrow-up-to-bracket"></i> Dep</th>
                                <th><i class="fa-regular fa-dice"></i> Bet</th>
                                <th><i class="fa-regular fa-calendar-week"></i> Sem</th>
                                <th><i class="fa-regular fa-calendar"></i> Mês</th>
                                <th><i class="fa-regular fa-arrow-up"></i> Up</th>
                                <th><i class="fa-regular fa-flag"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($levels as $l)
                                @php
                                    $isCur = $currentVip && $currentVip->id === $l->id;
                                @endphp
                                <tr class="{{ $isCur ? 'current-row' : '' }}">
                                    <td><span class="vip-dot" style="background:{{ $l->color }};"></span> <span style="color:{{ $l->color }}; font-weight:600;">{{ $l->name }}</span></td>
                                    <td>R$ {{ number_format($l->min_deposit, 0, ',', '.') }}</td>
                                    <td>{!! $l->min_bets > 0 ? 'R$ ' . number_format($l->min_bets, 0, ',', '.') : '<i class="fa-regular fa-minus"></i>' !!}</td>
                                    <td style="color:#10b981;">R$ {{ number_format($l->weekly_bonus, 2, ',', '.') }}</td>
                                    <td style="color:#3b82f6;">R$ {{ number_format($l->monthly_bonus, 2, ',', '.') }}</td>
                                    <td style="color:#f59e0b;">R$ {{ number_format($l->level_up_bonus, 2, ',', '.') }}</td>
                                    <td>
                                        @if($isCur)
                                            <span class="badge-vip-cur" style="padding:2px 8px;border-radius:10px;font-size:0.65rem;font-weight:600;white-space:nowrap;">
                                                <i class="fa-regular fa-check"></i>
                                            </span>
                                        @elseif($l->level < ($currentVip?->level ?? 0))
                                            <span class="badge-vip-done" style="padding:2px 8px;border-radius:10px;font-size:0.65rem;font-weight:600;white-space:nowrap;">
                                                <i class="fa-regular fa-check"></i>
                                            </span>
                                        @elseif($l->level === ($currentVip?->level ?? 0) + 1 || !$currentVip)
                                            <span class="badge-vip-nxt" style="padding:2px 8px;border-radius:10px;font-size:0.65rem;font-weight:600;white-space:nowrap;">
                                                <i class="fa-regular fa-arrow-right"></i>
                                            </span>
                                        @else
                                            <span class="badge-vip-lck" style="padding:2px 8px;border-radius:10px;font-size:0.65rem;font-weight:600;white-space:nowrap;">
                                                <i class="fa-regular fa-lock"></i>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.claim-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.disabled) return;

            const type = this.dataset.type;
            const original = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:12px;height:12px;"></span>';
            this.disabled = true;

            const csrf = document.head.querySelector('[name~=csrf-token][content]').content;
            const url = type === 'weekly'
                ? '{{ route("panel.vip.claim.weekly") }}'
                : '{{ route("panel.vip.claim.monthly") }}';

            fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({})
            })
            .then(r => r.json())
            .then(data => {
                if (data.status) {
                    iziToast.success({
                        title: 'Sucesso',
                        message: data.message,
                        backgroundColor: '#23ab0e',
                        position: 'topRight',
                        timeout: 3000,
                    });
                    this.textContent = 'Recebido';
                    this.className = 'bonus-btn claimed mt-2';
                    this.disabled = true;

                    const balanceEl = document.querySelector('.balance-value span');
                    if (balanceEl && data.balance) {
                        balanceEl.textContent = data.balance;
                    }
                } else {
                    iziToast.error({
                        title: 'Atenção',
                        message: data.error || 'Erro ao processar bônus.',
                        backgroundColor: '#b51408',
                        position: 'topRight'
                    });
                    this.innerHTML = original;
                    this.disabled = false;
                }
            })
            .catch(() => {
                iziToast.error({
                    title: 'Erro',
                    message: 'Falha na requisição. Tente novamente.',
                    backgroundColor: '#b51408',
                    position: 'topRight'
                });
                this.innerHTML = original;
                this.disabled = false;
            });
        });
    });

    document.querySelectorAll('.level-reward-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const original = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:12px;height:12px;"></span>';
            this.style.cursor = 'not-allowed';
            this.style.opacity = '0.6';

            const csrf = document.head.querySelector('[name~=csrf-token][content]').content;

            fetch('{{ route("panel.vip.claim.level_reward") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ bonus_id: id })
            })
            .then(r => r.json())
            .then(data => {
                if (data.status) {
                    iziToast.success({
                        title: 'Sucesso',
                        message: data.message,
                        backgroundColor: '#23ab0e',
                        position: 'topRight',
                        timeout: 3000,
                    });
                    this.style.background = 'rgba(255,255,255,0.04)';
                    this.style.border = '1px solid rgba(255,255,255,0.06)';
                    this.style.cursor = 'default';
                    this.innerHTML = '<span style="color:rgba(255,255,255,0.3)"><i class="fa-regular fa-check me-1"></i> Recebido</span>';

                    const balanceEl = document.querySelector('.balance-value span');
                    if (balanceEl && data.balance) {
                        balanceEl.textContent = data.balance;
                    }
                } else {
                    iziToast.error({
                        title: 'Atenção',
                        message: data.error || 'Erro ao processar recompensa.',
                        backgroundColor: '#b51408',
                        position: 'topRight'
                    });
                    this.innerHTML = original;
                    this.style.cursor = 'pointer';
                    this.style.opacity = '1';
                }
            })
            .catch(() => {
                iziToast.error({
                    title: 'Erro',
                    message: 'Falha na requisição. Tente novamente.',
                    backgroundColor: '#b51408',
                    position: 'topRight'
                });
                this.innerHTML = original;
                this.style.cursor = 'pointer';
                this.style.opacity = '1';
            });
        });
    });
</script>
@endpush
