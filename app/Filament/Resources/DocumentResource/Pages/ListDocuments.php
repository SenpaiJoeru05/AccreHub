<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;
    protected static string $view = 'filament.resources.document-resource.pages.list-documents';

    public function render(): View
    {
        return view(static::$view, [
            'records' => $this->getTableRecords(),
        ]);
    }
}