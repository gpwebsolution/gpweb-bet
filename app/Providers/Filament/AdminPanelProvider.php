<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Pages\Settings;
use App\Filament\Resources\ProviderResource;
use App\Filament\Resources\RoletaRecompensaResource;
use App\Filament\Resources\BannerResource;
use App\Filament\Resources\EfiPaymentResource;
use App\Filament\Resources\GameExclusiveResource;
use App\Filament\Resources\GameResource;
use App\Filament\Resources\SaqueResource;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\VipBonusResource;
use App\Filament\Resources\VipResource;
use App\Filament\Resources\WalletResource;
use App\Filament\Widgets\RevenueChartWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\TopAffiliatesWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Red,
                'secondary' => Color::Amber,
                'success' => Color::Emerald,
                'info' => Color::Sky,
                'warning' => Color::Orange,
                'danger' => Color::Rose,
            ])
            ->darkMode(true)
            ->viteTheme('resources/css/filament.css')
            ->brandName('MarioBET')
            ->brandLogo(fn () => view('filament.brand'))
            ->favicon(asset('assets/images/favicon.png'))
            ->navigationGroups([
                NavigationGroup::make('Usuários'),
                NavigationGroup::make('Jogos'),
                NavigationGroup::make('Financeiro'),
                NavigationGroup::make('Carteira'),
                NavigationGroup::make('Configurações'),
            ])
            ->resources([
                UserResource::class,
                WalletResource::class,
                GameExclusiveResource::class,
                GameResource::class,
                ProviderResource::class,
                EfiPaymentResource::class,
                BannerResource::class,
                RoletaRecompensaResource::class,
                SaqueResource::class,
                VipResource::class,
                VipBonusResource::class,
            ])
            ->pages([
                Dashboard::class,
                Settings::class,
            ])
            ->widgets([
                StatsOverviewWidget::class,
                RevenueChartWidget::class,
                TopAffiliatesWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class]);
    }
}
