<?php

namespace App\Filament\Resources\RoletaRecompensaResource\Pages;

use App\Filament\Resources\RoletaRecompensaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoletaRecompensas extends ListRecords
{
    protected static string $resource = RoletaRecompensaResource::class;

    protected function getHeaderActions(): array { return [CreateAction::make(),]; }
}
