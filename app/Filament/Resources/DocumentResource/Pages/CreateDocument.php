<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\User;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['uploaded_by'] = auth()->id();
        return $data;
    }

    protected function afterCreate(): void
    {
        // Notify all admins when a document is uploaded
        $admins = User::where('role', 'admin')->get();
        $user = auth()->user();
        $area = $this->record->area->name ?? 'an area';

        Notification::make()
            ->title('New Document Uploaded')
            ->body("{$user->name} uploaded a new document to {$area}.")
            ->icon('heroicon-o-document')
            ->color('info')
            ->sendToDatabase($admins);
    }
}