<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EfiPaymentResource\Pages;
use App\Models\EfiPayment;
use App\Models\Transaction;
use App\Models\Wallet;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions\Action;
use Filament\Tables\Table;

class EfiPaymentResource extends Resource
{
    protected static ?string $model = EfiPayment::class;

    protected static ?string $navigationLabel = 'Pagamentos PIX';

    protected static string|\UnitEnum|null $navigationGroup = 'Financeiro';

    protected static ?string $modelLabel = 'Pagamento PIX';

    protected static ?string $pluralModelLabel = 'Pagamentos PIX';

    protected static ?string $slug = 'pagamentos-pix';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Informações do Pagamento')
                    ->schema([
                        TextInput::make('payment_id')->label('ID do Pagamento')->disabled(),
                        TextInput::make('user.name')->label('Usuário')->disabled(),
                        TextInput::make('amount')->label('Valor')->prefix('R$')->disabled(),
                        TextInput::make('cpf')->label('CPF')->disabled(),
                        TextInput::make('txid')->label('TxID')->disabled(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pendente',
                                'paid' => 'Pago',
                                'expired' => 'Expirado',
                            ])
                            ->disabled(),
                    ])->columns(3),

                Section::make('QR Code')
                    ->schema([
                        TextInput::make('pix_copy_paste')->label('Copia e Cola')->disabled()->columnSpanFull(),
                    ]),

                Section::make('Datas')
                    ->schema([
                        TextInput::make('expires_at')->label('Expira em')->disabled(),
                        TextInput::make('paid_at')->label('Pago em')->disabled(),
                        TextInput::make('created_at')->label('Criado em')->disabled(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label('#'),
                Tables\Columns\TextColumn::make('payment_id')->searchable()->label('Pagamento')->limit(18),
                Tables\Columns\TextColumn::make('user.name')->searchable()->sortable()->label('Usuário'),
                Tables\Columns\TextColumn::make('amount')->money('BRL')->sortable()->label('Valor'),
                Tables\Columns\TextColumn::make('cpf')->label('CPF'),
                Tables\Columns\TextColumn::make('txid')->label('TxID')->limit(16)->copyable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'expired' => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'paid' => 'Pago',
                        'pending' => 'Pendente',
                        'expired' => 'Expirado',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('expires_at')->dateTime('d/m/Y H:i')->label('Expira')->sortable(),
                Tables\Columns\TextColumn::make('paid_at')->dateTime('d/m/Y H:i')->label('Pago em'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->label('Criado')->sortable()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pendente',
                        'paid' => 'Pago',
                        'expired' => 'Expirado',
                    ])
                    ->label('Status'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('created_from')->label('De'),
                        \Filament\Forms\Components\DatePicker::make('created_until')->label('Até'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                        ->when($data['created_until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
                    ),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Action::make('confirm')
                    ->label('Confirmar Pagamento')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (EfiPayment $record): bool => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (EfiPayment $record) {
                        $record->update(['status' => 'paid', 'paid_at' => now()]);

                        $transaction = Transaction::where('payment_id', $record->payment_id)
                            ->where('status', 0)->first();
                        if ($transaction) {
                            $wallet = Wallet::where('user_id', $transaction->user_id)->first();
                            if ($wallet) {
                                $wallet->increment('balance', $transaction->price);
                                $transaction->update(['status' => 1]);
                            }
                        }

                        Notification::make()->title('Pagamento confirmado manualmente')->success()->send();
                    }),
                Action::make('expire')
                    ->label('Cancelar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (EfiPayment $record): bool => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (EfiPayment $record) {
                        $record->update(['status' => 'expired']);
                        Notification::make()->title('Pagamento cancelado')->danger()->send();
                    }),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEfiPayments::route('/'),
            'edit' => Pages\EditEfiPayment::route('/{record}/edit'),
        ];
    }
}
