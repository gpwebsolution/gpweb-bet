<nav class="page__content__navbar" id="mainNavbar">
    <aside class="page__content__navbar__esq">
        <button class="navbar-toggle-btn" type="button" aria-label="Toggle menu">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <a class="page__navbar__logo" href="{{ url('/') }}">
            <img src="{{ method_exists($setting, 'logoUrl') ? $setting->logoUrl() : asset('assets/images/logo.svg') }}" alt="MarioBET" style="height: 42px;">
        </a>

    </aside>

    <aside class="page__content__navbar__dir">
        @if(auth()->check())
            <a class="balance-display" href="{{ route('panel.wallet.index') }}">
                <span class="balance-label">Saldo</span>
                <div class="balance-value">
                    <i class="fas fa-plus-circle"></i>
                    <span>{{ \Helper::getBalance() }}</span>
                </div>
            </a>

            @if(auth()->user()->notifications()->count() > 0)
                <a class="notification-bell" href="{{ route('panel.notifications.index') }}">
                    <i class="fas fa-bell"></i>
                </a>
            @endif

            <a href="{{ route('panel.wallet.deposit_form') }}"
               class="deposit-btn text-decoration-none">
                <i class="fa-regular fa-plus me-1"></i>
                <span class="hidden-mobile">Depositar</span>
            </a>

            <div class="dropdown user-dropdown">
                <button class="user-avatar" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-regular fa-user"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @if(in_array(auth()->user()->role_id, [0,1]))
                        <li><a class="dropdown-item" href="{{ url('/admin/login') }}">
                            <i class="fa-light fa-screwdriver-wrench"></i> Admin
                        </a></li>
                    @endif
                    <li><a class="dropdown-item" href="{{ route('panel.profile.index') }}">
                        <i class="fa-solid fa-user"></i> Meu Perfil
                    </a></li>
                    <li><a class="dropdown-item" href="{{ url('painel/vip') }}">
                        <i class="fa-regular fa-crown"></i> Programa VIP
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('panel.wallet.index') }}">
                        <i class="fa-regular fa-wallet"></i> Carteira
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('panel.wallet.deposits') }}">
                        <i class="fa-solid fa-money-bill-transfer"></i> Depósitos
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('panel.wallet.saques') }}">
                        <i class="fa-regular fa-money-simple-from-bracket"></i> Saques
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('panel.affiliates.index') }}">
                        <i class="fa-regular fa-users-viewfinder"></i> Afiliado
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('panel.notifications.index') }}">
                        <i class="fas fa-bell"></i> Notificações
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-right-from-bracket"></i> Sair
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </li>
                </ul>
            </div>
        @else
            <a class="deposit-btn text-decoration-none" href=""
               data-izimodal-open="#register-modal" data-izimodal-zindex="20000" data-izimodal-preventclose="">
                <i class="fa-duotone fa-arrow-right-to-bracket me-2"></i> Registrar
            </a>
            <a class="login-btn text-decoration-none" href=""
               data-izimodal-open="#login-modal" data-izimodal-zindex="20000" data-izimodal-preventclose="">
                Entrar
            </a>
        @endif
    </aside>
</nav>

@include('includes.deposit')

{{-- Login Modal --}}
<div id="login-modal" class="iziModal" data-izimodal-loop="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="position-relative">
                    <div id="loading" class="loading-spinner"><span class="spinner"></span></div>
                    <form id="loginForm" method="post" action="" class="auth-form">
                        @csrf
                        <div class="text-center mb-4">
                            <img src="{{ method_exists($setting, 'logoUrl') ? $setting->logoUrl() : asset('assets/images/logo.svg') }}" alt="MarioBET" style="height: 36px;">
                            <h5 class="fw-bold mt-3 mb-1">Entrar</h5>
                            <p class="text-white-50 small mb-0">Acesse sua conta para continuar</p>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Senha" required>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <a href="{{ route('forgotPassword') }}" class="small text-accent">Esqueci minha senha</a>
                        </div>
                        <button type="submit" class="btn-primary-theme w-100 mb-3">Entrar</button>
                        <p class="text-center small mb-4">Novo por aqui?
                            <a href="" class="fw-bold text-accent" onclick="openRegister(event)">Criar conta</a>
                        </p>
                        <div class="divider-text mb-3">
                            <span class="divider-line"></span>
                            <span class="divider-label">Ou entre com</span>
                            <span class="divider-line"></span>
                        </div>
                        <a href="{{ url('/auth/redirect/google') }}" class="google-btn w-100">Logar com Google</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Register Modal --}}
