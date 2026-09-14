<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @yield('seo')
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ \Helper::getCustomLayout()['background_color'] }}">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/iziModal.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/iziToast.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Catamaran:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .page__navbar { position: fixed; left: 0; top: 0; width: 260px; height: 100vh; transform: translateX(-100%); }
        .banner-carousel .swiper { border-radius: 16px; overflow: hidden; opacity: 0; }
        .banner-carousel .swiper.swiper-initialized { opacity: 1; }
        :root {
            --bs-body-bg: #0D131C;
            --cor-principal: {{ \Helper::getCustomLayout()['primary_color'] }};
            --cor-secundaria: {{ \Helper::getCustomLayout()['secondary_color'] }};
            --cor-fundo: {{ \Helper::getCustomLayout()['background_color'] }};
            --cor-texto: {{ \Helper::getCustomLayout()['primary_text'] }};
            --cor-rodape: {{ \Helper::getCustomLayout()['footer_color'] }};
            --cor-borda: {{ \Helper::getCustomLayout()['primary_border_color'] }};
        }
        body {
            background-color: var(--cor-fundo);
            font-family: 'Inter', sans-serif;
            color: var(--cor-texto);
            overflow-x: hidden;
        }
        .card {
            --bs-card-bg: var(--cor-secundaria);
            --bs-card-border-color: var(--cor-borda);
            background-color: var(--cor-secundaria);
        }
        .footer {
            background-color: var(--cor-rodape);
            border-top: 1px solid var(--cor-borda);
        }
        .dropdown-menu {
            --bs-dropdown-bg: var(--cor-secundaria);
        }
    </style>

    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <style>
    .banner-carousel {
        width: 100%;
        max-width: 1320px;
        margin: 12px auto 0;
        padding: 0 calc(var(--bs-gutter-x, 1.5rem) * 0.5);
    }
    .banner-carousel .swiper {
        border-radius: 16px;
        overflow: hidden;
    }
    .banner-carousel .swiper-slide {
        width: 100%;
        overflow: hidden;
    }
    .banner-carousel .swiper-slide img {
        width: 100%;
        height: auto;
        display: block;
    }
    .banner-carousel .swiper-button-next,
    .banner-carousel .swiper-button-prev {
        color: #fff;
        background: rgba(0,0,0,0.4);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        transition: background 0.3s;
    }
    .banner-carousel .swiper-button-next:after,
    .banner-carousel .swiper-button-prev:after {
        font-size: 16px;
    }
    .banner-carousel .swiper-button-next:hover,
    .banner-carousel .swiper-button-prev:hover {
        background: rgba(0,0,0,0.7);
    }
    .banner-carousel .swiper-pagination-bullet {
        background: #fff;
        opacity: 0.5;
    }
    .banner-carousel .swiper-pagination-bullet-active {
        opacity: 1;
        background: var(--cor-principal, #e74c3c);
    }
    @media (max-width: 768px) {
        .banner-carousel .swiper-button-next,
        .banner-carousel .swiper-button-prev {
            display: none;
        }
    }
    .page__content > .container,
    .page__content > .container-fluid {
        max-width: 1320px !important;
        margin-left: auto;
        margin-right: auto;
        width: 100%;
    }
    </style>

    @stack('styles')

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-9Q3ENV2D1L"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-9Q3ENV2D1L');
    </script>
</head>
<body style="background-color: {{ \Helper::getCustomLayout()['background_color'] }};">
    <div class="sidebar-overlay"></div>
    <main class="page">
        @yield('content')
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/iziModal.min.js') }}"></script>
    <script src="{{ asset('assets/js/iziToast.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
    </script>
    <script>
    var bannerSwiper = new Swiper('.bannerSwiper', {
        loop: true,
        autoplay: { delay: 5000, disableOnInteraction: false },
        navigation: { nextEl: '.banner-next', prevEl: '.banner-prev' },
        pagination: { el: '.banner-pagination', clickable: true },
        on: {
            init: function () {
                this.el.classList.add('swiper-initialized');
            }
        }
    });
    </script>

    @stack('scripts')
    <x-flash></x-flash>
</body>
</html>
