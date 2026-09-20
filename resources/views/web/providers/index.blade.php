@extends('layouts.web')

@push('styles')
<style>
.provider-nav-horizontal {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    white-space: nowrap;
    scrollbar-width: none;
    padding: 12px 0;
    margin-bottom: 20px;
    border-bottom: 1px solid var(--cor-borda, rgba(255,255,255,0.06));
}
.provider-nav-horizontal::-webkit-scrollbar { display: none; }

.provider-sidebar {
    position: sticky;
    top: 80px;
    max-height: calc(100vh - 100px);
    overflow-y: auto;
    scrollbar-width: none;
    padding-right: 16px;
}
.provider-sidebar::-webkit-scrollbar { display: none; }
.provider-sidebar .provider-list {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.btn-provider {
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    border: 1px solid var(--cor-borda, rgba(255,255,255,0.1));
    background: transparent;
    color: var(--cor-texto, #aaa);
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}
.btn-provider:hover {
    border-color: var(--cor-principal, #e74c3c);
    color: #fff;
}
.btn-provider.active {
    background: var(--cor-principal, #e74c3c);
    border-color: var(--cor-principal, #e74c3c);
    color: #fff;
}
.btn-provider img {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    object-fit: contain;
}

.provider-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    padding: 20px;
    background: var(--cor-secundaria, #1a1f2e);
    border-radius: 12px;
    border: 1px solid var(--cor-borda, rgba(255,255,255,0.06));
}
.provider-header img {
    width: 64px;
    height: 64px;
    object-fit: contain;
    border-radius: 8px;
}
.provider-header h1 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
}
.provider-header p {
    margin: 4px 0 0;
    opacity: 0.6;
    font-size: 0.9rem;
}

.provider-sidebar .btn-provider {
    border-radius: 10px;
    width: 100%;
    justify-content: flex-start;
}

.games-grid {
    position: relative;
    overflow: hidden;
}
.games-grid .row {
    margin: 0 -6px;
}
.games-grid .row > div {
    padding: 6px;
}
.game-card {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    background: var(--cor-secundaria, #1a1f2e);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    aspect-ratio: 3 / 4;
    width: 100%;
}
.game-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
}
.game-card a {
    display: block;
    width: 100%;
    height: 100%;
    text-decoration: none;
}
.game-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.game-item {
    animation: slideInRight 0.5s ease-out forwards;
    opacity: 0;
}
.game-item:nth-child(1) { animation-delay: 0.02s; }
.game-item:nth-child(2) { animation-delay: 0.06s; }
.game-item:nth-child(3) { animation-delay: 0.10s; }
.game-item:nth-child(4) { animation-delay: 0.14s; }
.game-item:nth-child(5) { animation-delay: 0.18s; }
.game-item:nth-child(6) { animation-delay: 0.22s; }
.game-item:nth-child(7) { animation-delay: 0.26s; }
.game-item:nth-child(8) { animation-delay: 0.30s; }
.game-item:nth-child(9) { animation-delay: 0.34s; }

@keyframes slideInRight {
    from { opacity: 0; transform: translateX(60px); }
    to { opacity: 1; transform: translateX(0); }
}

.pagination-wrapper {
    margin-top: 32px;
    padding: 16px 0;
}
.pagination-wrapper nav {
    display: flex;
    justify-content: center;
}
.pagination-wrapper .pagination {
    gap: 4px;
}
.pagination-wrapper .page-link {
    border-radius: 8px;
    padding: 8px 16px;
    font-size: 0.85rem;
    font-weight: 600;
    border: 1px solid var(--cor-borda, rgba(255,255,255,0.1));
    background: transparent;
    color: var(--cor-texto, #aaa);
    transition: all 0.3s ease;
}
.pagination-wrapper .page-link:hover {
    border-color: var(--cor-principal, #e74c3c);
    color: #fff;
    background: rgba(231, 76, 60, 0.1);
}
.pagination-wrapper .page-item.active .page-link {
    background: var(--cor-principal, #e74c3c);
    border-color: var(--cor-principal, #e74c3c);
    color: #fff;
}
.pagination-wrapper .page-item.disabled .page-link {
    opacity: 0.4;
    pointer-events: none;
}
.pagination-animated {
    animation: fadeInUp 0.4s ease-out;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush

@section('content')
    @include('includes.navbar_top')
    @include('includes.navbar_left')

    <div class="page__content">
        <br>
        <div class="container">

            {{-- Horizontal nav: shows on mobile/tablet --}}
            <div class="d-lg-none provider-nav-horizontal">
                @foreach($providers as $prov)
                    <a href="{{ route('web.provider.index', ['slug' => $prov->slug]) }}"
                       class="btn-provider {{ $prov->id === $provider->id ? 'active' : '' }}">
                        @if($prov->image)
                            <img src="{{ asset('storage/'.$prov->image) }}" alt="">
                        @endif
                        {{ $prov->name }}
                    </a>
                @endforeach
            </div>

            <div class="row">
                {{-- Sidebar: shows on desktop --}}
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="provider-sidebar">
                        <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Provedores</h6>
                        <div class="provider-list">
                            @foreach($providers as $prov)
                                <a href="{{ route('web.provider.index', ['slug' => $prov->slug]) }}"
                                   class="btn-provider {{ $prov->id === $provider->id ? 'active' : '' }}">
                                    @if($prov->image)
                                        <img src="{{ asset('storage/'.$prov->image) }}" alt="">
                                    @endif
                                    {{ $prov->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Game grid --}}
                <div class="col-lg-9">
                    <div class="provider-header">
                        @if($provider->image)
                            <img src="{{ asset('storage/'.$provider->image) }}" alt="{{ $provider->name }}" style="max-width:100%;height:auto;display:block">
                        @endif
                        <div>
                            <h1>{{ $provider->name }}</h1>
                            <p>{{ $provider->description ?: 'Jogos deste provedor' }}</p>
                        </div>
                    </div>

                    <div class="games-grid" id="gamesGrid">
                        <div class="row">
                            @forelse($games as $game)
                                <div class="col-4 col-md-3 col-lg-2 game-item">
                                    <div class="game-card">
                                        <a href="{{ route('web.game.index', ['slug' => $game->uuid]) }}">
                                            <img src="{{ asset('storage/'.$game->image) }}" alt="{{ $game->name }}" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block">
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <p class="text-muted">Nenhum jogo encontrado neste provedor.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    @if($games->hasPages())
                        <div class="pagination-wrapper pagination-animated">
                            {{ $games->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-provider').on('click', function(e) {
        if ($(this).hasClass('active')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
