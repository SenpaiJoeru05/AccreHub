<?php

namespace App\Filament\Pages;

use App\Models\Area;
use App\Models\Document;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Notifications\Notification;
use App\Events\DocumentCreated;

class AreaDocuments extends Page
{
    protected static string $view = 'filament.pages.area-documents';

    public Area $area;

    public function mount(Area $area)
    {
        $this->area = $area;
    }

    public function getTitle(): string
    {
        return $this->area->name . ' Documents';
    }

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Action::make('createDocument')
    //             ->label('Upload New Document')
    //             ->icon('heroicon-o-plus')
    //             ->modalHeading('Upload New Document')
    //             ->form([
    //                 Forms\Components\TextInput::make('title')->required(),
    //                 Forms\Components\FileUpload::make('file')->required(),
    //                 Forms\Components\Select::make('parameter')
    //                     ->options([
    //                         'A' => 'Parameter A',
    //                         'B' => 'Parameter B',
    //                         'C' => 'Parameter C',
    //                         'D' => 'Parameter D',
    //                         'E' => 'Parameter E',
    //                     ])
    //                     ->required(),
    //                 Forms\Components\TextInput::make('year')->numeric()->required(),
    //                 Forms\Components\Select::make('type')
    //                     ->options([
    //                         'SAR' => 'SAR',
    //                         'Supporting Document' => 'Supporting Document',
    //                         'Other' => 'Other',
    //                     ])
    //                     ->required(),
    //                 Forms\Components\TextInput::make('section'),
    //                 Forms\Components\Textarea::make('description'),
    //             ])
    //             ->action(function (array $data) {
    //                 Document::create([
    //                     'title' => $data['title'],
    //                     'path' => $data['file'],
    //                     'area_id' => $this->area->id,
    //                     'parameter' => $data['parameter'],
    //                     'section' => $data['section'] ?? null,
    //                     'type' => $data['type'],
    //                     'year' => $data['year'],
    //                     'description' => $data['description'] ?? null,
    //                     'uploaded_by' => auth()->id(),
    //                 ]);
    //                       // Emit event for Livewire component
    //                 broadcast(new DocumentCreated($this->area->id))->toOthers();
    //                 Notification::make()->success()->title('Document uploaded!')->send();
    //             })
    //             ->after(function () {
    //                 $this->redirect($this->getUrl(['area' => $this->area->id]));
    //             })
    //     ];
    // }
}