<div id="register-modal" class="iziModal" data-izimodal-loop="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="position-relative">
                    <div id="loading_register" class="loading-spinner"><span class="spinner"></span></div>
                    <form id="registrationForm" action="" method="post" class="auth-form">
                        @csrf
                        <div class="text-center mb-4">
                            <img src="{{ method_exists($setting, 'logoUrl') ? $setting->logoUrl() : asset('assets/images/logo.svg') }}" alt="MarioBET" style="height: 36px;">
                            <h5 class="fw-bold mt-3 mb-1">Criar Conta</h5>
                            <p class="text-white-50 small mb-0">Preencha os dados para se cadastrar</p>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                            <input type="text" name="name" class="form-control" placeholder="Nome de usuário" required>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                            <input id="regPassword" type="password" name="password" class="form-control" placeholder="Senha" required>
                            <button type="button" class="input-group-text password-toggle" onclick="toggleRegPassword()">
                                <i id="regEye" class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                            <input id="regPasswordConfirm" type="password" name="password_confirmation" class="form-control" placeholder="Confirme a senha" required>
                            <button type="button" class="input-group-text password-toggle" onclick="toggleRegPassword()">
                                <i id="regEye2" class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-brands fa-whatsapp"></i></span>
                            <input type="text" name="phone" class="form-control sp_celphones" placeholder="WhatsApp" required>
                        </div>
                        @if(app('request')->input('affiliate'))
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fa-light fa-user-group-simple"></i></span>
                                <input type="text" name="affiliate_token" readonly class="form-control" value="{{ app('request')->input('affiliate') }}">
                            </div>
                        @endif
                        <button type="submit" class="btn-primary-theme w-100 mb-3">Criar Conta</button>
                        <p class="text-center small">Ao criar conta, você aceita nossos <a href="" class="text-accent">termos</a> e <a href="" class="text-accent">política de privacidade</a>.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('/assets/js/jquery.mask.min.js') }}"></script>
    <script>
        // === SIDEBAR TOGGLE - all screen sizes ===
        const toggleBtn = document.querySelector('.navbar-toggle-btn');
        const sidebar = document.getElementById('navbarContent');
        const overlay = document.querySelector('.sidebar-overlay');

        function openSidebar() {
            sidebar?.classList.add('open');
            toggleBtn?.classList.add('active');
            overlay?.classList.add('active');
            if (window.innerWidth <= 960) document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar?.classList.remove('open');
            toggleBtn?.classList.remove('active');
            overlay?.classList.remove('active');
            document.body.style.overflow = '';
        }

        toggleBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar?.classList.contains('open') ? closeSidebar() : openSidebar();
        });

        overlay?.addEventListener('click', closeSidebar);
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeSidebar(); });

        // === IZIMODALS ===
        $("#login-modal").iziModal({
            title: '',
            subtitle: '',
            headerColor: '#202327', theme: 'dark', background: '#202327',
            width: 440, closeOnEscape: true, overlayClose: true,
            padding: 0
        });
        $("#register-modal").iziModal({
            title: '',
            subtitle: '',
            headerColor: '#202327', theme: 'dark', background: '#202327',
            width: 440, closeOnEscape: true, overlayClose: true,
            padding: 0,
            onOpening: function() {
                var mask = function(val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                };
                $('.sp_celphones').mask(mask, { onKeyPress: function(val, e, f, o) {
                    f.mask(mask.apply(this, arguments), o);
                }});
            },
            onClosed: function() { document.querySelectorAll('#register-modal input').forEach(i => i.value = ''); }
        });

        // === LOGIN FORM ===
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            document.getElementById('loading').style.display = 'block';
            fetch('{{ route('login') }}', {
                method: 'POST', body: new FormData(this)
            }).then(r => r.json()).then(data => {
                if(data.status) {
                    iziToast.success({ title: 'Sucesso', message: 'Login realizado!',
                        backgroundColor: '#23ab0e', position: 'topRight', timeout: 500,
                        onClosed: function() {
                            $("#login-modal").iziModal('close');
                            window.location.replace('{{ url('/') }}');
                        }
                    });
                } else {
                    iziToast.error({ title: 'Atenção', message: data.error || Object.values(data)[0][0],
                        backgroundColor: '#b51408', position: 'topRight' });
                }
                document.getElementById('loading').style.display = 'none';
            }).catch(() => document.getElementById('loading').style.display = 'none');
        });

        // === REGISTER FORM ===
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            document.getElementById('loading_register').style.display = 'block';
            fetch('{{ route('register') }}', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: new FormData(this)
            }).then(r => r.json()).then(data => {
                if(data.status) {
                    iziToast.success({ title: 'Sucesso', message: 'Conta criada!',
                        backgroundColor: '#23ab0e', position: 'topRight', timeout: 500,
                        onClosed: function() {
                            $("#register-modal").iziModal('close');
                            window.location.replace(data.redirect || '{{ url('/') }}');
                        }
                    });
                } else {
                    iziToast.error({ title: 'Atenção', message: data.error || Object.values(data)[0][0],
                        backgroundColor: '#b51408', position: 'topRight' });
                }
                document.getElementById('loading_register').style.display = 'none';
            }).catch(() => document.getElementById('loading_register').style.display = 'none');
        });

        function toggleRegPassword() {
            const pw = document.getElementById('regPassword');
            const pw2 = document.getElementById('regPasswordConfirm');
            const icon = document.getElementById('regEye');
            const icon2 = document.getElementById('regEye2');
            const t = pw.type === 'password' ? 'text' : 'password';
            pw.type = t; pw2.type = t;
            icon.className = t === 'password' ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';
            icon2.className = t === 'password' ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';
        }

        function openRegister(e) {
            e.preventDefault();
            $("#login-modal").iziModal('close');
            setTimeout(() => $("#register-modal").iziModal('open'), 300);
        }

        @if(app('request')->input('action') == 'login') $("#login-modal").iziModal('open'); @endif
        @if(app('request')->input('action') == 'register') $("#register-modal").iziModal('open'); @endif
    </script>
@endpush