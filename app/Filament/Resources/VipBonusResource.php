<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VipBonusResource\Pages;
use App\Models\VipBonus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class VipBonusResource extends Resource
{
    protected static ?string $model = VipBonus::class;

    protected static ?string $navigationLabel = 'Bônus VIP';

    protected static string|UnitEnum|null $navigationGroup = 'Configurações';

    protected static ?string $modelLabel = 'Bônus VIP';

    protected static ?string $pluralModelLabel = 'Bônus VIP';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-gift';

    protected static ?int $navigationSort = 16;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuário')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vip.name')
                    ->label('Nível')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'weekly' => 'success',
                        'monthly' => 'info',
                        'level_up' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'weekly' => 'Semanal',
                        'monthly' => 'Mensal',
                        'level_up' => 'Subiu de Nível',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('claimed_at')
                    ->label('Retirado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('claimed_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVipBonuses::route('/'),
        ];
    }
}
