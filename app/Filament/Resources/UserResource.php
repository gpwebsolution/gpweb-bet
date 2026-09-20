<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Usuários';

    protected static string|UnitEnum|null $navigationGroup = 'Usuários';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Informações Básicas')
                    ->schema([
                        TextInput::make('name')->required()->maxLength(255)->label('Nome'),
                        TextInput::make('email')->email()->required()->unique(ignoreRecord: true)->label('E-mail'),
                        TextInput::make('password')->password()->hiddenOn('edit')->label('Senha'),
                        Select::make('status')
                            ->options(['active' => 'Ativo', 'suspended' => 'Suspenso'])
                            ->required()
                            ->label('Status'),
                    ])->columns(2),

                Section::make('Funções')
                    ->schema([
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->label('Funções'),
                    ]),

                Section::make('Configuração de Afiliado')
                    ->description('Configure as comissões que este usuário receberá como afiliado')
                    ->schema([
                        TextInput::make('affiliate_revenue_share')
                            ->numeric()
                            ->suffix('%')
                            ->default(0)
                            ->helperText('Comissão em % que este afiliado recebe sobre apostas dos indicados. Deixe 0 para usar o percentual global.')
                            ->label('Revenue Share (%)'),
                        TextInput::make('affiliate_cpa')
                            ->numeric()
                            ->prefix('R$')
                            ->default(0)
                            ->helperText('Valor fixo pago por depósito qualificado')
                            ->label('CPA (R$)'),
                        TextInput::make('affiliate_baseline')
                            ->numeric()
                            ->prefix('R$')
                            ->default(0)
                            ->helperText('Depósito mínimo para liberar o CPA')
                            ->label('Baseline (R$)'),
                        TextInput::make('inviter')
                            ->numeric()
                            ->nullable()
                            ->helperText('ID do usuário que indicou este')
                            ->label('Indicado por (ID)'),
                    ])->columns(2),

                Section::make('Influenciador')
                    ->schema([
                        Toggle::make('is_demo_agent')
                            ->label('Marcar como Influenciador')
                            ->helperText('Influenciadores têm probabilidades diferentes nos jogos (mais vitórias)')
                            ->default(false),
                    ]),

                Section::make('Outros')
                    ->schema([
                        TextInput::make('last_name')->label('Sobrenome'),
                        TextInput::make('cpf')->label('CPF'),
                        TextInput::make('phone')->label('Telefone'),
                        Toggle::make('cpf_confirmed')
                            ->label('CPF Confirmado')
                            ->helperText('Marque quando o CPF do usuário for verificado')
                            ->default(false),
                        TextInput::make('affiliate_code')
                            ->label('Código de Afiliado')
                            ->disabled()
                            ->dehydrated(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->label('Nome'),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable()->label('E-mail'),
                Tables\Columns\TextColumn::make('balance')->money('BRL')->sortable()->label('Saldo'),
                Tables\Columns\TextColumn::make('affiliate_code')->searchable()->label('Cód. Afiliado'),
                Tables\Columns\TextColumn::make('game_sessions_count')->counts('gameSessions')->label('Apostas'),
                Tables\Columns\IconColumn::make('cpf_confirmed')
                    ->boolean()
                    ->label('CPF OK'),
                Tables\Columns\IconColumn::make('is_demo_agent')
                    ->boolean()
                    ->label('Influenciador'),
                Tables\Columns\TextColumn::make('affiliate_revenue_share')
                    ->suffix('%')
                    ->sortable()
                    ->label('Comissão'),
                Tables\Columns\TextColumn::make('inviter')
                    ->sortable()
                    ->label('Indicado Por'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'suspended' => 'danger',
                    })->label('Status'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->label('Criado em'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(['active' => 'Ativo', 'suspended' => 'Suspenso']),
                SelectFilter::make('is_demo_agent')
                    ->options([1 => 'Influenciador', 0 => 'Normal'])
                    ->label('Tipo'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Action::make('toggleStatus')
                    ->label(fn (User $record): string => $record->status === 'active' ? 'Suspender' : 'Ativar')
                    ->icon(fn (User $record): string => $record->status === 'active' ? 'heroicon-o-no-symbol' : 'heroicon-o-check-circle')
                    ->color(fn (User $record): string => $record->status === 'active' ? 'warning' : 'success')
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $newStatus = $record->status === 'active' ? 'suspended' : 'active';
                        $record->update(['status' => $newStatus]);
                        Notification::make()
                            ->title($newStatus === 'active' ? 'Usuário ativado' : 'Usuário suspenso')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
