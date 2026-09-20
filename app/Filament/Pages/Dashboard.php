<?php

namespace App\Filament\Pages;

use App\Models\Commission;
use App\Models\EfiPayment;
use App\Models\GameSession;
use App\Models\Saque;
use App\Models\User;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    protected string $view = 'filament.pages.dashboard';

    public ?string $startDate = null;

    public ?string $endDate = null;

    public function getViewData(): array
    {
        $query = GameSession::query();

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        $depositQuery = EfiPayment::query();
        $saqueQuery = Saque::query();

        if ($this->startDate) {
            $depositQuery->whereDate('created_at', '>=', $this->startDate);
            $saqueQuery->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $depositQuery->whereDate('created_at', '<=', $this->endDate);
            $saqueQuery->whereDate('created_at', '<=', $this->endDate);
        }

        return [
            'totalSessions' => (clone $query)->count(),
            'totalRevenue' => (clone $query)->sum('profit'),
            'totalBets' => (clone $query)->sum('bet_amount'),
            'activeUsers' => User::where('status', 'active')->count(),
            'suspendedUsers' => User::where('status', 'suspended')->count(),
            'pendingCommissions' => Commission::where('status', 'pending')->sum('amount'),
            'totalDeposits' => (clone $depositQuery)->where('status', 'paid')->sum('amount'),
            'totalWithdrawals' => (clone $saqueQuery)->where('status', 'paid')->sum('amount'),
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ];
    }
}
