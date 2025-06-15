<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\ActivityLog;
use Filament\Notifications\Actions\Action;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
   
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = bcrypt($data['password']);
        return $data;
    }

    protected function afterCreate(): void
    {
    
        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_user',
            'description' => 'Created user "' . $this->record->name . '" (' . $this->record->email . ') as a ' . $this->record->role . '.',
        ]);

        // Notify all admins
        $creator = auth()->user()->name ?? 'Someone';
        $admins = \App\Models\User::where('role', 'admin')->get();
        \Filament\Notifications\Notification::make()
            ->title('New User Added')
            ->body("{$creator} created user {$this->record->name} ({$this->record->email}) as a {$this->record->role}.")
            ->color('info')
            ->actions([
                Action::make('markAsRead')
                    ->label('Mark as read')
                    ->markAsRead(), // This will mark the notification as read when clicked
            ])
            ->sendToDatabase($admins);
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    }
