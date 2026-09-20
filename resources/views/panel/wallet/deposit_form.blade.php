@extends('layouts.web')

@section('content')
    @include('includes.navbar_top')
    @include('includes.navbar_left')

    <div class="page__content">
        <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                    <div class="deposit-card position-relative">
                        <div id="loadingDepositPage" class="loading-overlay">
                            <span class="spinner"></span>
                        </div>

                        @php $qrData = session('qr_data'); @endphp
                        <div id="qrcode-container" @if(!$qrData) style="display:none;" @endif>
                            <div class="text-center mb-3">
                                <div class="check-icon-wrapper">
                                    <i class="fa-regular fa-circle-check"></i>
                                </div>
                                <h5 class="text-white mt-3">ESCANEIE O QRCODE PARA PAGAR</h5>
                            </div>
                            <div id="qrcode" style="width:260px;height:260px;margin:0 auto;"></div>
                            <input id="pixcopiaecola" type="text" class="form-control mt-3 text-center" value="{{ $qrData['pixCopiaECola'] ?? $qrData['qrcode'] ?? '' }}" readonly>
                            <div class="d-grid mt-2">
                                <button id="copyQrcodePix" class="btn btn-primary" type="button" style="background: var(--cor-principal); border-color: var(--cor-principal);">
                                    <i class="fa-regular fa-copy me-2"></i>COPIAR CÓDIGO PIX
                                </button>
                            </div>
                            <div class="mt-4 text-center">
                                <span class="badge-pulse" id="paymentStatusBadge">
                                    <i class="fa fa-spin fa-spinner me-2"></i> Aguardando pagamento...
                                </span>
                                <div class="mt-2">
                                    <small class="text-muted" id="expiryCountdown"></small>
                                </div>
                            </div>
                            <div class="d-grid mt-4">
                                <a href="{{ route('panel.wallet.deposit_form') }}" class="btn btn-outline-secondary">CANCELAR</a>
                            </div>
                        </div>

                        <form id="depositFormPage" method="post" action="{{ route('panel.wallet.deposit') }}" @if($qrData) style="display:none;" @endif>
                                @csrf
                                <div class="text-center mb-4">
                                    <div class="pix-icon-wrapper">
                                        <i class="fa-brands fa-pix"></i>
                                    </div>
                                    <h4 class="text-white mt-3 fw-bold">DEPOSITAR VIA PIX</h4>
                                    <p class="text-muted small">Escolha ou digite o valor desejado</p>
                                </div>

                                <div class="amount-quick-select mb-4">
                                    @php
                                        $min = (float) (config('setting')['min_deposit'] ?? 10);
                                        $values = [$min, $min*2, $min*5, $min*10, $min*20, $min*50];
                                    @endphp
                                    @foreach($values as $val)
                                        <button type="button" class="amount-btn" data-value="{{ (int)$val }}">R$ {{ number_format($val, 0, ',', '.') }}</button>
                                    @endforeach
                                </div>

                                <div class="input-group mb-3 input-group-custom">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" name="amount" required min="{{ config('setting')['min_deposit'] ?? 1 }}" max="{{ config('setting')['max_deposit'] ?? 99999 }}" class="form-control amount-input" placeholder="0,00" step="0.01">
                                </div>

                                <div class="input-group mb-3 input-group-custom">
                                    <span class="input-group-text"><i class="fa-regular fa-address-card"></i></span>
                                    @if(auth()->user()->cpf_confirmed)
                                        <input type="text" class="form-control cpf" value="{{ auth()->user()->cpf }}" readonly disabled>
                                        <input type="hidden" name="cpf" value="{{ auth()->user()->cpf }}">
                                    @else
                                        <input type="text" name="cpf" class="form-control cpf" value="" required placeholder="SEU CPF">
                                    @endif
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn-primary-theme btn-block w-full pulse-btn">
                                        <i class="fa-regular fa-qrcode me-2"></i>GERAR QRCODE
                                    </button>
                                </div>
                            </form>

                            <div class="mt-4 text-center">
                                <a href="{{ route('panel.wallet.deposits') }}" class="text-muted small">
                                    <i class="fa-regular fa-clock-rotate-left me-1"></i>Ver histórico de depósitos
                                </a>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('/assets/js/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('/assets/js/qrcode.min.js') }}"></script>
    <script>
        (function() {
        var paymentWatcher = null;

        function stopPaymentWatcher() {
            if (paymentWatcher) {
                paymentWatcher.stop();
                paymentWatcher = null;
            }
        }

        function onPaymentPaid() {
            stopPaymentWatcher();
            iziToast.show({ title: 'Pagamento Confirmado!', message: 'Seu depósito foi processado com sucesso.',
                theme: 'dark', icon: 'fa-solid fa-circle-check', iconColor: '#ffffff',
                backgroundColor: '#23ab0e', position: 'topRight', timeout: 3000,
                onClosed: function() { window.location.replace('{{ route('panel.wallet.deposits') }}'); }
            });
        }

        function onPaymentExpired() {
            stopPaymentWatcher();
            iziToast.show({ title: 'QR Code Expirado', message: 'O tempo para pagamento expirou. Gere um novo QR Code.',
                theme: 'dark', icon: 'fa-regular fa-circle-exclamation', iconColor: '#ffffff',
                backgroundColor: '#b51408', position: 'topRight', timeout: 5000,
                onClosed: function() { location.reload(); }
            });
        }

        function onPaymentError() {
            iziToast.show({ title: 'Erro de conexão', message: 'Verificando pagamento novamente...',
                theme: 'dark', icon: 'fa-regular fa-circle-exclamation', iconColor: '#ffffff',
                backgroundColor: '#856404', position: 'topRight', timeout: 2000
            });
        }

        var PaymentWatcher = {
            _id: null, _pollId: null, _running: false,
            _expiresAt: null, _countdownId: null,
            _baseUrl: '{{ url(\Helper::getGatewaySelected()) }}',

            start: function(idTransaction, expiresAt) {
                this._id = idTransaction;
                this._expiresAt = expiresAt;
                this._running = true;
                this._startCountdown();
                this._startPolling(idTransaction);
            },

            stop: function() {
                this._running = false;
                if (this._pollId) { clearInterval(this._pollId); this._pollId = null; }
                if (this._countdownId) { clearInterval(this._countdownId); this._countdownId = null; }
            },

            _startCountdown: function() {
                if (!this._expiresAt) return;
                var self = this;
                var el = document.getElementById('expiryCountdown');
                if (!el) return;
                function update() {
                    if (!self._running || !self._expiresAt) return;
                    var now = new Date();
                    var end = new Date(self._expiresAt);
                    var diff = Math.max(0, Math.floor((end - now) / 1000));
                    if (diff <= 0) { el.textContent = 'Expirado'; return; }
                    el.textContent = 'Expira em ' + Math.floor(diff / 60) + 'm ' + (diff % 60) + 's';
                }
                update();
                this._countdownId = setInterval(update, 1000);
            },

            _startPolling: function(idTransaction) {
                var self = this;
                var poll = function() {
                    if (!self._running) return;
                    fetch(self._baseUrl + '/consult-status-transaction', {
                        method: 'POST',
                        body: JSON.stringify({ idTransaction }),
                        headers: new Headers({
                            'Content-Type': 'application/json; charset=UTF-8',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        })
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (!self._running) return;
                        if (data.status === 'PAID') { onPaymentPaid(); }
                        else if (data.status === 'EXPIRED') { onPaymentExpired(); }
                        else if (data.status === 'ERROR') { onPaymentError(); }
                    })
                    .catch(function() {});
                };
                poll();
                this._pollId = setInterval(poll, 4000);
            }
        };

        @if(session('qr_data'))
            (function() {
                var txId = {!! json_encode(session('qr_data')['idTransaction']) !!};
                var expiresAt = {!! json_encode(session('qr_data')['expires_at'] ?? null) !!};
                var qrCode = document.getElementById('qrcode');
                if (qrCode && !qrCode.querySelector('img')) {
                    new QRCode(qrCode, { width: 260, height: 260, colorDark: '#ffffff', colorLight: '#1A1C1F', correctLevel: QRCode.CorrectLevel.H }).makeCode({!! json_encode(session('qr_data')['pixCopiaECola'] ?? session('qr_data')['qrcode']) !!});
                }

                fetch('{{ url(\Helper::getGatewaySelected().'/consult-status-transaction') }}', {
                    method: 'POST', body: JSON.stringify({ idTransaction: txId }),
                    headers: new Headers({
                        'Content-Type': 'application/json; charset=UTF-8',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    })
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.status === 'PAID') {
                        window.location.replace('{{ route('panel.wallet.deposits') }}');
                        return;
                    }
                    if (data.status !== 'EXPIRED') {
                        paymentWatcher = Object.create(PaymentWatcher);
                        paymentWatcher.start(txId, expiresAt);
                    }
                })
                .catch(function() {
                    paymentWatcher = Object.create(PaymentWatcher);
                    paymentWatcher.start(txId, expiresAt);
                });
            })();
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            $('.cpf').mask('000.000.000-00', {reverse: true});
        });

        document.getElementById("copyQrcodePix").addEventListener("click", function() {
            var inputElement = document.getElementById("pixcopiaecola");
            inputElement.select(); inputElement.setSelectionRange(0, 99999);
            document.execCommand("copy");
            iziToast.show({ title: 'Sucesso', message: 'Chave Pix copiada com sucesso!', theme: 'dark',
                icon: 'fa-solid fa-check', iconColor: '#ffffff', backgroundColor: '#23ab0e',
                position: 'topRight', timeout: 1500 });
        });

        document.getElementById('depositFormPage').addEventListener('submit', function(event) {
            event.preventDefault();
            const loadingElement = document.getElementById('loadingDepositPage');
            loadingElement.style.display = 'flex';
            const formData = new FormData(this);
            var cpfRaw = (formData.get('cpf') || '').replace(/\D/g, '');
            formData.set('cpf', cpfRaw);
            fetch('{{ url(\Helper::getGatewaySelected().'/qrcode-pix') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
                .then(response => response.json())
                .then(data => {
                    if(data.status) {
                        var qrContainer = document.getElementById('qrcode-container');
                        qrContainer.style.display = 'block';
                        qrContainer.classList.add('fade-in-qr');
                        document.getElementById('depositFormPage').style.display = 'none';
                        var pixPayload = data.pixCopiaECola || data.qrcode;
                        new QRCode(document.getElementById('qrcode'), { width: 260, height: 260, colorDark: '#ffffff', colorLight: '#1A1C1F', correctLevel: QRCode.CorrectLevel.H }).makeCode(pixPayload);
                        document.getElementById("pixcopiaecola").value = pixPayload;
                        paymentWatcher = Object.create(PaymentWatcher);
                        paymentWatcher.start(data.idTransaction, data.expires_at);
                    } else {
                        var msg = data.error || 'Erro ao gerar QR Code';
                        iziToast.show({ title: 'Atenção', message: msg, theme: 'dark', icon: 'fa-regular fa-circle-exclamation', iconColor: '#ffffff', backgroundColor: '#b51408', position: 'topRight' });
                    }
                    loadingElement.style.display = 'none';
                }).catch(function() { loadingElement.style.display = 'none'; });
        });

        document.querySelectorAll('#depositFormPage .amount-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('#depositFormPage .amount-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                document.querySelector('#depositFormPage input[name="amount"]').value = this.dataset.value;
            });
        });
        document.querySelector('#depositFormPage input[name="amount"]').addEventListener('input', function() {
            const val = this.value;
            let matched = false;
            document.querySelectorAll('#depositFormPage .amount-btn').forEach(b => {
                if (b.dataset.value === val) {
                    b.classList.add('active');
                    matched = true;
                } else {
                    b.classList.remove('active');
                }
            });
            if (!matched && val === '') {
                document.querySelectorAll('#depositFormPage .amount-btn').forEach(b => b.classList.remove('active'));
            }
        });
        })();
    </script>
@endpush
