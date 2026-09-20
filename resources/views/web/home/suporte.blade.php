@extends('layouts.web')

@section('title', 'Suporte - ' . (config('setting')['software_name'] ?? 'MarioBET'))

@section('seo')
    <link rel="canonical" href="{{ url()->current() }}" />
    <meta name="description" content="Entre em contato com o suporte {{ config('setting')['software_name'] }}. Estamos aqui para ajudar você com dúvidas, problemas ou sugestões.">
    <meta name="keywords" content="suporte, atendimento, ajuda, cassino online, {{ config('setting')['software_name'] }}">
    <meta property="og:locale" content="pt_BR" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Suporte - {{ config('setting')['software_name'] }}" />
    <meta property="og:description" content="Entre em contato com o suporte {{ config('setting')['software_name'] }}." />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ config('setting')['software_name'] }}" />
    <meta property="og:image" content="{{ asset('/assets/images/banner-1.png') }}" />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Suporte - {{ config('setting')['software_name'] }}">
    <meta name="twitter:description" content="Entre em contato com o suporte {{ config('setting')['software_name'] }}.">
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.navbar_top')
        @include('includes.navbar_left')

        <div class="page__content">
            <br>
            <div class="@if(\Helper::getCustomLayout()['expanded_layout']) container-fluid @else container @endif">
                <div class="content-page">
                    <div class="text-center mb-5">
                        <div style="width: 72px; height: 72px; background: linear-gradient(135deg, var(--cor-principal), color-mix(in srgb, var(--cor-principal) 60%, #000)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <i class="fa-regular fa-headset" style="color: white; font-size: 1.75rem;"></i>
                        </div>
                        <h2>Central de Suporte</h2>
                        <p style="max-width: 600px; margin: 0 auto; color: rgba(255,255,255,0.5);">
                            Estamos aqui para ajudar! Escolha o canal de atendimento que preferir.
                        </p>
                    </div>

                    <div class="row g-4 mb-5">
                        @if(!empty(config('setting')['whatsapp']))
                        <div class="col-md-4">
                            <div class="card h-100 text-center p-4" style="transition: transform 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform=''">
                                <div class="card-body">
                                    <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #25D366, #128C7E); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                        <i class="fa-brands fa-whatsapp" style="color: white; font-size: 1.5rem;"></i>
                                    </div>
                                    <h5 class="fw-bold text-white">WhatsApp</h5>
                                    <p style="color: rgba(255,255,255,0.5); font-size: 0.875rem;">Atendimento rápido e prático pelo WhatsApp</p>
                                    <a href="{{ config('setting')['whatsapp'] }}" target="_blank" class="btn btn-primary-theme w-100">
                                        <i class="fa-brands fa-whatsapp me-2"></i>Falar agora
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(!empty(config('setting')['instagram']))
                        <div class="col-md-4">
                            <div class="card h-100 text-center p-4" style="transition: transform 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform=''">
                                <div class="card-body">
                                    <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #833AB4, #FD1D1D); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                        <i class="fa-brands fa-instagram" style="color: white; font-size: 1.5rem;"></i>
                                    </div>
                                    <h5 class="fw-bold text-white">Instagram</h5>
                                    <p style="color: rgba(255,255,255,0.5); font-size: 0.875rem;">Acompanhe nossas novidades e promoções</p>
                                    <a href="{{ config('setting')['instagram'] }}" target="_blank" class="btn btn-primary-theme w-100">
                                        <i class="fa-brands fa-instagram me-2"></i>Seguir
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(!empty(config('setting')['telegram']))
                        <div class="col-md-4">
                            <div class="card h-100 text-center p-4" style="transition: transform 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform=''">
                                <div class="card-body">
                                    <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #0088cc, #005f8a); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                        <i class="fa-brands fa-telegram" style="color: white; font-size: 1.5rem;"></i>
                                    </div>
                                    <h5 class="fw-bold text-white">Telegram</h5>
                                    <p style="color: rgba(255,255,255,0.5); font-size: 0.875rem;">Entre no nosso canal do Telegram</p>
                                    <a href="{{ config('setting')['telegram'] }}" target="_blank" class="btn btn-primary-theme w-100">
                                        <i class="fa-brands fa-telegram me-2"></i>Entrar
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="card p-4 mb-4">
                        <h4 class="fw-bold text-white mb-3"><i class="fa-regular fa-circle-question me-2 text-accent"></i>Perguntas Frequentes</h4>
                        <div class="accordion" id="suporteFaq">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#supFaq1">
                                        Como faço um depósito?
                                    </button>
                                </h2>
                                <div id="supFaq1" class="accordion-collapse collapse" data-bs-parent="#suporteFaq">
                                    <div class="accordion-body">
                                        Acesse sua carteira, clique em "Depositar", escolha o valor desejado e gere o QR Code PIX. Após o pagamento, o saldo é creditado automaticamente em sua conta.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#supFaq2">
                                        Quanto tempo leva para o saque cair na conta?
                                    </button>
                                </h2>
                                <div id="supFaq2" class="accordion-collapse collapse" data-bs-parent="#suporteFaq">
                                    <div class="accordion-body">
                                        Os saques são processados em até 24 horas úteis após a solicitação. O PIX costuma cair em poucos minutos após a aprovação.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#supFaq3">
                                        Esqueci minha senha, o que fazer?
                                    </button>
                                </h2>
                                <div id="supFaq3" class="accordion-collapse collapse" data-bs-parent="#suporteFaq">
                                    <div class="accordion-body">
                                        Clique em "Esqueci minha senha" na página de login e informe seu e-mail. Você receberá um link para redefinir sua senha.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
