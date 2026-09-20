<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationLabel = 'Banners';

    protected static string|UnitEnum|null $navigationGroup = 'Configurações';

    protected static ?string $modelLabel = 'Banner';

    protected static ?string $pluralModelLabel = 'Banners';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static ?int $navigationSort = 10;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Banner')
                    ->schema([
                        TextInput::make('title')
                            ->label('Título')
                            ->maxLength(255),
                        FileUpload::make('image')
                            ->label('Imagem')
                            ->image()
                            ->required(),
                        Textarea::make('description')
                            ->label('Descrição'),
                        TextInput::make('link')
                            ->label('Link')
                            ->maxLength(191)
                            ->placeholder('https:// ou /rota'),
                        Select::make('type')
                            ->label('Tipo')
                            ->options([
                                'home' => 'Home',
                                'promo' => 'Promoção',
                                'other' => 'Outro',
                            ])
                            ->default('home'),
                        TextInput::make('sort_order')
                            ->label('Ordem')
                            ->numeric()
                            ->default(0),
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
                Tables\Columns\TextColumn::make('sort_order')->sortable()->label('#'),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Imagem')
                    ->width(160)
                    ->state(fn (Banner $record): string => $record->imageUrl()),
                Tables\Columns\TextColumn::make('title')->searchable()->label('Título'),
                Tables\Columns\TextColumn::make('type')->badge()->label('Tipo'),
                Tables\Columns\TextColumn::make('link')->label('Link')->limit(30),
                Tables\Columns\ToggleColumn::make('active')->label('Ativo'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y')->sortable()->label('Criado'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
