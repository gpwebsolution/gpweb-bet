@extends('layouts.web')

@section('content')
    <div class="container-fluid">
        @include('includes.navbar_top')
        @include('includes.navbar_left')

        <div class="page__content">
            <br>
            <div class="container">
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                    <h4 class="mb-0 fw-bold">
                        <i class="fa-regular fa-circle-down me-2 text-success"></i>HISTÓRICO DE DEPÓSITOS
                    </h4>
                    <a href="{{ route('panel.wallet.deposit_form') }}" class="btn btn-primary-theme" style="padding: 8px 20px; font-size: 0.85rem;">
                        <i class="fa-regular fa-plus me-1"></i>Novo Depósito
                    </a>
                </div>

                <div class="filter-tabs mb-4">
                    <a href="{{ route('panel.wallet.deposits', ['filter' => 'today']) }}" class="filter-tab @if(($filter ?? 'all') === 'today') active @endif">Hoje</a>
                    <a href="{{ route('panel.wallet.deposits', ['filter' => 'week']) }}" class="filter-tab @if(($filter ?? 'all') === 'week') active @endif">Esta Semana</a>
                    <a href="{{ route('panel.wallet.deposits', ['filter' => 'month']) }}" class="filter-tab @if(($filter ?? 'all') === 'month') active @endif">Este Mês</a>
                    <a href="{{ route('panel.wallet.deposits', ['filter' => 'all']) }}" class="filter-tab @if(($filter ?? 'all') === 'all' && !$searchDate) active @endif">Todas</a>
                    <div class="ms-auto">
                        <input type="date" class="form-control form-control-sm" style="width:auto;max-width:160px;background:rgba(255,255,255,0.04) !important;color:#fff !important;border:1px solid rgba(255,255,255,0.08) !important;" value="{{ $searchDate ?? '' }}" onchange="window.location.href='{{ route('panel.wallet.deposits') }}?search_date='+this.value">
                        @if($searchDate)
                            <a href="{{ route('panel.wallet.deposits', ['filter' => 'all']) }}" class="filter-tab" style="padding:4px 10px;font-size:12px;"><i class="fa-regular fa-xmark"></i></a>
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
                                        <th>Pagamento</th>
                                        <th>Tipo</th>
                                        <th>Valor</th>
                                        <th>Status</th>
                                        <th>QR</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($deposits as $deposit)
                                        @php
                                            $qrCode = $deposit->pix_qrcode ?? '';
                                            $qrCopy = $deposit->pix_copy_paste ?? '';
                                            $isExpired = $deposit->expires_at && $deposit->expires_at->isPast();
                                            $showQr = ($qrCode || $qrCopy) && $deposit->status === 'pending' && !$isExpired;
                                        @endphp
                                        <tr>
                                            <td>{{ $deposit->id }}</td>
                                            <td class="txid-mono" title="payment_id: {{ $deposit->payment_id }}">
                                                {{ $deposit->payment_id ? \Illuminate\Support\Str::limit($deposit->payment_id, 18) : '---' }}
                                            </td>
                                            <td>Pix</td>
                                            <td class="fw-bold">{{ \Helper::amountFormatDecimal($deposit->amount) }}</td>
                                            <td>
                                                @if($deposit->status === 'paid')
                                                    <span class="status-badge status-confirmed">
                                                        <i class="fa-regular fa-circle-check me-1"></i>Confirmado
                                                    </span>
                                                @elseif($deposit->status === 'expired')
                                                    <span class="status-badge status-expired">
                                                        <i class="fa-regular fa-circle-xmark me-1"></i>Expirado
                                                    </span>
                                                @else
                                                    <span class="status-badge status-pending">
                                                        <i class="fa-regular fa-clock me-1"></i>Pendente
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($showQr)
                                                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#qrModal-{{ $deposit->id }}">
                                                        <i class="fa-regular fa-qrcode me-1"></i>Ver QR
                                                    </button>
                                                @elseif($deposit->status === 'expired')
                                                    <span class="text-muted"><i class="fa-regular fa-clock"></i></span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $deposit->created_at->format('d/m/Y H:i') }}</small>
                                                <br><small class="text-muted">{{ $deposit->dateHumanReadable ?? '' }}</small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <i class="fa-regular fa-circle-down text-muted" style="font-size:32px;"></i>
                                                <h5 class="text-muted mt-2">NENHUM DEPÓSITO ENCONTRADO</h5>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="p-3 d-flex justify-content-end">
                            {{ $deposits->appends(['filter' => $filter ?? 'all', 'search_date' => $searchDate ?? ''])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/assets/js/qrcode.min.js') }}"></script>

    @foreach($deposits as $deposit)
        @php
            $qrCode = $deposit->pix_qrcode ?? '';
            $qrCopy = $deposit->pix_copy_paste ?? '';
            $isExpired = $deposit->expires_at && $deposit->expires_at->isPast();
            $showQr = ($qrCode || $qrCopy) && $deposit->status === 'pending' && !$isExpired;
        @endphp
        @if($showQr)
            <div class="modal fade" id="qrModal-{{ $deposit->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content bg-dark text-white">
                        <div class="modal-header border-0">
                            <h6 class="modal-title">Pagamento PIX</h6>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <p class="mb-2 fw-bold">R$ {{ number_format($deposit->amount, $deposit->amount == floor($deposit->amount) ? 0 : 2, ',', '.') }}</p>
                            @if($qrCode || $qrCopy)
                                <div id="qrCodeModal-{{ $deposit->id }}" class="mx-auto mb-3" style="width:240px;height:240px;"></div>
                                <script>
                                    new QRCode(document.getElementById('qrCodeModal-{{ $deposit->id }}'), {
                                        width: 240, height: 240,
                                        colorDark: '#ffffff', colorLight: '#1A1C1F',
                                        correctLevel: QRCode.CorrectLevel.H
                                    }).makeCode({!! json_encode($qrCopy ?: $qrCode) !!});
                                </script>
                            @endif
                            @if($qrCopy)
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary" value="{{ $qrCopy }}" id="pixKey-{{ $deposit->id }}" readonly>
                                    <button class="btn btn-sm btn-outline-info" onclick="copyPix('pixKey-{{ $deposit->id }}')">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            @endif
                            <p class="text-muted small mb-0">Escaneie o QR Code ou copie o código PIX para pagar</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <script>
    function copyPix(elId) {
        var input = document.getElementById(elId);
        if (input) {
            input.select();
            document.execCommand('copy');
            iziToast.show({
                title: 'Sucesso',
                message: 'Código PIX copiado!',
                theme: 'dark',
                icon: 'fa-solid fa-check',
                iconColor: '#ffffff',
                backgroundColor: '#23ab0e',
                position: 'topRight',
                timeout: 1500
            });
        }
    }

    (function() {
        var pendingRows = document.querySelectorAll('.status-pending');
        if (!pendingRows.length) return;

        var baseUrl = '{{ url(\Helper::getGatewaySelected()) }}';
        var pendingIds = [];
        var rowMap = {};

        pendingRows.forEach(function(badge) {
            var row = badge.closest('tr');
            if (!row) return;
            var paymentId = row.querySelector('.txid-mono');
            if (!paymentId) return;
            var id = paymentId.title.replace('payment_id: ', '');
            if (!id) return;
            pendingIds.push(id);
            rowMap[id] = row;
        });

        if (!pendingIds.length) return;

        function poll() {
            pendingIds.forEach(function(id) {
                fetch(baseUrl + '/consult-status-transaction', {
                    method: 'POST',
                    body: JSON.stringify({ idTransaction: id }),
                    headers: new Headers({
                        'Content-Type': 'application/json; charset=UTF-8',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    })
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.status === 'PAID' || data.status === 'EXPIRED') {
                        var row = rowMap[id];
                        if (!row) return;
                        var badge = row.querySelector('.status-pending, .status-badge');
                        if (!badge) return;
                        if (data.status === 'PAID') {
                            badge.className = 'status-badge status-confirmed';
                            badge.innerHTML = '<i class="fa-regular fa-circle-check me-1"></i>Confirmado';
                        } else {
                            badge.className = 'status-badge status-expired';
                            badge.innerHTML = '<i class="fa-regular fa-circle-xmark me-1"></i>Expirado';
                        }
                        var qrCell = row.querySelector('td:nth-child(6)');
                        if (qrCell) {
                            qrCell.innerHTML = '<span class="text-muted">\u2014</span>';
                        }
                        var msg = data.status === 'PAID'
                            ? 'Dep\u00f3sito de ' + (row.querySelector('.fw-bold')?.textContent || '') + ' foi processado.'
                            : 'QR Code expirado. Gere um novo.';
                        iziToast.show({
                            title: data.status === 'PAID' ? 'Pagamento Confirmado!' : 'QR Code Expirado',
                            message: msg,
                            theme: 'dark',
                            icon: data.status === 'PAID' ? 'fa-solid fa-circle-check' : 'fa-regular fa-circle-exclamation',
                            iconColor: '#ffffff',
                            backgroundColor: data.status === 'PAID' ? '#23ab0e' : '#b51408',
                            position: 'topRight',
                            timeout: 4000
                        });
                        pendingIds = pendingIds.filter(function(pid) { return pid !== id; });
                        delete rowMap[id];
                    }
                })
                .catch(function() {});
            });
        }

        setInterval(poll, 5000);
        poll();
    })();
    </script>
@endsection
