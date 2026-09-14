@extends('layouts.web')

@push('styles')
<style>
    .affiliate-gradient-card {
        background: linear-gradient(135deg, #e63946 0%, #c1121f 100%);
        border: none;
        border-radius: 16px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .affiliate-gradient-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(230, 57, 70, 0.3);
    }
    .affiliate-stat-card {
        background: #1a1c1f;
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .affiliate-stat-card:hover {
        border-color: rgba(230, 57, 70, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    }
    .affiliate-table {
        border-radius: 12px;
        overflow: hidden;
        background: #1a1c1f;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .affiliate-table thead th {
        background: #0d0d0d;
        color: #e63946;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 14px 16px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .affiliate-table tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid rgba(255,255,255,0.03);
        color: #e0e0e0;
        font-size: 0.875rem;
    }
    .affiliate-table tbody tr:hover {
        background: rgba(230, 57, 70, 0.05);
    }
    .affiliate-table tbody tr:last-child td {
        border-bottom: none;
    }
    .earnings-value {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .copy-btn {
        background: #e63946;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 600;
    }
    .copy-btn:hover {
        background: #c1121f;
        transform: scale(1.02);
    }
    .modal-affiliate {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(4px);
        animation: fadeIn 0.2s ease;
    }
    .modal-affiliate.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .modal-affiliate-content {
        background: #1a1c1f;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 16px;
        max-width: 480px;
        width: 90%;
        padding: 32px;
        animation: slideUp 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .badge-affiliate {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-pending { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .badge-paid { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    .badge-info { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
    .affiliate-link-input {
        background: #0d0d0d;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        padding: 12px 16px;
        color: white;
        width: 100%;
        font-size: 0.875rem;
    }
    .affiliate-link-input:focus {
        outline: none;
        border-color: #e63946;
    }
    .loading-spinner-affiliate {
        display: none;
        text-align: center;
        padding: 20px;
    }
    .loading-spinner-affiliate .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid rgba(255,255,255,0.1);
        border-top-color: #e63946;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        display: inline-block;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .affiliate-cta {
        background: linear-gradient(135deg, rgba(230,57,70,0.1) 0%, rgba(193,18,31,0.05) 100%);
        border: 1px solid rgba(230,57,70,0.2);
        border-radius: 16px;
        padding: 40px;
    }
    .tab-btn { position: relative; }
    .tab-btn::after {
        content: ''; position: absolute; bottom: -8px; left: 0; right: 0;
        height: 2px; background: #e63946; transform: scaleX(0);
        transition: transform 0.2s ease;
    }
    .tab-btn:hover::after, .tab-btn:focus::after { transform: scaleX(1); }
    .tab-btn:hover { color: rgba(255,255,255,0.7) !important; }
</style>
@endpush

@section('content')
    @include('includes.navbar_top')
    @include('includes.navbar_left')

    <div class="page__content">
        <div class="container">

            {{-- Header --}}
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #e63946, #c1121f); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-link" style="color: white; font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold" style="color: white;">Sistema de Afiliados</h3>
                        <p class="mb-0" style="color: rgba(255,255,255,0.5); font-size: 0.875rem;">
                            Compartilhe seu link e ganhe <strong style="color: #e63946;">{{ $revsharePercent }}%</strong> de todas as apostas dos seus indicados
                        </p>
                    </div>
                </div>

                {{-- Affiliate Link Card --}}
                <div class="affiliate-gradient-card p-4 mb-4">
                    <div class="row align-items-center">
                        <div class="col-lg-7 mb-3 mb-lg-0">
                            <p class="mb-1" style="color: rgba(255,255,255,0.7); font-size: 0.875rem;">Seu link de afiliado</p>
                            <div class="d-flex gap-2">
                                <input type="text" id="urlInput" class="affiliate-link-input" value="{{ $affiliateLink }}" readonly>
                                <button class="copy-btn" onclick="copyToClipboard()">
                                    <i class="fa-regular fa-copy me-1"></i> COPIAR
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-5 text-lg-end">
                            <p class="mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.875rem;">
                                <i class="fa-regular fa-users me-1"></i> Total de indicados: <strong style="color: white;">{{ $stats['total_indicated'] }}</strong>
                            </p>
                            <p class="mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.875rem;">
                                <i class="fa-regular fa-chart-line me-1"></i> Volume de apostas: <strong style="color: white;">R$ {{ number_format($stats['total_bet_volume'], 2, ',', '.') }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->affiliate_revenue_share > 0 || $revsharePercent > 0)

                    {{-- Stats Cards --}}
                    <div class="row g-3 mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="affiliate-stat-card p-3 text-center">
                                <p class="mb-1" style="color: rgba(255,255,255,0.5); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Ganhos Disponíveis</p>
                                <div class="earnings-value" style="font-size: 1.75rem;">R$ {{ number_format($stats['total_earnings'], 2, ',', '.') }}</div>
                                <button data-modal-target="#saqueModal" class="btn btn-sm mt-2" style="background: #e63946; color: white; border-radius: 8px; font-weight: 600;">
                                    <i class="fa-regular fa-arrow-right-to-bracket me-1"></i> Resgatar
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="affiliate-stat-card p-3 text-center">
                                <p class="mb-1" style="color: rgba(255,255,255,0.5); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Comissão</p>
                                <div style="font-size: 1.75rem; font-weight: 800; color: #e63946;">{{ $revsharePercent }}%</div>
                                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.4); font-size: 0.75rem;">Revenue Share</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="affiliate-stat-card p-3 text-center">
                                <p class="mb-1" style="color: rgba(255,255,255,0.5); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Ganhos no Mês</p>
                                <div style="font-size: 1.75rem; font-weight: 800; color: #10b981;">R$ {{ number_format($monthlyEarnings, 2, ',', '.') }}</div>
                                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.4); font-size: 0.75rem;">Comissões recebidas</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="affiliate-stat-card p-3 text-center">
                                <p class="mb-1" style="color: rgba(255,255,255,0.5); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Indicados</p>
                                <div style="font-size: 1.75rem; font-weight: 800; color: #3b82f6;">{{ $stats['total_indicated'] }}</div>
                                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.4); font-size: 0.75rem;">Total de cadastros</p>
                            </div>
                        </div>
                    </div>

                    {{-- Tabs: Indicados / Comissões --}}
                    <div class="affiliate-table mb-4">
                        <div class="px-3 py-3 d-flex align-items-center gap-3 flex-wrap" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <button class="tab-btn active" data-tab="tab-indications" style="background:none;border:none;color:#e63946;font-weight:700;font-size:0.9rem;padding:0;text-transform:uppercase;letter-spacing:0.05em;">
                                <i class="fa-regular fa-users me-1"></i> Indicados
                            </button>
                            <button class="tab-btn" data-tab="tab-commissions" style="background:none;border:none;color:rgba(255,255,255,0.4);font-weight:600;font-size:0.9rem;padding:0;text-transform:uppercase;letter-spacing:0.05em;transition:color 0.2s;">
                                <i class="fa-regular fa-sack-dollar me-1"></i> Comissões
                            </button>
                            <div class="ms-auto d-flex gap-3 align-items-center flex-wrap">
                                <input type="date" class="form-control form-control-sm" style="width:auto;max-width:150px;background:rgba(255,255,255,0.04) !important;color:#fff !important;border:1px solid rgba(255,255,255,0.08) !important;" value="{{ $searchDate ?? '' }}" onchange="window.location.href='{{ url('painel/affiliates') }}?search_date='+this.value">
                                @if($searchDate)
                                    <a href="{{ url('painel/affiliates') }}" class="btn btn-sm" style="background:rgba(255,255,255,0.05);color:#aaa;padding:4px 10px;border-radius:6px;text-decoration:none;"><i class="fa-regular fa-xmark"></i></a>
                                @endif
                                <div style="font-size:0.75rem;color:rgba(255,255,255,0.4);">
                                    <i class="fa-regular fa-circle-check me-1" style="color:#10b981;"></i> Recebido: <strong style="color:#10b981;">R$ {{ number_format($stats['total_earnings'], 2, ',', '.') }}</strong>
                                </div>
                                <div style="font-size:0.75rem;color:rgba(255,255,255,0.4);">
                                    <i class="fa-regular fa-clock me-1" style="color:#f59e0b;"></i> Pendente: <strong style="color:#f59e0b;">R$ {{ number_format($stats['pending_commissions'], 2, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Tab: Indicados --}}
                        <div id="tab-indications" class="tab-content">
                            <div class="table-responsive">
                                <table class="table affiliate-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Data de Cadastro</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($indications as $indication)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div style="width: 32px; height: 32px; background: linear-gradient(135deg,#e63946,#c1121f); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 13px;">
                                                            {{ strtoupper(substr($indication->name ?? '--', 0, 1)) }}
                                                        </div>
                                                        <span>{{ $indication->name }}</span>
                                                    </div>
                                                </td>
                                                <td style="font-size:0.85rem;color:rgba(255,255,255,0.5);">{{ $indication->created_at ? \Carbon\Carbon::parse($indication->created_at)->diffForHumans() : '--' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center py-4" style="color: rgba(255,255,255,0.4);">
                                                    <i class="fa-regular fa-inbox me-2"></i> Nenhum indicado encontrado
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($indications->hasPages())
                                <div class="p-3" style="border-top: 1px solid rgba(255,255,255,0.05);">
                                    {{ $indications->appends(['search_date' => $searchDate ?? ''])->links() }}
                                </div>
                            @endif
                        </div>

                        {{-- Tab: Comissões --}}
                        <div id="tab-commissions" class="tab-content" style="display:none;">
                            <div class="table-responsive">
                                <table class="table affiliate-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Usuário</th>
                                            <th>Total Gerado</th>
                                            <th>Recebido</th>
                                            <th>Pendente</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($histories as $history)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#e63946,#c1121f);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:14px;">
                                                            {{ strtoupper(substr($history->user->name ?? '--', 0, 1)) }}
                                                        </div>
                                                        <strong>{{ $history->user->name ?? '--' }}</strong>
                                                    </div>
                                                </td>
                                                <td><strong style="color:#fbbf24;">R$ {{ number_format($history->total_commission, 2, ',', '.') }}</strong></td>
                                                <td>
                                                    <span style="color:#10b981;">
                                                        R$ {{ number_format($history->total_received ?? 0, 2, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if(($history->total_pending ?? 0) > 0)
                                                        <span style="color:#f59e0b;">
                                                            R$ {{ number_format($history->total_pending, 2, ',', '.') }}
                                                        </span>
                                                    @else
                                                        <span style="color:rgba(255,255,255,0.2);">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4" style="color: rgba(255,255,255,0.4);">
                                                    <i class="fa-regular fa-inbox me-2"></i> Nenhuma comissão registrada
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($histories->hasPages())
                                <div class="p-3" style="border-top: 1px solid rgba(255,255,255,0.05);">
                                    {{ $histories->appends(['search_date' => $searchDate ?? ''])->links() }}
                                </div>
                            @endif
                        </div>
                    </div>

                @else
                    {{-- CTA for non-affiliates --}}
                    <div class="affiliate-cta text-center">
                        <img src="{{ asset('/assets/images/business_afiliado.png') }}" alt="Afiliado" class="img-fluid mb-3" style="max-height: 180px;">
                        <h3 class="fw-bold mb-2" style="color: white;">SAIBA MAIS SOBRE NOSSO <span style="color: #e63946;">PROGRAMA DE AFILIADOS</span></h3>
                        <p style="color: rgba(255,255,255,0.6); max-width: 600px; margin: 0 auto 24px;">
                            Trabalhe conosco como afiliado e obtenha lucros significativos por meio de suas indicações.
                            Oferecemos condições especiais exclusivas para nossos afiliados.
                        </p>
                        <form action="{{ route('panel.affiliates.join') }}" method="post" class="mx-auto" style="max-width: 440px;">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="email" class="form-control" placeholder="Digite seu email" aria-label="Seu e-mail" style="background: #0d0d0d; border: 1px solid rgba(255,255,255,0.1); color: white; padding: 12px;">
                                <button type="submit" class="copy-btn">
                                    <i class="fa-solid fa-envelope me-2"></i> Enviar agora
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- FAQ --}}
                    <div class="affiliate-table mt-4">
                        <div class="px-3 py-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <h5 class="mb-0" style="color: white;"><i class="fa-regular fa-circle-question me-2" style="color: #e63946;"></i> Perguntas Frequentes</h5>
                        </div>
                        <div class="p-3">
                            <div class="accordion" id="affiliateFaq">
                                <div class="accordion-item" style="background: transparent; border: none; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" style="background: transparent; color: white; box-shadow: none;">
                                            Como funciona o sistema de referência?
                                        </button>
                                    </h2>
                                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#affiliateFaq">
                                        <div class="accordion-body" style="color: rgba(255,255,255,0.6);">
                                            Quando você compartilha seu link de referência e um jogador se inscreve em nosso site,
                                            esse jogador se torna sua referência e você ganha <strong style="color: #e63946;">{{ $revsharePercent }}%</strong> de comissão sobre todas as apostas que ele realizar.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item" style="background: transparent; border: none; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" style="background: transparent; color: white; box-shadow: none;">
                                            Quanto posso ganhar com a minha indicação?
                                        </button>
                                    </h2>
                                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#affiliateFaq">
                                        <div class="accordion-body" style="color: rgba(255,255,255,0.6);">
                                            Você ganha <strong style="color: #e63946;">{{ $revsharePercent }}%</strong> de todas as apostas dos seus indicados. Não há limite de ganhos! Quanto mais pessoas você indicar, mais você ganha.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item" style="background: transparent; border: none;">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" style="background: transparent; color: white; box-shadow: none;">
                                            Como recebo meus ganhos?
                                        </button>
                                    </h2>
                                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#affiliateFaq">
                                        <div class="accordion-body" style="color: rgba(255,255,255,0.6);">
                                            Seus ganhos ficam acumulados em sua carteira de afiliado. Você pode solicitar o saque a qualquer momento clicando em "Resgatar". O valor será transferido para seu saldo principal, podendo ser sacado normalmente.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Saque Modal --}}
        <div id="saqueModal" class="modal-affiliate">
            <div class="modal-affiliate-content">
                <div class="text-center mb-4">
                    <div style="width: 64px; height: 64px; background: rgba(230,57,70,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <i class="fa-regular fa-money-bill-transfer" style="color: #e63946; font-size: 1.5rem;"></i>
                    </div>
                    <h5 class="fw-bold" style="color: white;">Solicitar Resgate</h5>
                    <p style="color: rgba(255,255,255,0.5); font-size: 0.875rem;">
                        Seus ganhos disponíveis: <strong style="color: #fbbf24;">R$ {{ number_format($stats['total_earnings'], 2, ',', '.') }}</strong>
                    </p>
                </div>

                <div id="loadingAffiliateSaque" class="loading-spinner-affiliate">
                    <span class="spinner"></span>
                    <p class="mt-2" style="color: rgba(255,255,255,0.5);">Processando...</p>
                </div>

                <div class="affiliate-saque-body mb-4" style="color: rgba(255,255,255,0.7); font-size: 0.875rem; text-align: center;">
                    Ao confirmar, você autoriza a transferência da sua comissão para sua carteira principal, possibilitando o saque dos seus ganhos.
                </div>

                <div class="d-flex gap-2">
                    <button class="btn flex-fill request-affiliate-saque" style="background: #e63946; color: white; padding: 12px; border-radius: 8px; font-weight: 600; border: none;">
                        <i class="fa-regular fa-check me-1"></i> CONFIRMAR RESGATE
                    </button>
                    <button class="btn flex-fill close-modal-btn" style="background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.6); padding: 12px; border-radius: 8px; font-weight: 600; border: 1px solid rgba(255,255,255,0.1);">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyToClipboard() {
            const input = document.getElementById('urlInput');
            input.select();
            document.execCommand('copy');

            iziToast.show({
                title: 'Sucesso',
                message: 'URL copiada para a área de transferência.',
                theme: 'dark',
                icon: 'fa-solid fa-check',
                iconColor: '#ffffff',
                backgroundColor: '#23ab0e',
                position: 'topRight',
            });
        }

        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => {
                    b.style.color = 'rgba(255,255,255,0.4)';
                    b.style.fontWeight = '600';
                });
                this.style.color = '#e63946';
                this.style.fontWeight = '700';
                document.querySelectorAll('.tab-content').forEach(tc => tc.style.display = 'none');
                document.getElementById(this.dataset.tab).style.display = '';
            });
        });

        document.querySelectorAll('[data-modal-target]').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = document.querySelector(this.dataset.modalTarget);
                if (target) target.classList.add('active');
            });
        });

        document.querySelectorAll('.close-modal-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.modal-affiliate').classList.remove('active');
            });
        });

        document.querySelectorAll('.modal-affiliate').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) this.classList.remove('active');
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-affiliate.active').forEach(m => m.classList.remove('active'));
            }
        });

        document.querySelector('.request-affiliate-saque')?.addEventListener('click', function(event) {
            event.preventDefault();

            const loadingElement = document.getElementById('loadingAffiliateSaque');
            const bodyElement = document.querySelector('.affiliate-saque-body');
            const buttons = this.closest('.d-flex');

            loadingElement.style.display = 'block';
            bodyElement.style.display = 'none';
            buttons.style.display = 'none';

            const csrfToken = document.head.querySelector("[name~=csrf-token][content]").content;

            fetch('{{ url('painel/affiliates/saque') }}', {
                method: 'post',
                body: JSON.stringify({}),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    iziToast.show({
                        title: 'Sucesso',
                        message: 'A conversão foi concluída com êxito.',
                        theme: 'dark',
                        icon: 'fa-solid fa-check',
                        iconColor: '#ffffff',
                        backgroundColor: '#23ab0e',
                        position: 'topRight',
                        timeout: 1500,
                        onClosed: function() {
                            document.getElementById('saqueModal').classList.remove('active');
                            setTimeout(function() {
                                window.location.replace('{{ route('panel.wallet.saques') }}');
                            }, 1000);
                        }
                    });
                } else {
                    iziToast.show({
                        title: 'Atenção',
                        message: data.error || 'Erro ao processar resgate.',
                        theme: 'dark',
                        icon: 'fa-regular fa-circle-exclamation',
                        iconColor: '#ffffff',
                        backgroundColor: '#b51408',
                        position: 'topRight'
                    });
                    loadingElement.style.display = 'none';
                    bodyElement.style.display = 'block';
                    buttons.style.display = 'flex';
                }
            })
            .catch(error => {
                loadingElement.style.display = 'none';
                bodyElement.style.display = 'block';
                buttons.style.display = 'flex';
            });
        });
    </script>
@endpush
