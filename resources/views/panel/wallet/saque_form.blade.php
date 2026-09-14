@extends('layouts.web')

@section('content')
    <div class="container-fluid">
        @include('includes.navbar_top')
        @include('includes.navbar_left')

        <div class="page__content">
            <br>
            <div class="@if(\Helper::getCustomLayout()['expanded_layout']) container-fluid @else container @endif">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="withdraw-card position-relative">
                            <div id="loadingSaquePage" class="loading-overlay">
                                <span class="spinner"></span>
                            </div>

                            <div class="text-center mb-4">
                                <div class="withdraw-icon-wrapper">
                                    <i class="fa-regular fa-money-from-bracket"></i>
                                </div>
                                <h4 class="text-white mt-3 fw-bold">REALIZAR SAQUE</h4>
                            </div>

                            <div class="text-center mb-4 p-3" style="background:linear-gradient(135deg,rgba(34,193,195,0.1),rgba(253,187,45,0.1));border-radius:12px;border:1px solid rgba(255,255,255,0.05);">
                                <small class="text-muted">Saldo disponível</small>
                                <h2 class="text-white fw-bold mb-0">{{ \Helper::amountFormatDecimal(auth()->user()->wallet->balance) }}</h2>
                            </div>

                            <form id="saqueFormPage" method="post" action="{{ route('panel.wallet.saque') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small text-muted">VALOR DO SAQUE</label>
                                    <div class="amount-quick-select mb-3">
                                        @php
                                            $balance = auth()->user()->wallet->balance;
                                            $min = max(1, (int) (config('setting')['min_saque'] ?? 10));
                                            $options = [$min, $min * 2, $min * 5, $min * 10, $min * 20, $min * 50];
                                        @endphp
                                        @foreach($options as $val)
                                            @if($val <= $balance)
                                                <button type="button" class="amount-btn" data-value="{{ $val }}">R$ {{ number_format($val, 0, ',', '.') }}</button>
                                            @endif
                                        @endforeach
                                        <button type="button" class="amount-btn" data-value="{{ $balance }}">TUDO</button>
                                    </div>
                                    <div class="input-group input-group-custom">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" name="amount" required min="{{ config('setting')['min_saque'] ?? 0 }}" max="{{ config('setting')['max_saque'] ?? 99999 }}" class="form-control amount-input" placeholder="0,00" step="0.01">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small text-muted">CHAVE PIX</label>
                                    <div class="row g-2">
                                        <div class="col-lg-8">
                                            <input type="text" name="chave_pix" class="form-control" required placeholder="SUA CHAVE PIX">
                                        </div>
                                        <div class="col-lg-4">
                                            <select name="tipo_chave" class="form-select" required>
                                                <option value="" disabled selected>Tipo</option>
                                                <option value="document">CPF/CNPJ</option>
                                                <option value="email">E-mail</option>
                                                <option value="phoneNumber">Telefone</option>
                                                <option value="randomKey">Aleatória</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small text-muted">CPF</label>
                                    @if(auth()->user()->cpf_confirmed)
                                        <input type="text" class="form-control cpf" value="{{ auth()->user()->cpf }}" readonly disabled>
                                        <input type="hidden" name="document" value="{{ auth()->user()->cpf }}">
                                        <small class="text-muted mt-1 d-block">CPF vinculado à sua conta</small>
                                    @else
                                        <input type="text" name="document" class="form-control cpf" required placeholder="SEU CPF">
                                    @endif
                                </div>

                                <div class="alert alert-warning d-flex align-items-center gap-2 py-2">
                                    <i class="fa-regular fa-circle-info"></i>
                                    <small class="mb-0">Selecione o tipo correto de chave Pix para evitar problemas.</small>
                                </div>

                                <div class="form-check mb-3">
                                    <input name="accept_terms" required class="form-check-input" type="checkbox" value="1" id="acceptTerms">
                                    <label class="form-check-label small" for="acceptTerms">
                                        Aceito os <a href="#" class="text-accent">termos de transferência</a>
                                    </label>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn-primary-theme btn-block w-full pulse-btn">
                                        <i class="fa-regular fa-check me-2"></i>SOLICITAR SAQUE
                                    </button>
                                </div>
                            </form>

                            <div class="mt-4 text-center">
                                <a href="{{ route('panel.wallet.saques') }}" class="text-muted small">
                                    <i class="fa-regular fa-clock-rotate-left me-1"></i>Ver histórico de saques
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('/assets/js/jquery.mask.min.js') }}"></script>
    <script>
        $('.cpf').mask('000.000.000-00', {reverse: true});

        document.getElementById('saqueFormPage').addEventListener('submit', function(event) {
            event.preventDefault();
            const loadingElement = document.getElementById('loadingSaquePage');
            loadingElement.style.display = 'flex';
            const formData = new FormData(this);
            fetch('{{ route('panel.wallet.saque') }}', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if(data.status) {
                        iziToast.show({ title: 'Sucesso!', message: 'Saque solicitado com sucesso', theme: 'dark',
                            icon: 'fa-solid fa-check', iconColor: '#ffffff', backgroundColor: '#23ab0e',
                            position: 'topRight', timeout: 2000,
                            onClosed: function() { window.location.replace('{{ route('panel.wallet.saques') }}'); }
                        });
                    } else {
                        var msg = data.error || 'Erro ao solicitar saque';
                        iziToast.show({ title: 'Atenção', message: msg, theme: 'dark',
                            icon: 'fa-regular fa-circle-exclamation', iconColor: '#ffffff',
                            backgroundColor: '#b51408', position: 'topRight' });
                    }
                    loadingElement.style.display = 'none';
                }).catch(() => { loadingElement.style.display = 'none'; });
        });

        document.querySelectorAll('#saqueFormPage .amount-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('#saqueFormPage .amount-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                document.querySelector('#saqueFormPage input[name="amount"]').value = this.dataset.value;
            });
        });
        document.querySelector('#saqueFormPage input[name="amount"]').addEventListener('input', function() {
            document.querySelectorAll('#saqueFormPage .amount-btn').forEach(b => b.classList.remove('active'));
        });
        document.querySelector('input[name="chave_pix"]').addEventListener('input', function() {
            var tipo = document.querySelector('select[name="tipo_chave"]');
            var val = this.value.trim();
            if (val.includes('@')) tipo.value = 'email';
            else if (/^\d{11}$/.test(val) || /^\d{14}$/.test(val)) tipo.value = 'document';
            else if (/^\d+$/.test(val)) tipo.value = 'phoneNumber';
            else tipo.value = 'randomKey';
        });
    </script>
@endpush
