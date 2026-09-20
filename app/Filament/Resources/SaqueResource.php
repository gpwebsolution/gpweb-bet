<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaqueResource\Pages;
use App\Models\Saque;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SaqueResource extends Resource
{
    protected static ?string $model = Saque::class;

    protected static ?string $navigationLabel = 'Saques';

    protected static string|\UnitEnum|null $navigationGroup = 'Financeiro';

    protected static ?string $modelLabel = 'Saque';

    protected static ?string $pluralModelLabel = 'Saques';

    protected static ?string $slug = 'saques';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-up-tray';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Informações do Saque')
                    ->schema([
                        TextInput::make('user.name')->label('Usuário')->disabled(),
                        TextInput::make('amount')->label('Valor')->prefix('R$')->disabled(),
                        TextInput::make('type')->label('Tipo')->disabled(),
                        TextInput::make('chave_pix')->label('Chave Pix')->disabled(),
                        TextInput::make('tipo_chave')->label('Tipo da Chave')->disabled(),
                        TextInput::make('document')->label('CPF/CNPJ')->disabled(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                0 => 'Pendente',
                                1 => 'Confirmado',
                                2 => 'Cancelado',
                            ])
                            ->disabled(),
                    ])->columns(3),
                Section::make('Datas')
                    ->schema([
                        TextInput::make('created_at')->label('Solicitado em')->disabled(),
                        TextInput::make('updated_at')->label('Atualizado em')->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label('#'),
                Tables\Columns\TextColumn::make('user.name')->searchable()->sortable()->label('Usuário'),
                Tables\Columns\TextColumn::make('amount')->money('BRL')->sortable()->label('Valor'),
                Tables\Columns\TextColumn::make('type')->label('Tipo'),
                Tables\Columns\TextColumn::make('chave_pix')->label('Chave Pix')->limit(20)->copyable(),
                Tables\Columns\TextColumn::make('tipo_chave')->label('Tipo Chave'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'success',
                        0 => 'warning',
                        2 => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        0 => 'Pendente',
                        1 => 'Confirmado',
                        2 => 'Cancelado',
                        default => 'Desconhecido',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->label('Data')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        0 => 'Pendente',
                        1 => 'Confirmado',
                        2 => 'Cancelado',
                    ])
                    ->label('Status'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('De'),
                        DatePicker::make('created_until')->label('Até'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                        ->when($data['created_until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
                    ),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Action::make('approve')
                    ->label('Aprovar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Saque $record): bool => $record->status === 0)
                    ->requiresConfirmation()
                    ->action(function (Saque $record) {
                        $record->update(['status' => 1, 'proof' => 'admin_approved']);
                        Notification::make()->title('Saque aprovado')->success()->send();
                    }),
                Action::make('reject')
                    ->label('Recusar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Saque $record): bool => $record->status === 0)
                    ->requiresConfirmation()
                    ->action(function (Saque $record) {
                        $record->update(['status' => 2]);
                        if ($record->user->wallet) {
                            $record->user->wallet->increment('balance', $record->amount);
                        }
                        Notification::make()->title('Saque recusado, saldo estornado')->danger()->send();
                    }),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSaques::route('/'),
            'edit' => Pages\EditSaque::route('/{record}/edit'),
        ];
    }
}
