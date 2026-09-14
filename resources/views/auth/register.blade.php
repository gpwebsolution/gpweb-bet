@extends('layouts.web')
@section('title', 'Cadastrar - ' . (config('setting')['software_name'] ?? 'MarioBET'))

@section('seo')
    <link rel="canonical" href="{{ url()->current() }}" />
    <meta name="description" content="Crie sua conta no {{ config('setting')['software_name'] }} e comece a jogar.">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('content')
<div class="container-page">
    @include('includes.navbar_top')
    @include('includes.navbar_left')

    <div class="page__content">
        <div class="container-fluid">
            <div class="row justify-content-center" style="min-height: 80vh; align-content: center;">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border-0 shadow-lg" style="background: #1a1c22; border-radius: 16px;">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <div class="mb-3">
                                    <i class="fa-duotone fa-user-plus" style="font-size: 3rem; color: var(--cor-principal);"></i>
                                </div>
                                <h4 class="fw-bold text-white">Criar Conta</h4>
                                <p class="text-white-50">Comece a jogar em segundos</p>
                            </div>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label text-white-50 small">Nome</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Seu nome" required>
                                    </div>
                                    @error('name') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-white-50 small">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="seu@email.com" required>
                                    </div>
                                    @error('email') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-white-50 small">Senha</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                                        <input type="password" name="password" class="form-control" placeholder="Crie uma senha" required>
                                    </div>
                                    @error('password') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-white-50 small">Confirmar Senha</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repita a senha" required>
                                    </div>
                                </div>

                                @if(request('ref'))
                                <input type="hidden" name="ref" value="{{ request('ref') }}">
                                @endif

                                <button type="submit" class="btn btn-primary-theme w-100 mb-3 text-white fw-bold">
                                    Criar Conta
                                </button>

                                <p class="text-center text-white-50 small">
                                    Ao criar conta, você aceita nossos <a href="" class="text-decoration-none" style="color: var(--cor-principal);">termos de uso</a>.
                                </p>
                            </form>

                            <p class="text-center text-white-50 small mt-3 mb-0">
                                Já tem conta? <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: var(--cor-principal);">Entre</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
