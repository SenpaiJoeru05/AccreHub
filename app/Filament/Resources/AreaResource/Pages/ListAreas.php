<?php

namespace App\Filament\Resources\AreaResource\Pages;

use App\Filament\Resources\AreaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAreas extends ListRecords
{
    protected static string $resource = AreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
