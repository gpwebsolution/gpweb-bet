@extends('layouts.web')

@push('styles')
<style>
.game-card-link {
    text-decoration: none;
    display: block;
    border-radius: 12px;
    overflow: hidden;
    background: var(--cor-secundaria, #1a1f2e);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.game-card-link:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
}
.game-card-img-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 3/4;
    overflow: hidden;
}
.game-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.game-card-link:hover .game-card-img {
    transform: scale(1.08);
}
.game-card-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.game-card-link:hover .game-card-overlay {
    opacity: 1;
}
.game-card-play-btn {
    background: var(--cor-principal, #e74c3c);
    color: #fff;
    padding: 10px 24px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 8px;
}
.game-card-info {
    padding: 12px;
}
.game-card-name {
    color: #fff;
    font-weight: 600;
    font-size: 0.85rem;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.game-item {
    animation: fadeInUp 0.4s ease-out forwards;
    opacity: 0;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    @include('includes.navbar_top')
    @include('includes.navbar_left')

    <div class="page__content">
        <br>
        <div class="@if(\Helper::getCustomLayout()['expanded_layout']) container-fluid @else container @endif">

            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="fw-bold mb-0">Todos os Jogos</h4>
            </div>

            <div class="row g-3">
                @forelse($games as $game)
                    <div class="col-4 col-md-3 col-lg-2 game-item">
                        <a href="{{ route('web.vgames.show', ['game' => $game->uuid]) }}" class="game-card-link">
                            <div class="game-card-img-wrapper">
                                <img src="{{ asset('storage/'.$game->cover) }}" alt="{{ $game->name }}" class="game-card-img" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block">
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
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Nenhum jogo encontrado.</p>
                    </div>
                @endforelse
            </div>

            @if($games->hasPages())
                <div class="mt-4">
                    {{ $games->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection