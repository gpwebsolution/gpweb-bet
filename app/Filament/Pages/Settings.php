<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

class Settings extends Page
{
    protected string $view = 'filament.pages.settings';

    public ?string $software_name = null;

    public ?string $software_description = null;

    public $software_logo_white = [];

    public ?string $prefix = null;

    public ?string $currency_code = null;

    public ?string $decimal_format = null;

    public ?string $currency_position = null;

    public ?string $storage = null;

    public ?string $min_deposit = null;

    public ?string $max_deposit = null;

    public ?string $min_saque = null;

    public ?string $max_saque = null;

    public ?string $initial_bonus = null;

    public ?string $ngr_percent = null;

    public bool $revshare_reverse = false;

    public ?string $instagram = null;

    public ?string $tiktok = null;

    public ?string $whatsapp = null;

    public ?string $discord = null;

    public ?string $telegram = null;

    public ?string $twitter = null;

    public ?string $affiliate_default_percentage = null;

    public ?string $affiliate_default_cpa = null;

    public ?string $affiliate_default_baseline = null;

    public function mount(): void
    {
        $setting = Setting::first();
        if ($setting) {
            $data = $setting->toArray();
            $this->software_name = $data['software_name'] ?? null;
            $this->software_description = $data['software_description'] ?? null;
            $this->software_logo_white = ! empty($data['software_logo_white']) ? [$data['software_logo_white']] : [];
            $this->prefix = $data['prefix'] ?? null;
            $this->currency_code = $data['currency_code'] ?? null;
            $this->decimal_format = $data['decimal_format'] ?? null;
            $this->currency_position = $data['currency_position'] ?? null;
            $this->storage = $data['storage'] ?? null;
            $this->min_deposit = $data['min_deposit'] ?? null;
            $this->max_deposit = $data['max_deposit'] ?? null;
            $this->min_saque = $data['min_saque'] ?? null;
            $this->max_saque = $data['max_saque'] ?? null;
            $this->initial_bonus = $data['initial_bonus'] ?? null;
            $this->ngr_percent = $data['ngr_percent'] ?? null;
            $this->revshare_reverse = filter_var($data['revshare_reverse'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $this->instagram = $data['instagram'] ?? null;
            $this->tiktok = $data['tiktok'] ?? null;
            $this->whatsapp = $data['whatsapp'] ?? null;
            $this->discord = $data['discord'] ?? null;
            $this->telegram = $data['telegram'] ?? null;
            $this->twitter = $data['twitter'] ?? null;
            $this->affiliate_default_percentage = $data['affiliate_default_percentage'] ?? null;
            $this->affiliate_default_cpa = $data['affiliate_default_cpa'] ?? null;
            $this->affiliate_default_baseline = $data['affiliate_default_baseline'] ?? null;
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Geral')
                    ->description('Informações principais do cassino')
                    ->schema([
                        TextInput::make('software_name')
                            ->label('Nome do Cassino')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('software_description')
                            ->label('Descrição')
                            ->maxLength(500)
                            ->columnSpanFull(),
                        FileUpload::make('software_logo_white')
                            ->label('Logo (branca)')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Moeda & Formatação')
                    ->schema([
                        TextInput::make('prefix')
                            ->label('Prefixo da Moeda')
                            ->required()
                            ->default('R$')
                            ->maxLength(10),
                        TextInput::make('currency_code')
                            ->label('Código da Moeda')
                            ->required()
                            ->default('BRL')
                            ->maxLength(10),
                        Select::make('decimal_format')
                            ->label('Formato Decimal')
                            ->options(['dot' => 'Ponto (.)', 'comma' => 'Vírgula (,)'])
                            ->required(),
                        Select::make('currency_position')
                            ->label('Posição da Moeda')
                            ->options(['left' => 'Esquerda (R$ 10,00)', 'right' => 'Direita (10,00 R$)'])
                            ->required(),
                        Select::make('storage')
                            ->label('Armazenamento')
                            ->options(['local' => 'Local', 's3' => 'Amazon S3'])
                            ->required(),
                    ])->columns(3),

                Section::make('Depósitos')
                    ->schema([
                        TextInput::make('min_deposit')
                            ->label('Depósito Mínimo')
                            ->numeric()
                            ->prefix('R$')
                            ->required(),
                        TextInput::make('max_deposit')
                            ->label('Depósito Máximo')
                            ->numeric()
                            ->prefix('R$')
                            ->required(),
                    ])->columns(2),

                Section::make('Saques')
                    ->schema([
                        TextInput::make('min_saque')
                            ->label('Saque Mínimo')
                            ->numeric()
                            ->prefix('R$')
                            ->required(),
                        TextInput::make('max_saque')
                            ->label('Saque Máximo')
                            ->numeric()
                            ->prefix('R$')
                            ->required(),
                    ])->columns(2),

                Section::make('Bônus & Revenue')
                    ->schema([
                        TextInput::make('initial_bonus')
                            ->label('Bônus Inicial (%)')
                            ->suffix('%')
                            ->numeric()
                            ->helperText('Percentual de bônus no primeiro depósito'),
                        TextInput::make('ngr_percent')
                            ->label('NGR (%)')
                            ->suffix('%')
                            ->numeric()
                            ->helperText('Receita líquida de jogos (Gross Gaming Revenue)'),
                        Toggle::make('revshare_reverse')
                            ->label('Revshare Reverso')
                            ->helperText('Se ativado, o afiliado paga quando o indicado ganha'),
                    ])->columns(3),

                Section::make('Afiliados')
                    ->description('Configurações globais do programa de afiliados')
                    ->schema([
                        TextInput::make('affiliate_default_percentage')
                            ->label('Comissão Padrão')
                            ->suffix('%')
                            ->numeric()
                            ->helperText('Percentual padrão para novos afiliados'),
                        TextInput::make('affiliate_default_cpa')
                            ->label('CPA Padrão')
                            ->prefix('R$')
                            ->numeric()
                            ->helperText('Valor fixo por depósito qualificado'),
                        TextInput::make('affiliate_default_baseline')
                            ->label('Baseline')
                            ->prefix('R$')
                            ->numeric()
                            ->helperText('Depósito mínimo para liberar CPA'),
                    ])->columns(3),

                Section::make('Redes Sociais')
                    ->description('Links das redes sociais — os ícones são fixos no frontend')
                    ->schema([
                        TextInput::make('instagram')->label('Instagram (URL)')->placeholder('https://instagram.com/seu-perfil'),
                        TextInput::make('tiktok')->label('TikTok (URL)')->placeholder('https://tiktok.com/@seu-perfil'),
                        TextInput::make('whatsapp')->label('WhatsApp (URL)')->placeholder('https://wa.me/5511999999999'),
                        TextInput::make('discord')->label('Discord (URL)')->placeholder('https://discord.gg/seu-convite'),
                        TextInput::make('telegram')->label('Telegram (URL)')->placeholder('https://t.me/seu-usuario'),
                        TextInput::make('twitter')->label('Twitter / X (URL)')->placeholder('https://x.com/seu-perfil'),
                    ])->columns(3),
            ]);
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $setting = Setting::first();

        foreach (['software_logo_white', 'software_logo_black', 'software_favicon'] as $field) {
            if (array_key_exists($field, $data)) {
                if (is_array($data[$field]) && ! empty($data[$field])) {
                    $data[$field] = $data[$field][0];
                } elseif (empty($data[$field])) {
                    $data[$field] = $setting->$field ?? null;
                }
            }
        }

        if (! $setting) {
            $setting = Setting::create();
        }

        $setting->update($data);
        $setting->flushCache();

        Notification::make()
            ->title('Configurações salvas com sucesso!')
            ->success()
            ->send();
    }

    public static function getNavigationLabel(): string
    {
        return 'Configurações';
    }

    protected static string|UnitEnum|null $navigationGroup = 'Configurações';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
}
