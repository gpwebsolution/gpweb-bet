@extends('layouts.web')
@section('title', 'Entrar - ' . (config('setting')['software_name'] ?? 'MarioBET'))

@section('seo')
    <link rel="canonical" href="{{ url()->current() }}" />
    <meta name="description" content="Faça login no {{ config('setting')['software_name'] }} e comece a jogar.">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('content')
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
                                    <i class="fa-duotone fa-right-to-bracket" style="font-size: 3rem; color: var(--cor-principal);"></i>
                                </div>
                                <h4 class="fw-bold text-white">Entrar</h4>
                                <p class="text-white-50">Acesse sua conta</p>
                            </div>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
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
                                        <input type="password" name="password" class="form-control" placeholder="Sua senha" required>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                        <label class="form-check-label text-white-50 small" for="remember">Lembrar-me</label>
                                    </div>
                                    <a href="{{ route('forgotPassword') }}" class="text-decoration-none small" style="color: var(--cor-principal);">Esqueceu a senha?</a>
                                </div>

                                <button type="submit" class="btn btn-primary-theme w-100 mb-3 text-white fw-bold">
                                    Entrar
                                </button>
                            </form>

                            <div class="text-center mt-4">
                                <div class="line-text mb-3">
                                    <div class="l"></div>
                                    <div class="t text-white-50 small">Ou entre com</div>
                                    <div class="l"></div>
                                </div>
                                <a href="{{ route('auth.redirect', 'google') }}" class="btn btn-outline-light w-100">
                                    <i class="fa-brands fa-google me-2"></i> Google
                                </a>
                            </div>

                            <p class="text-center text-white-50 small mt-4 mb-0">
                                Não tem conta? <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: var(--cor-principal);">Cadastre-se</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection
