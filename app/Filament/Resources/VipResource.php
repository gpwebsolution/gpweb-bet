<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VipResource\Pages;
use App\Models\Vip;
use BackedEnum;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class VipResource extends Resource
{
    protected static ?string $model = Vip::class;

    protected static ?string $navigationLabel = 'Níveis VIP';

    protected static string|UnitEnum|null $navigationGroup = 'Configurações';

    protected static ?string $modelLabel = 'Nível VIP';

    protected static ?string $pluralModelLabel = 'Níveis VIP';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    protected static ?int $navigationSort = 15;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Nível VIP')
                    ->schema([
                        TextInput::make('level')
                            ->label('Nível')
                            ->numeric()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('weekly_bonus')
                            ->label('Bônus Semanal (R$)')
                            ->numeric()
                            ->required()
                            ->step(0.01),
                        TextInput::make('level_up_bonus')
                            ->label('Bônus de Upgrade (R$)')
                            ->numeric()
                            ->required()
                            ->step(0.01),
                        TextInput::make('monthly_bonus')
                            ->label('Bônus Mensal (R$)')
                            ->numeric()
                            ->required()
                            ->step(0.01),
                        TextInput::make('min_deposit')
                            ->label('Depósito Mínimo (R$)')
                            ->numeric()
                            ->required()
                            ->step(0.01),
                        TextInput::make('min_bets')
                            ->label('Valor Mínimo em Apostas (R$)')
                            ->numeric()
                            ->required()
                            ->step(0.01),
                        ColorPicker::make('color')
                            ->label('Cor')
                            ->default('#e63946'),
                        TextInput::make('icon')
                            ->label('Ícone (classe FontAwesome)')
                            ->placeholder('fa-solid fa-crown')
                            ->maxLength(50),
                        Textarea::make('description')
                            ->label('Descrição')
                            ->maxLength(65535),
                        Toggle::make('active')
                            ->label('Ativo')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('level')
                    ->label('Nível')
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color')
                    ->label(''),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('weekly_bonus')
                    ->label('Semanal')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('monthly_bonus')
                    ->label('Mensal')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('level_up_bonus')
                    ->label('Upgrade')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('min_deposit')
                    ->label('Depósito Mín.')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('min_bets')
                    ->label('Apostas (R$)')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('Ativo'),
                Tables\Columns\TextColumn::make('users_count')
                    ->label('Usuários')
                    ->counts('users')
                    ->sortable(),
            ])
            ->defaultSort('level');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVips::route('/'),
            'create' => Pages\CreateVip::route('/create'),
            'edit' => Pages\EditVip::route('/{record}/edit'),
        ];
    }
}
