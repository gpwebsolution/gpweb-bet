<div id="deposit-modal" class="iziModal" data-izimodal-loop="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div id="loadingDeposit" class="loading-spinner">
                <span class="spinner"></span>
            </div>

            <button type="button" class="modal-close-btn" onclick="closeDepositModal()" aria-label="Fechar">
                <i class="fa-regular fa-xmark"></i>
            </button>

            <div id="qrcode-container">
                <div class="text-center mb-3">
                    <div class="check-icon-wrapper">
                        <i class="fa-regular fa-circle-check"></i>
                    </div>
                    <h5 class="text-white mt-3">ESCANEIE O QRCODE PARA PAGAR</h5>
                </div>
                <div id="qrcode" style="width:260px; height:260px; margin:0 auto;"></div>
                <input id="pixcopiaecola" type="text" class="form-control mt-3 text-center" value="" readonly>
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
                    <button type="button" onclick="closeDepositModal()" class="btn btn-outline-secondary">
                        CANCELAR
                    </button>
                </div>
            </div>

            <form id="depositForm" method="post" action="" class="auth-form">
                @csrf
                <div class="animate__animated animate__fadeIn">
                    <div class="text-center mb-4">
                        <div class="pix-icon-wrapper">
                            <i class="fa-brands fa-pix"></i>
                        </div>
                        <h5 class="font-bold mt-3">DEPOSITAR VIA PIX</h5>
                        <p class="text-muted small">Escolha ou digite o valor desejado</p>
                    </div>
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
                    @auth
                        @if(auth()->user()->cpf_confirmed)
                            <input type="text" class="form-control cpf" value="{{ auth()->user()->cpf }}" readonly disabled>
                            <input type="hidden" name="cpf" value="{{ auth()->user()->cpf }}">
                        @else
                            <input type="text" name="cpf" class="form-control cpf" value="" required placeholder="SEU CPF">
                        @endif
                    @else
                        <input type="text" name="cpf" class="form-control cpf" value="" required placeholder="SEU CPF">
                    @endauth
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn-primary-theme btn-block w-full mb-2 pulse-btn">
                        <i class="fa-regular fa-qrcode me-2"></i>GERAR QRCODE
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('/assets/js/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('/assets/js/qrcode.min.js') }}"></script>
    <script>
        let intervalId;

        function closeDepositModal() {
            clearInterval(intervalId);
            $('#deposit-modal').iziModal('close');
            setTimeout(() => location.reload(), 300);
        }

        function onPaymentPaid() {
            clearInterval(intervalId);
            iziToast.show({
                title: 'Pagamento Confirmado!',
                message: 'Seu depósito foi processado com sucesso.',
                theme: 'dark',
                icon: 'fa-solid fa-circle-check',
                iconColor: '#ffffff',
                backgroundColor: '#23ab0e',
                position: 'topRight',
                timeout: 3000,
                onClosed: function() {
                    window.location.replace('{{ route('panel.wallet.deposits') }}');
                }
            });
        }

        function onPaymentExpired() {
            clearInterval(intervalId);
            iziToast.show({
                title: 'QR Code Expirado',
                message: 'O tempo para pagamento expirou. Gere um novo QR Code.',
                theme: 'dark',
                icon: 'fa-regular fa-circle-exclamation',
                iconColor: '#ffffff',
                backgroundColor: '#b51408',
                position: 'topRight',
                timeout: 5000,
                onClosed: function() {
                    location.reload();
                }
            });
        }

        function onPaymentError() {
            clearInterval(intervalId);
            iziToast.show({
                title: 'Erro',
                message: 'Erro ao verificar o pagamento.',
                theme: 'dark',
                icon: 'fa-regular fa-circle-exclamation',
                iconColor: '#ffffff',
                backgroundColor: '#b51408',
                position: 'topRight',
                timeout: 5000
            });
        }

        $(function() {
            $('.cpf').mask('000.000.000-00', {reverse: true});

            $("#deposit-modal").iziModal({
                title: 'Depósito',
                subtitle: 'Deposite via PIX de forma rápida',
                icon: 'fa-brands fa-pix',
                headerColor: '#1A1C1F',
                theme: 'dark',
                background: '#202327',
                width: 700,
                closeOnEscape: true,
                overlayClose: true,
                transitionIn: 'bounceInDown',
                transitionOut: 'bounceOutUp',
                transitionInOverlay: 'fadeIn',
                transitionOutOverlay: 'fadeOut',
                onOpening: function(){
                    $('.cpf').mask('000.000.000-00', {reverse: true});
                },
                onClosed: function(){
                    clearInterval(intervalId);
                }
            });
        });

        document.getElementById("copyQrcodePix").addEventListener("click", function() {
            var inputElement = document.getElementById("pixcopiaecola");
            inputElement.select();
            inputElement.setSelectionRange(0, 99999);
            document.execCommand("copy");

            iziToast.show({
                title: 'Sucesso',
                message: 'Chave Pix copiada com sucesso!',
                theme: 'dark',
                icon: 'fa-solid fa-check',
                iconColor: '#ffffff',
                backgroundColor: '#23ab0e',
                position: 'topRight',
                timeout: 1500
            });
        });

        function consultStatusTransaction(idTransaction) {
            fetch('{{ url(\Helper::getGatewaySelected().'/consult-status-transaction') }}', {
                method: 'POST',
                body: JSON.stringify({ idTransaction }),
                headers: new Headers({
                    'Content-Type': 'application/json; charset=UTF-8'
                })
            })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'PAID') {
                        onPaymentPaid();
                    } else if(data.status === 'EXPIRED') {
                        onPaymentExpired();
                    } else if(data.status === 'ERROR') {
                        onPaymentError();
                    }
                })
                .catch(() => {});
        }

        document.getElementById('depositForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const loadingElement = document.getElementById('loadingDeposit');
            loadingElement.style.display = 'flex';

            const formData = new FormData(this);
            var cpfRaw = (formData.get('cpf') || '').replace(/\D/g, '');
            formData.set('cpf', cpfRaw);

            fetch('{{ url(\Helper::getGatewaySelected().'/qrcode-pix') }}', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if(data.status) {
                        document.getElementById('qrcode-container').style.display = 'block';
                        document.getElementById('depositForm').style.display = 'none';

                        new QRCode(document.getElementById('qrcode'), {
                            width: 260,
                            height: 260,
                            colorDark: '#ffffff',
                            colorLight: '#1A1C1F',
                            correctLevel: QRCode.CorrectLevel.H
                        }).makeCode(data.qrcode);

                        document.getElementById("pixcopiaecola").value = data.qrcode;

                        intervalId = setInterval(function() {
                            consultStatusTransaction(data.idTransaction);
                        }, 4000);
                    } else {
                        var msg = data.error || 'Erro ao gerar QR Code';
                        iziToast.show({
                            title: 'Atenção',
                            message: msg,
                            theme: 'dark',
                            icon: 'fa-regular fa-circle-exclamation',
                            iconColor: '#ffffff',
                            backgroundColor: '#b51408',
                            position: 'topRight'
                        });
                    }
                    loadingElement.style.display = 'none';
                })
                .catch(() => {
                    loadingElement.style.display = 'none';
                });
        });

        document.querySelectorAll('#deposit-modal .amount-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('#deposit-modal .amount-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                document.querySelector('#deposit-modal input[name="amount"]').value = this.dataset.value;
            });
        });

        document.querySelector('#deposit-modal input[name="amount"]').addEventListener('input', function() {
            const val = this.value;
            let matched = false;
            document.querySelectorAll('#deposit-modal .amount-btn').forEach(b => {
                if (b.dataset.value === val) {
                    b.classList.add('active');
                    matched = true;
                } else {
                    b.classList.remove('active');
                }
            });
            if (!matched && val === '') {
                document.querySelectorAll('#deposit-modal .amount-btn').forEach(b => b.classList.remove('active'));
            }
        });
    </script>
@endpush
