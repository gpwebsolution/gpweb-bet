<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WalletResource\Pages;
use App\Models\Wallet;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use UnitEnum;
use Filament\Tables;
use Filament\Tables\Table;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;

    protected static ?string $navigationLabel = 'Carteiras';

    protected static string|UnitEnum|null $navigationGroup = 'Carteira';

    protected static ?string $modelLabel = 'Carteira';

    protected static ?string $pluralModelLabel = 'Carteiras';

    protected static ?string $slug = 'carteiras';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Saldo')
                    ->schema([
                        TextInput::make('balance')
                            ->numeric()
                            ->required()
                            ->label('Saldo Principal')
                            ->prefix('R$'),
                        TextInput::make('balance_bonus')
                            ->numeric()
                            ->label('Saldo Bônus')
                            ->prefix('R$'),
                        TextInput::make('refer_rewards')
                            ->numeric()
                            ->label('Ganhos de Afiliado')
                            ->prefix('R$'),
                        TextInput::make('anti_bot')
                            ->numeric()
                            ->label('Anti-Bot')
                            ->prefix('R$'),
                        TextInput::make('balance_bonus_rollover')
                            ->numeric()
                            ->label('Rollover Bônus')
                            ->prefix('R$'),
                        Toggle::make('hide_balance')
                            ->label('Ocultar Saldo'),
                    ])->columns(3),

                Section::make('Estatísticas')
                    ->schema([
                        TextInput::make('total_bet')
                            ->numeric()
                            ->label('Total Apostado')
                            ->prefix('R$'),
                        TextInput::make('total_won')
                            ->numeric()
                            ->label('Total Ganho')
                            ->prefix('R$'),
                        TextInput::make('total_lose')
                            ->numeric()
                            ->label('Total Perdido')
                            ->prefix('R$'),
                        TextInput::make('last_won')
                            ->numeric()
                            ->label('Último Ganho')
                            ->prefix('R$'),
                        TextInput::make('last_lose')
                            ->numeric()
                            ->label('Última Perda')
                            ->prefix('R$'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->label('Usuário'),
                Tables\Columns\TextColumn::make('balance')
                    ->money('BRL')
                    ->sortable()
                    ->label('Saldo'),
                Tables\Columns\TextColumn::make('balance_bonus')
                    ->money('BRL')
                    ->sortable()
                    ->label('Bônus'),
                Tables\Columns\TextColumn::make('refer_rewards')
                    ->money('BRL')
                    ->sortable()
                    ->label('Afiliados'),
                Tables\Columns\TextColumn::make('total_balance')
                    ->money('BRL')
                    ->label('Total'),
                Tables\Columns\IconColumn::make('hide_balance')
                    ->boolean()
                    ->label('Oculto'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Criado em')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('hide_balance')
                    ->options([1 => 'Sim', 0 => 'Não'])
                    ->label('Saldo Oculto'),
            ])
            ->defaultSort('balance', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWallets::route('/'),
            'edit' => Pages\EditWallet::route('/{record}/edit'),
        ];
    }
}
