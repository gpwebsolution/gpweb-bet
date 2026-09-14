<?php

namespace App\Filament\Widgets;

use App\Models\Commission;
use App\Models\GameSession;
use App\Models\User;
use App\Models\Wallet;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Usuários Ativos', User::active()->count())
                ->description('Total de contas ativas')
                ->icon('heroicon-o-users')
                ->color('success'),
            Stat::make('Saldo Total', 'R$ ' . number_format(Wallet::sum('balance') + Wallet::sum('balance_bonus'), 2, ',', '.'))
                ->description('Saldo de todos os usuários')
                ->icon('heroicon-o-currency-dollar')
                ->color('info'),
            Stat::make('Apostas Hoje', GameSession::today()->count())
                ->description(number_format(GameSession::today()->sum('bet_amount'), 2, ',', '.') . ' em apostas')
                ->icon('heroicon-o-play')
                ->color('warning'),
            Stat::make('Comissões Pendentes', 'R$ ' . number_format(Commission::pending()->sum('amount'), 2, ',', '.'))
                ->description('Aguardando pagamento')
                ->icon('heroicon-o-banknotes')
                ->color('danger'),
        ];
    }
}
