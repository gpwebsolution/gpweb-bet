<?php

namespace App\Filament\Widgets;

use App\Models\GameSession;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RevenueChartWidget extends ChartWidget
{
    protected ?string $heading = 'Receita - Últimos 30 Dias';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = GameSession::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(profit) as ggr'),
            DB::raw('COUNT(*) as bets'),
        )
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $usersData = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
        )
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        return [
            'datasets' => [
                [
                    'label' => 'GGR Diário',
                    'data' => $data->pluck('ggr')->toArray(),
                    'borderColor' => '#e63946',
                    'backgroundColor' => 'rgba(230, 57, 70, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Apostas',
                    'data' => $data->pluck('bets')->toArray(),
                    'borderColor' => '#fbbf24',
                    'backgroundColor' => 'rgba(251, 191, 36, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): ?array
    {
        return [
            'scales' => [
                'y' => ['beginAtZero' => true, 'title' => ['display' => true, 'text' => 'GGR (R$)']],
                'y1' => ['position' => 'right', 'beginAtZero' => true, 'title' => ['display' => true, 'text' => 'Apostas']],
            ],
            'plugins' => [
                'legend' => ['position' => 'bottom'],
            ],
        ];
    }
}
