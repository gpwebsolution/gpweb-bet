@extends('layouts.web')
@section('title', 'Recuperar Senha - ' . (config('setting')['software_name'] ?? 'MarioBET'))

@section('seo')
    <link rel="canonical" href="{{ url()->current() }}" />
    <meta name="description" content="Recupere sua senha no {{ config('setting')['software_name'] }}.">
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
                                    <i class="fa-duotone fa-key" style="font-size: 3rem; color: var(--cor-principal);"></i>
                                </div>
                                <h4 class="fw-bold text-white">Recuperar Senha</h4>
                                <p class="text-white-50">Receba um link no seu email</p>
                            </div>

                            <form method="POST" action="{{ route('sendResetLink') }}">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label text-white-50 small">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                                        <input type="email" name="email" class="form-control" placeholder="seu@email.com" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary-theme w-100 text-white fw-bold">
                                    Enviar Link
                                </button>
                            </form>

                            <p class="text-center text-white-50 small mt-4 mb-0">
                                <a href="{{ route('login') }}" class="text-decoration-none" style="color: var(--cor-principal);">
                                    <i class="fa-regular fa-arrow-left me-1"></i> Voltar ao login
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection
