@extends('layouts.web')
@section('title', 'Redefinir Senha - ' . (config('setting')['software_name'] ?? 'MarioBET'))

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
                                <h4 class="fw-bold text-white">Redefinir Senha</h4>
                                <p class="text-white-50">Escolha uma nova senha</p>
                            </div>

                            <form method="POST" action="{{ route('resetPassword', $token) }}">
                                @csrf
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        @foreach ($errors->all() as $error)
                                            <p class="mb-0 small">{{ $error }}</p>
                                        @endforeach
                                    </div>
                                @endif

                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="mb-3">
                                    <label class="form-label text-white-50 small">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                                        <input type="email" name="email" class="form-control" placeholder="seu@email.com" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-white-50 small">Nova Senha</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                                        <input type="password" name="password" class="form-control" placeholder="Nova senha" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-white-50 small">Confirmar Senha</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirme a senha" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary-theme w-100 text-white fw-bold">
                                    Redefinir Senha
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection