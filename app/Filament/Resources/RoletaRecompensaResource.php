<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoletaRecompensaResource\Pages;
use App\Models\RoletaRecompensa;
use BackedEnum;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class RoletaRecompensaResource extends Resource
{
    protected static ?string $model = RoletaRecompensa::class;

    protected static ?string $navigationLabel = 'Roleta Diária';

    protected static string|UnitEnum|null $navigationGroup = 'Jogos';

    protected static ?string $modelLabel = 'Prêmio';

    protected static ?string $pluralModelLabel = 'Prêmios da Roleta';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Prêmio')
                    ->schema([
                        TextInput::make('label')
                            ->label('Nome do Prêmio')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('value')
                            ->label('Valor (R$)')
                            ->numeric()
                            ->required()
                            ->step(0.01),
                        TextInput::make('weight')
                            ->label('Peso (Probabilidade)')
                            ->helperText('Maior número = mais chance de cair. Ex: 100 = muito comum, 1 = raro')
                            ->numeric()
                            ->required()
                            ->default(1),
                        ColorPicker::make('color')
                            ->label('Cor do Segmento')
                            ->default('#e74c3c'),
                        TextInput::make('sort_order')
                            ->label('Ordem')
                            ->numeric()
                            ->default(0),
                        Toggle::make('active')
                            ->label('Ativo')
                            ->default(true),
                        Toggle::make('garantido')
                            ->label('Garantido (100%)')
                            ->helperText('Se ativo, este prêmio cairá com 100% de certeza no próximo giro')
                            ->default(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')->sortable()->label('#'),
                Tables\Columns\ColorColumn::make('color')->label('Cor'),
                Tables\Columns\TextColumn::make('label')->searchable()->label('Prêmio'),
                Tables\Columns\TextColumn::make('value')->money('BRL')->sortable()->label('Valor'),
                Tables\Columns\TextColumn::make('weight')->sortable()->label('Peso'),
                Tables\Columns\ToggleColumn::make('active')->label('Ativo'),
                Tables\Columns\ToggleColumn::make('garantido')->label('Garantido'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoletaRecompensas::route('/'),
            'create' => Pages\CreateRoletaRecompensa::route('/create'),
            'edit' => Pages\EditRoletaRecompensa::route('/{record}/edit'),
        ];
    }
}
