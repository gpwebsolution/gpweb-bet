@extends('layouts.web')

@section('title', 'Como Funciona? - ' . (config('setting')['software_name'] ?? 'MarioBET'))

@section('seo')
    <link rel="canonical" href="{{ url()->current() }}" />
    <meta name="description" content="Saiba como funciona o {{ config('setting')['software_name'] }. Guia completo sobre cadastro, depósitos, saques e apostas.">
    <meta name="keywords" content="como funciona, tutorial, guia, cassino online, apostas, {{ config('setting')['software_name'] }}">
    <meta property="og:locale" content="pt_BR" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Como Funciona? - {{ config('setting')['software_name'] }}" />
    <meta property="og:description" content="Saiba como funciona o {{ config('setting')['software_name'] }." />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ config('setting')['software_name'] }}" />
    <meta property="og:image" content="{{ asset('/assets/images/banner-1.png') }}" />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Como Funciona? - {{ config('setting')['software_name'] }}">
    <meta name="twitter:description" content="Saiba como funciona o {{ config('setting')['software_name'] }.">
@endsection

@section('content')
    @include('includes.navbar_top')
    @include('includes.navbar_left')

    <div class="page__content">
        <br>
        <div class="container">
                <div class="content-page">
                    <div class="text-center mb-5">
                        <div style="width: 72px; height: 72px; background: linear-gradient(135deg, var(--cor-principal), color-mix(in srgb, var(--cor-principal) 60%, #000)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <i class="fa-regular fa-circle-info" style="color: white; font-size: 1.75rem;"></i>
                        </div>
                        <h2>Como Funciona?</h2>
                        <p style="max-width: 600px; margin: 0 auto; color: rgba(255,255,255,0.5);">
                            Guia completo para você começar a jogar no <strong>{{ config('setting')['software_name'] ?? 'MarioBET' }}</strong>
                        </p>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <div class="card h-100 text-center p-4">
                                <div class="card-body">
                                    <div class="step-number" style="width: 44px; height: 44px; border-radius: 50%; background: var(--cor-principal); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem; margin: 0 auto 16px;">1</div>
                                    <i class="fa-regular fa-user-plus" style="font-size: 2rem; color: var(--cor-principal); margin-bottom: 12px;"></i>
                                    <h5 class="fw-bold text-white">Crie sua Conta</h5>
                                    <p style="color: rgba(255,255,255,0.5); font-size: 0.875rem;">Cadastre-se gratuitamente em segundos. Informe seus dados básicos e crie uma senha segura.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 text-center p-4">
                                <div class="card-body">
                                    <div class="step-number" style="width: 44px; height: 44px; border-radius: 50%; background: var(--cor-principal); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem; margin: 0 auto 16px;">2</div>
                                    <i class="fa-regular fa-circle-down" style="font-size: 2rem; color: var(--cor-principal); margin-bottom: 12px;"></i>
                                    <h5 class="fw-bold text-white">Faça um Depósito</h5>
                                    <p style="color: rgba(255,255,255,0.5); font-size: 0.875rem;">Deposite via PIX de forma rápida e segura. O valor cai na hora em sua carteira.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 text-center p-4">
                                <div class="card-body">
                                    <div class="step-number" style="width: 44px; height: 44px; border-radius: 50%; background: var(--cor-principal); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem; margin: 0 auto 16px;">3</div>
                                    <i class="fa-regular fa-gamepad-modern" style="font-size: 2rem; color: var(--cor-principal); margin-bottom: 12px;"></i>
                                    <h5 class="fw-bold text-white">Jogue e Ganhe</h5>
                                    <p style="color: rgba(255,255,255,0.5); font-size: 0.875rem;">Escolha entre diversos jogos emocionantes e comece a jogar. Boa sorte!</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="mt-5"><i class="fa-regular fa-circle-question me-2 text-accent"></i>Perguntas Frequentes</h4>

                    <div class="accordion mt-3" id="howWorksFaq">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hwFaq1">
                                    Como posso me cadastrar?
                                </button>
                            </h2>
                            <div id="hwFaq1" class="accordion-collapse collapse" data-bs-parent="#howWorksFaq">
                                <div class="accordion-body">
                                    Para se registrar, clique em "Registrar" no canto superior direito. Preencha seu nome, e-mail, senha e telefone. Após o cadastro, você já pode fazer login e começar a jogar.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hwFaq2">
                                    Como faço uma aposta?
                                </button>
                            </h2>
                            <div id="hwFaq2" class="accordion-collapse collapse" data-bs-parent="#howWorksFaq">
                                <div class="accordion-body">
                                    Faça login, deposite funds em sua carteira e escolha um jogo na página inicial ou na seção "Jogos". Clique no jogo desejado e comece a jogar.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hwFaq3">
                                    Como funciona o saque?
                                </button>
                            </h2>
                            <div id="hwFaq3" class="accordion-collapse collapse" data-bs-parent="#howWorksFaq">
                                <div class="accordion-body">
                                    Acesse sua carteira, clique em "Sacar", informe o valor e sua chave PIX. O saque será processado em até 24 horas úteis.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hwFaq4">
                                    Existe valor mínimo para depósito?
                                </button>
                            </h2>
                            <div id="hwFaq4" class="accordion-collapse collapse" data-bs-parent="#howWorksFaq">
                                <div class="accordion-body">
                                    Sim, o valor mínimo para depósito é de <strong>R$ {{ config('setting')['min_deposit'] ?? 10 }}</strong>. Não há limite máximo.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hwFaq5">
                                    É seguro jogar no {{ config('setting')['software_name'] ?? 'MarioBET' }}?
                                </button>
                            </h2>
                            <div id="hwFaq5" class="accordion-collapse collapse" data-bs-parent="#howWorksFaq">
                                <div class="accordion-body">
                                    Sim! Utilizamos tecnologia de criptografia de ponta para proteger seus dados e transações. Trabalhamos com jogos certificados e auditados para garantir公平 (justiça) e transparência.
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
