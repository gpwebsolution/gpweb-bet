<div class="d-flex align-items-center justify-content-between my-3">
    <div class="d-flex align-items-center gap-2">
        <i class="{{ $icon }}" style="color: var(--cor-principal); font-size: 1.3rem;"></i>
        <h4 class="mb-0 fw-bold">{{ $title }}</h4>
    </div>
    <a href="{{ $link }}" class="text-decoration-none d-flex align-items-center gap-1" style="color: var(--cor-principal);">
        {{ $labelLink ?? 'Ver todos' }} <i class="fa-regular fa-chevron-right"></i>
    </a>
</div>