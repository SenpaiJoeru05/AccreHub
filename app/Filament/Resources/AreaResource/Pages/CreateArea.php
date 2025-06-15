<?php

namespace App\Filament\Resources\AreaResource\Pages;

use App\Filament\Resources\AreaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\User;

class CreateArea extends CreateRecord
{
    protected static string $resource = AreaResource::class;

    protected function afterCreate(): void
    {
        $admins = User::where('role', 'admin')->get();
        $faculty = User::where('role', 'faculty')->get();
        $area = $this->record;
        $creator = auth()->user()->name ?? 'Someone';
    
        // Notify all admins
        Notification::make()
            ->title('New Area Created')
            ->body("{$creator} created area \"{$area->name}\".")
            ->color('success')
            ->sendToDatabase($admins);
    
        // Notify all faculty
        Notification::make()
            ->title('New Area Available')
            ->body("{$creator} created a new area \"{$area->name}\".")
            ->color('info')
            ->sendToDatabase($faculty);
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
