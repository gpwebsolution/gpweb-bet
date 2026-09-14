@extends('layouts.web')

@section('title', (config('setting')['software_name'] ?? 'MarioBET') . ' - Cassino Online | Jogos de Slot')

@section('seo')
    <link rel="canonical" href="{{ url()->current() }}" />
    <meta name="description" content="{{ config('setting')['software_description'] ?? ('Bem-vindo à '.config('setting')['software_name'].' - o melhor cassino online com uma ampla seleção de jogos de slot e experiência de aposta fácil e divertida.') }}">
    <meta name="keywords" content="{{ config('setting')['software_name'] }}, cassino online, jogos de slot, Fortune Tiger, Fortune OX">
    <meta property="og:locale" content="pt_BR" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ config('setting')['software_name'] }} - Apostas Online | Jogos de Slot" />
    <meta property="og:description" content="Bem-vindo à {{ config('setting')['software_name'] }} - o melhor cassino online." />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ config('setting')['software_name'] }}" />
    <meta property="og:image" content="{{ asset('/assets/images/banner-1.png') }}" />
    <meta property="og:image:secure_url" content="{{ asset('/assets/images/banner-1.png') }}" />
    <meta property="og:image:width" content="1024" />
    <meta property="og:image:height" content="571" />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ config('setting')['software_name'] }} - Apostas Online">
    <meta name="twitter:description" content="Bem-vindo à {{ config('setting')['software_name'] }} - o melhor cassino online.">
    <meta name="twitter:image" content="{{ asset('/assets/images/banner-1.png') }}">
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/splide-core.min.css') }}">
    <style>
        #image-carousel .splide__slide { overflow: hidden; }
        #image-carousel .splide__slide img { width: 100%; height: auto; display: block; }
        #image-carousel, #splide-soccer { opacity: 0; transition: opacity 0.3s; }
        #image-carousel.splide--initialized, #splide-soccer.splide--initialized { opacity: 1; }
    </style>
@endpush

@section('content')
    @include('includes.navbar_top')
    @include('includes.navbar_left')

    <div class="page__content">
        <div class="container">

            {{-- Banner Carousel --}}
            <style>#image-carousel { opacity: 0; transition: opacity 0.3s; } #image-carousel.splide--initialized { opacity: 1; }</style>
            <section id="image-carousel" class="splide" aria-label="Banner">
                <div class="splide__track">
                    <div class="splide-banner">
                        Ganhe 10 rodadas grátis <i class="fa-solid fa-fire ms-2"></i>
                    </div>
                    <ul class="splide__list">
                        @foreach(\App\Models\Banner::where('type', 'carousel')->get() as $banner)
                            <li class="splide__slide">
                                <a href="{{ $banner->link }}">
                                    <img src="{{ asset('storage/'.$banner->image) }}" alt="Banner" loading="lazy" style="max-width:100%;width:100%;height:auto;display:block;">
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>

            {{-- Search --}}
            <form action="{{ url('/') }}" method="GET" class="mt-2 mb-1">
                <div class="input-group input-search-group">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                           placeholder="Buscar jogos..." aria-label="Pesquisar">
                    <span class="input-group-text"><i class="fa-duotone fa-magnifying-glass"></i></span>
                </div>
            </form>

            {{-- Jogos da Casa --}}
            @if(count($gamesExclusives) > 0)
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-regular fa-gamepad-modern" style="color: var(--cor-principal); font-size: 1.5rem;"></i>
                        <h4 class="mb-0 fw-bold">Jogos da Casa</h4>
                    </div>
                    <a href="{{ url('/games?tab=exclusives') }}" class="text-decoration-none d-flex align-items-center gap-1" style="color: var(--cor-principal);">
                        Ver todos <i class="fa-regular fa-chevron-right"></i>
                    </a>
                </div>

                <style>.gamesSwiper { opacity: 0; transition: opacity 0.3s; } .gamesSwiper.swiper-initialized { opacity: 1; }</style>
                <div class="swiper gamesSwiper mb-5">
                    <div class="swiper-wrapper">
                        @foreach($gamesExclusives as $game)
                            <div class="swiper-slide">
                                <a href="{{ route('web.vgames.show', ['game' => $game->uuid]) }}" class="game-card-link">
                                    <div class="game-card-img-wrapper">
                                        <img src="{{ asset('storage/'.$game->cover) }}" alt="{{ $game->name }}"
                                             class="game-card-img" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block">
                                        <div class="game-card-overlay">
                                            <span class="game-card-play-btn">
                                                <i class="fa-solid fa-play"></i> Jogar
                                            </span>
                                        </div>
                                    </div>
                                    <div class="game-card-info">
                                        <span class="game-card-name">{{ $game->name }}</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            @endif

            {{-- FAQ --}}
            <div class="d-flex align-items-center justify-content-between mt-3 mb-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-light fa-circle-info" style="color: var(--cor-principal); font-size: 1.5rem;"></i>
                    <h4 class="mb-0 fw-bold">F.A.Q</h4>
                </div>
                <a href="{{ url('como-funciona') }}" class="text-decoration-none d-flex align-items-center gap-1" style="color: var(--cor-principal);">
                    Saiba mais <i class="fa-regular fa-chevron-right"></i>
                </a>
            </div>

            @include('web.home.sections.faq')
        </div>
    </div>

@include('includes.footer')
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/splide.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var el = document.getElementById('splide-soccer');
            if (el) {
                new Splide('#splide-soccer', {
                    type: 'loop', drag: 'free', focus: 'center',
                    autoplay: true, perPage: 3, arrows: false, pagination: false,
                    breakpoints: { 640: { perPage: 1 } }
                }).mount();
            }
            new Splide('#image-carousel', {
                arrows: false, pagination: false, type: 'loop', autoplay: true
            }).mount();

            new Swiper('.gamesSwiper', {
                slidesPerView: 2,
                spaceBetween: 16,
                loop: true,
                autoplay: { delay: 4000, disableOnInteraction: false },
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                breakpoints: {
                    480: { slidesPerView: 3 },
                    768: { slidesPerView: 4 },
                    1024: { slidesPerView: 5 },
                    1400: { slidesPerView: 6 }
                },
                on: {
                    init: function () {
                        this.el.classList.add('swiper-initialized');
                    }
                }
            });
        });
    </script>
@endpush
