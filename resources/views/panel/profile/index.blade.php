@extends('layouts.web')

@push('styles')
<style>
    .vip-bar { height: 6px; background: rgba(255,255,255,0.08); border-radius: 3px; overflow: hidden; }
    .vip-bar-fill { height: 100%; border-radius: 3px; transition: width 0.8s ease; }
    .vip-circle { width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 800; color: #fff; flex-shrink: 0; }
    .profile-card { background: #202327; border: 1px solid rgba(255,255,255,0.06); border-radius: 16px; overflow: hidden; }
    .profile-header { display: flex; align-items: center; gap: 14px; padding: 28px 32px; background: linear-gradient(135deg, rgba(230,57,70,0.08) 0%, rgba(230,57,70,0.02) 100%); border-bottom: 1px solid rgba(255,255,255,0.06); }
    .profile-header .avatar-icon { width: 54px; height: 54px; border-radius: 50%; background: linear-gradient(135deg, var(--cor-principal), #c1121f); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #fff; flex-shrink: 0; }
    .profile-header h4 { margin: 0; color: #fff; font-weight: 700; }
    .profile-header small { color: rgba(255,255,255,0.45); }
    .profile-body { padding: 28px 32px; }
    .section-title { color: var(--cor-principal); font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 18px; }
    .divider-custom { height: 1px; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent); margin: 28px 0; }
    .delete-section { background: rgba(220,38,38,0.04); border: 1px solid rgba(220,38,38,0.1); border-radius: 12px; padding: 16px 20px; }
    .delete-section h6 { color: #ef4444; font-weight: 700; font-size: 0.85rem; }
    .delete-section p { color: rgba(255,255,255,0.4); font-size: 0.8rem; margin: 2px 0 0; }

    @media (max-width: 576px) {
        .profile-header { padding: 20px 18px; }
        .profile-body { padding: 20px 18px; }
        .profile-header .avatar-icon { width: 44px; height: 44px; font-size: 1.1rem; }
    }
</style>
@endpush

@section('content')
    @include('includes.navbar_top')
    @include('includes.navbar_left')

    <div class="page__content">
        <div class="container">

            @if(session('success'))
                <div class="alert alert-success alert-custom d-flex align-items-center gap-2 mb-4">
                    <i class="fa-regular fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-custom d-flex align-items-center gap-2 mb-4">
                    <i class="fa-regular fa-circle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            {{-- VIP Info Card (display only) --}}
            @php $vipColor = $currentVip?->color ?? '#555'; @endphp
            <div class="p-3 mb-4" style="border-radius:16px;background:linear-gradient(135deg, {{ $vipColor }}ee 0%, {{ $vipColor }}88 100%);border:1px solid {{ $vipColor }}55;">
                <div class="row align-items-center g-2">
                    <div class="col-auto">
                        <div class="vip-circle" style="background: {{ $currentVip?->color ?? '#555' }};">
                            {!! $currentVip ? $currentVip->level : '<i class="fa-regular fa-star" style="font-size:1.2rem;"></i>' !!}
                        </div>
                    </div>
                    <div class="col">
                        <p class="mb-0" style="color: rgba(255,255,255,0.6); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.04em;">
                            <i class="fa-regular fa-crown me-1"></i>{{ $currentVip ? 'Nível ' . $currentVip->level : 'Sem nível VIP' }}
                        </p>
                        <h5 class="fw-bold mb-0" style="color: #fff;">{{ $currentVip?->name ?? 'Iniciante' }}</h5>
                    </div>
                    <div class="col-lg-5 mt-2 mt-lg-0">
                        @if($nextVip)
                            <div style="font-size: 0.7rem; color: rgba(255,255,255,0.6);">
                                <span><i class="fa-regular fa-arrow-right"></i> Próximo: {{ $nextVip->name }}</span>
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
                        @else
                            <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.4); font-size: 0.75rem;">Deposite ou aposte para subir de nível</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Profile Card --}}
            <div class="profile-card">
                <div class="profile-header">
                    <div class="avatar-icon"><i class="fa-regular fa-user"></i></div>
                    <div>
                        <h4>{{ auth()->user()->name ?? 'Usuário' }}</h4>
                        <small>{{ auth()->user()->email }}</small>
                    </div>
                </div>

                <div class="profile-body">
                    <form action="{{ route('panel.profile.store') }}" method="post" id="profileForm">
                        @csrf

                        <p class="section-title"><i class="fa-regular fa-address-card me-2"></i>DADOS PESSOAIS</p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label for="formName">Nome Completo</label>
                                    <div class="input-wrap">
                                        <span class="input-icon"><i class="fa-regular fa-user"></i></span>
                                        <input type="text" name="name" id="formName"
                                               value="{{ auth()->user()->name ?? old('name') }}"
                                               placeholder="Seu nome completo" required>
                                    </div>
                                    @error('name')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label for="formEmail">E-mail</label>
                                    <div class="input-wrap">
                                        <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
                                        <input type="email" readonly disabled
                                               value="{{ auth()->user()->email }}"
                                               style="color: rgba(255,255,255,0.35);">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label for="formPhone">Telefone</label>
                                    <div class="input-wrap">
                                        <span class="input-icon"><i class="fa-regular fa-phone"></i></span>
                                        <input type="text" name="phone" id="formPhone" class="sp_celphones"
                                               value="{{ auth()->user()->phone ?? old('phone') }}"
                                               placeholder="(11) 99999-9999" required>
                                    </div>
                                    @error('phone')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label for="formCpf">CPF</label>
                                    <div class="input-wrap">
                                        <span class="input-icon"><i class="fa-regular fa-address-card"></i></span>
                                        <input type="text" name="cpf" id="formCpf"
                                               value="{{ auth()->user()->cpf ?? '' }}"
                                               {{ auth()->user()->cpf ? 'readonly' : 'required' }}>
                                    </div>
                                    @error('cpf')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 gap-2">
                            <a href="{{ route('panel.wallet.index') }}" class="btn-gradient outline">
                                <i class="fa-regular fa-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn-gradient primary">
                                <i class="fa-regular fa-floppy-disk"></i> SALVAR ALTERAÇÕES
                            </button>
                        </div>
                    </form>

                    <div class="divider-custom"></div>

                    <div class="delete-section">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h6><i class="fa-regular fa-trash-can me-1"></i>Excluir Conta</h6>
                                <p>Todos os seus dados serão removidos permanentemente. Esta ação não pode ser desfeita.</p>
                            </div>
                            <form action="{{ route('panel.profile.destroy') }}" method="post"
                                  onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Todos os dados serão perdidos.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-gradient danger">
                                    <i class="fa-regular fa-trash-can"></i> EXCLUIR CONTA
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @push('scripts') --}}
{{-- @endpush --}}