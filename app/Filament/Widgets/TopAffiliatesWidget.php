<?php

namespace App\Filament\Widgets;

use App\Models\Affiliate;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopAffiliatesWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'half';

    protected static ?string $heading = 'Top Afiliados';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Affiliate::where('status', true)
                    ->orderByDesc('total_referred')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('name')->label('Nome')->searchable(),
                TextColumn::make('code')->label('Código'),
                TextColumn::make('total_referred')->label('Indicados')->sortable(),
                TextColumn::make('total_commission')->money('BRL')->label('Comissão Total')->sortable(),
                TextColumn::make('pending_commission')->money('BRL')->label('Pendente')->sortable(),
            ]);
    }
}
