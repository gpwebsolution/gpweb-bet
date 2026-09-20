@extends('layouts.web')

@section('content')
    @include('includes.navbar_top')
    @include('includes.navbar_left')

    <div class="page__content">
        <br>
        <div class="container">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--cor-principal), color-mix(in srgb, var(--cor-principal) 60%, #000)); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-regular fa-bell" style="color: white; font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold text-white">Notificações</h3>
                        <p class="mb-0" style="color: rgba(255,255,255,0.5); font-size: 0.875rem;">
                            @if(count($notifications) > 0)
                                {{ count($notifications) }} notifica{{ count($notifications) > 1 ? 'ções' : 'ção' }}
                            @endif
                        </p>
                    </div>
                </div>

                @if(count($notifications) > 0)
                    <div class="mb-4">
                        @foreach($notifications as $notification)
                            <div class="notification-item">
                                <div class="notification-icon">
                                    <i class="fa-regular fa-bell"></i>
                                </div>
                                <div class="notification-body">
                                    {{ $notification->data['message'] ?? 'Nova notificação' }}
                                </div>
                                <div class="notification-time">
                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,0.04); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <i class="fa-regular fa-bell-slash" style="font-size: 2rem; color: rgba(255,255,255,0.2);"></i>
                        </div>
                        <h5 class="text-muted-40">Nenhuma notificação</h5>
                        <p style="color: rgba(255,255,255,0.25); font-size: 0.875rem;">Você não possui notificações no momento</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
