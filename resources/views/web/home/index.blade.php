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
    <style>
        .banner-carousel { opacity: 0; transition: opacity 0.3s; }
        .banner-carousel.swiper-initialized { opacity: 1; }
    </style>
@endpush

@section('content')
    @include('includes.navbar_top')
    @include('includes.navbar_left')

    <div class="page__content">
        <div class="container">

            {{-- Banner Carousel --}}
            <div class="banner-carousel swiper mb-2" style="max-width:1320px;margin:12px auto 0;padding:0 calc(var(--bs-gutter-x, 1.5rem) * 0.5);">
                <div class="swiper-wrapper">
                    @foreach(\App\Models\Banner::where('type', 'carousel')->get() as $banner)
                        <div class="swiper-slide" style="border-radius:16px;overflow:hidden;">
                            <a href="{{ $banner->link }}">
                                <img src="{{ asset('storage/'.$banner->image) }}" alt="Banner" loading="lazy" width="1320" height="400" style="width:100%;height:auto;display:block;">
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="banner-next" style="color:#fff;background:rgba(0,0,0,0.4);width:40px;height:40px;border-radius:50%;transition:background 0.3s;"></div>
                <div class="banner-prev" style="color:#fff;background:rgba(0,0,0,0.4);width:40px;height:40px;border-radius:50%;transition:background 0.3s;"></div>
                <div class="banner-pagination"></div>
            </div>

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
                        <i class="fa-regular fa-gamepad-modern text-accent" style="font-size: 1.5rem;"></i>
                        <h4 class="mb-0 fw-bold">Jogos da Casa</h4>
                    </div>
                    <a href="{{ url('/games?tab=exclusives') }}" class="text-decoration-none d-flex align-items-center gap-1 text-accent">
                        Ver todos <i class="fa-regular fa-chevron-right"></i>
                    </a>
                </div>

                <div class="swiper gamesSwiper mb-5">
                    <div class="swiper-wrapper">
                        @foreach($gamesExclusives as $game)
                            <div class="swiper-slide">
                                <a href="{{ route('web.vgames.show', ['game' => $game->uuid]) }}" class="game-card-link">
                                    <div class="game-card-img-wrapper">
                                        <img src="{{ asset('storage/'.$game->cover) }}" alt="{{ $game->name }}"
                                             class="game-card-img" loading="lazy" width="200" height="267">
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
                    <i class="fa-light fa-circle-info text-accent" style="font-size: 1.5rem;"></i>
                    <h4 class="mb-0 fw-bold">F.A.Q</h4>
                </div>
                <a href="{{ url('como-funciona') }}" class="text-decoration-none d-flex align-items-center gap-1 text-accent">
                    Saiba mais <i class="fa-regular fa-chevron-right"></i>
                </a>
            </div>

            @include('web.home.sections.faq')
        </div>
    </div>

@include('includes.footer')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.banner-carousel', {
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
