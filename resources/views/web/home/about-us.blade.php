@extends('layouts.web')

@section('title', 'Sobre Nós - ' . (config('setting')['software_name'] ?? 'MarioBET'))

@section('seo')
    <link rel="canonical" href="{{ url()->current() }}" />
    <meta name="description" content="Conheça o {{ config('setting')['software_name'] }} - sua plataforma de jogos online. Saiba mais sobre nossa história, missão e valores.">
    <meta name="keywords" content="{{ config('setting')['software_name'] }}, sobre nós, cassino online, jogos de slot">
    <meta property="og:locale" content="pt_BR" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Sobre Nós - {{ config('setting')['software_name'] }}" />
    <meta property="og:description" content="Conheça o {{ config('setting')['software_name'] }} - sua plataforma de jogos online." />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ config('setting')['software_name'] }}" />
    <meta property="og:image" content="{{ asset('/assets/images/banner-1.png') }}" />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Sobre Nós - {{ config('setting')['software_name'] }}">
    <meta name="twitter:description" content="Conheça o {{ config('setting')['software_name'] }} - sua plataforma de jogos online.">
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
                            <i class="fa-regular fa-building" style="color: white; font-size: 1.75rem;"></i>
                        </div>
                        <h2>Sobre Nós</h2>
                        <p style="max-width: 600px; margin: 0 auto; color: rgba(255,255,255,0.5);">
                            Conheça nossa história e o que nos move
                        </p>
                    </div>

                    <div class="row g-4 align-items-center mb-5">
                        <div class="col-lg-5">
                            <div class="about-us-img">
                                <img src="{{ asset('/assets/images/sobre-nos.png') }}" alt="Sobre Nós" class="img-fluid" style="border-radius: 16px; max-height: 350px;">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <p>
                                Bem-vindo à <strong>{{ config('setting')['software_name'] ?? 'MarioBET' }}</strong>: sua porta de entrada para um mundo emocionante de entretenimento e apostas online!
                                Somos uma renomada empresa de cassino online, oferecendo uma vasta gama de jogos divertidos,
                                desde caça-níqueis fascinantes até apostas eletrizantes em jogos de futebol.
                            </p>
                            <p>
                                Nossa missão é proporcionar aos nossos jogadores uma experiência de jogo imersiva, simples e repleta de oportunidades para grandes vitórias.
                                Nosso cassino online apresenta um ambiente virtual seguro e confiável, onde os jogadores podem se deliciar com uma variedade de jogos de slot temáticos,
                                repletos de cores vibrantes e gráficos de alta qualidade.
                            </p>
                        </div>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <div class="card h-100 text-center p-4">
                                <div class="card-body">
                                    <i class="fa-regular fa-shield-halved" style="font-size: 2.5rem; color: var(--cor-principal); margin-bottom: 16px;"></i>
                                    <h5 class="fw-bold text-white">Segurança</h5>
                                    <p style="color: rgba(255,255,255,0.5); font-size: 0.875rem;">Protegemos seus dados com criptografia de ponta e processos rigorosos de segurança.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 text-center p-4">
                                <div class="card-body">
                                    <i class="fa-regular fa-face-smile" style="font-size: 2.5rem; color: var(--cor-principal); margin-bottom: 16px;"></i>
                                    <h5 class="fw-bold text-white">Diversão</h5>
                                    <p style="color: rgba(255,255,255,0.5); font-size: 0.875rem;">Oferecemos uma experiência de jogo emocionante com os melhores títulos do mercado.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 text-center p-4">
                                <div class="card-body">
                                    <i class="fa-regular fa-handshake" style="font-size: 2.5rem; color: var(--cor-principal); margin-bottom: 16px;"></i>
                                    <h5 class="fw-bold text-white">Transparência</h5>
                                    <p style="color: rgba(255,255,255,0.5); font-size: 0.875rem;">Jogos justos e auditados, com política clara de privacidade e termos de uso.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p>
                        Na <strong>{{ config('setting')['software_name'] ?? 'MarioBET' }}</strong>, acreditamos que a diversão é a chave para uma experiência de cassino online memorável.
                        Venha se juntar a nós e descubra o emocionante mundo dos jogos de azar e apostas esportivas.
                        Sua sorte está à espera em cada giro e em cada aposta - aproveite ao máximo a sua jornada conosco!
                    </p>
            </div>
        </div>
    </div>
@endsection
