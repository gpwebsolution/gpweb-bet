<?php

namespace App\Filament\Resources\VipResource\Pages;

use App\Filament\Resources\VipResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVips extends ListRecords
{
    protected static string $resource = VipResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
