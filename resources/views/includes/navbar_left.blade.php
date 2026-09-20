<nav id="navbarContent" class="page__navbar">
    <div class="sidebar-header">
        <a class="sidebar-logo" href="{{ url('/') }}">
            <img src="{{ method_exists($setting, 'logoUrl') ? $setting->logoUrl() : asset('assets/images/logo.svg') }}" alt="MarioBET" style="height: 32px;">
        </a>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="" class="btn btn-accent w-100">
                Ganhe R$ {{ number_format(config('setting')['initial_bonus'] ?? 50, 0, ',', '.') }} grátis <i class="fa-light fa-rocket-launch"></i>
            </a>
        </li>

        <li>
            <a href="{{ url('/') }}" class="{{ Request::is('/') ? 'active' : '' }}">
                <img src="{{ asset('/assets/images/svg/home2.svg') }}" alt="" width="20">
                <span>Visão geral</span>
            </a>
        </li>

        <li>
            <a href="{{ route('web.roleta.index') }}" class="{{ request()->routeIs('web.roleta.*') ? 'active' : '' }}">
                <i class="fa-regular fa-circle" style="width:20px;text-align:center;"></i>
                <span>Roleta Diária</span>
            </a>
        </li>

        <li>
            <a href="{{ url('painel/vip') }}" class="{{ request()->routeIs('panel.vip.*') ? 'active' : '' }}">
                <i class="fa-regular fa-crown" style="width:20px;text-align:center;"></i>
                <span>VIP</span>
            </a>
        </li>

        <li>
            <a href="{{ url('painel/affiliates') }}" class="{{ request()->routeIs('panel.affiliates.index') ? 'active' : '' }}">
                <img src="{{ asset('/assets/images/svg/affiliate.svg') }}" alt="" width="20">
                <span>Menu de Afiliado</span>
            </a>
        </li>

        <li>
            <a href="{{ url('/como-funciona') }}" class="{{ Request::is('/como-funciona') ? 'active' : '' }}">
                <img src="{{ asset('assets/images/svg/about.svg') }}" alt="" width="20">
                <span>Como funciona?</span>
            </a>
        </li>

        <li>
            <a href="{{ url('/suporte') }}" class="{{ Request::is('/suporte') ? 'active' : '' }}">
                <img src="{{ asset('assets/images/svg/suporte.svg') }}" alt="" width="20">
                <span>Suporte</span>
            </a>
        </li>

        <li>
            <a href="{{ url('/sobre-nos') }}" class="{{ Request::is('/sobre-nos') ? 'active' : '' }}">
                <img src="{{ asset('assets/images/svg/sobre.svg') }}" alt="" width="20">
                <span>Sobre Nós</span>
            </a>
        </li>

        @if(\App\Models\Provider::count() > 0)
        <li class="has-sub">
            <button class="sub-toggle" onclick="toggleSubmenu(this)">
                <span class="sub-toggle-left">
                    <i class="fa-regular fa-dice-d6"></i>
                    <span>CASSINO</span>
                </span>
                <i class="fas fa-chevron-down sub-arrow"></i>
            </button>
            <ul class="sub-list">
                @foreach(\App\Models\Provider::all() as $prov)
                <li>
                    <a href="{{ route('web.provider.index', ['slug' => $prov->slug]) }}">
                        @if($prov->image)
                            <img src="{{ asset('storage/'.$prov->image) }}" alt="" width="20" class="rounded">
                        @else
                            <i class="fa-regular fa-folder"></i>
                        @endif
                        <span>{{ $prov->name }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </li>
        @endif
    </ul>
</nav>

<script>
    function toggleSubmenu(btn) {
        btn.classList.toggle('expanded');
        const list = btn.nextElementSibling;
        list.classList.toggle('show');
    }
</script>