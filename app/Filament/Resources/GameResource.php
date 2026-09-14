<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameResource\Pages;
use App\Models\Game;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class GameResource extends Resource
{
    protected static ?string $model = Game::class;

    protected static ?string $navigationLabel = 'Jogos';

    protected static string|UnitEnum|null $navigationGroup = 'Jogos';

    protected static ?string $modelLabel = 'Jogo';

    protected static ?string $pluralModelLabel = 'Jogos';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-play-circle';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Informações do Jogo')
                    ->schema([
                        Select::make('provider_id')
                            ->label('Provedor')
                            ->relationship('provider', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('uuid')
                            ->label('UUID')
                            ->maxLength(191),
                        TextInput::make('provider')
                            ->label('Provedor')
                            ->maxLength(100),
                        TextInput::make('provider_service')
                            ->label('Serviço')
                            ->maxLength(100),
                        Select::make('type')
                            ->label('Tipo')
                            ->options([
                                'slot' => 'Slot',
                                'table' => 'Mesa',
                                'live' => 'Ao Vivo',
                                'crash' => 'Crash',
                                'other' => 'Outro',
                            ]),
                        Select::make('technology')
                            ->label('Tecnologia')
                            ->options([
                                'html5' => 'HTML5',
                                'flash' => 'Flash',
                                'unity' => 'Unity',
                                'other' => 'Outro',
                            ]),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->maxLength(191),
                        TextInput::make('views')
                            ->label('Visualizações')
                            ->numeric()
                            ->default(0),
                        Toggle::make('active')
                            ->label('Ativo')
                            ->default(true),
                        Toggle::make('has_lobby')
                            ->label('Possui Lobby'),
                        Toggle::make('is_mobile')
                            ->label('Mobile'),
                        Toggle::make('has_freespins')
                            ->label('Free Spins'),
                        Toggle::make('has_tables')
                            ->label('Mesas'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label('#'),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->label('Nome'),
                Tables\Columns\TextColumn::make('provider.name')->sortable()->label('Provedor'),
                Tables\Columns\TextColumn::make('provider')->label('Provedor')->searchable(),
                Tables\Columns\TextColumn::make('type')->badge()->label('Tipo'),
                Tables\Columns\ToggleColumn::make('active')->label('Ativo'),
                Tables\Columns\TextColumn::make('views')->sortable()->label('Views'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y')->sortable()->label('Criado'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('active')
                    ->options([1 => 'Ativo', 0 => 'Inativo'])
                    ->label('Status'),
                Tables\Filters\SelectFilter::make('provider_id')
                    ->relationship('provider', 'name')
                    ->label('Provedor'),
                Tables\Filters\SelectFilter::make('provider')
                    ->options(fn () => Game::distinct()->pluck('provider', 'provider')->toArray())
                    ->label('Provedor'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGames::route('/'),
            'create' => Pages\CreateGame::route('/create'),
            'edit' => Pages\EditGame::route('/{record}/edit'),
        ];
    }
}
