@extends('layouts.web')

@section('content')
    @include('includes.navbar_top')
    @include('includes.navbar_left')

    <div class="page__content">
        <br>
        <div class="container">
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                    <h4 class="mb-0 fw-bold">
                        <i class="fa-regular fa-circle-up me-2 text-warning"></i>HISTÓRICO DE SAQUES
                    </h4>
                    <a href="{{ route('panel.wallet.saque_form') }}" class="btn btn-primary-theme" style="padding: 8px 20px; font-size: 0.85rem;">
                        <i class="fa-regular fa-plus me-1"></i>Novo Saque
                    </a>
                </div>

                <div class="filter-tabs mb-4">
                    <a href="{{ route('panel.wallet.saques', ['filter' => 'today']) }}" class="filter-tab @if(($filter ?? 'all') === 'today') active @endif">Hoje</a>
                    <a href="{{ route('panel.wallet.saques', ['filter' => 'week']) }}" class="filter-tab @if(($filter ?? 'all') === 'week') active @endif">Esta Semana</a>
                    <a href="{{ route('panel.wallet.saques', ['filter' => 'month']) }}" class="filter-tab @if(($filter ?? 'all') === 'month') active @endif">Este Mês</a>
                    <a href="{{ route('panel.wallet.saques', ['filter' => 'all']) }}" class="filter-tab @if(($filter ?? 'all') === 'all' && !$searchDate) active @endif">Todas</a>
                    <div class="ms-auto">
                        <input type="date" class="form-control form-control-sm" style="width:auto;max-width:160px;background:rgba(255,255,255,0.04) !important;color:#fff !important;border:1px solid rgba(255,255,255,0.08) !important;" value="{{ $searchDate ?? '' }}" onchange="window.location.href='{{ route('panel.wallet.saques') }}?search_date='+this.value">
                        @if($searchDate)
                            <a href="{{ route('panel.wallet.saques', ['filter' => 'all']) }}" class="filter-tab" style="padding:4px 10px;font-size:12px;"><i class="fa-regular fa-xmark"></i></a>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Chave Pix</th>
                                        <th>Tipo</th>
                                        <th>Valor</th>
                                        <th>Comprovante</th>
                                        <th>Status</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($saques as $saque)
                                        <tr>
                                            <td>{{ $saque->id }}</td>
                                            <td>
                                                <small class="text-muted">{{ \Illuminate\Support\Str::mask($saque->chave_pix, '*', 3, -3) }}</small>
                                                <br><small class="txid-mono" style="font-size:10px;color:#666;">{{ $saque->tipo_chave }}</small>
                                            </td>
                                            <td>{{ $saque->type }}</td>
                                            <td class="fw-bold">{{ \Helper::amountFormatDecimal($saque->amount) }}</td>
                                            <td>
                                                @if(!empty($saque->proof))
                                                    <a href="{{ url('storage/'. $saque->proof) }}" download class="text-success">
                                                        <i class="fa-regular fa-download"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($saque->status == 0)
                                                    <span class="status-badge status-pending">
                                                        <i class="fa-regular fa-clock me-1"></i>Pendente
                                                    </span>
                                                @elseif($saque->status == 1)
                                                    <span class="status-badge status-confirmed">
                                                        <i class="fa-regular fa-circle-check me-1"></i>Confirmado
                                                    </span>
                                                @else
                                                    <span class="status-badge status-cancelled">
                                                        <i class="fa-regular fa-circle-xmark me-1"></i>Cancelado
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $saque->created_at->format('d/m/Y H:i') }}</small>
                                                <br><small class="text-muted">{{ $saque->dateHumanReadable ?? '' }}</small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <i class="fa-regular fa-circle-up text-muted" style="font-size:32px;"></i>
                                                <h5 class="text-muted mt-2">NENHUM SAQUE ENCONTRADO</h5>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="p-3 d-flex justify-content-end">
                            {{ $saques->appends(['filter' => $filter ?? 'all', 'search_date' => $searchDate ?? ''])->links() }}
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
