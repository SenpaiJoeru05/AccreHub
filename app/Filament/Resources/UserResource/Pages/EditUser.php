<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;
use App\Models\ActivityLog;
use App\Models\Area;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected array $oldAreas = [];

    protected function beforeFill(): void
    {
        // Capture old areas before the form is filled
        $this->oldAreas = $this->record->areas()->pluck('id')->toArray();
        sort($this->oldAreas);
    }

    protected function afterSave(): void
    {
        $newAreas = $this->record->areas()->pluck('id')->toArray();
        sort($newAreas);

        if ($this->oldAreas !== $newAreas) {
            $user = auth()->user();

            $added = array_diff($newAreas, $this->oldAreas);
            $removed = array_diff($this->oldAreas, $newAreas);

            $addedNames = Area::whereIn('id', $added)->pluck('name')->toArray();
            $removedNames = Area::whereIn('id', $removed)->pluck('name')->toArray();

            $changes = [];
            if ($addedNames) {
                $changes[] = 'added areas: ' . implode(', ', $addedNames);
            }
            if ($removedNames) {
                $changes[] = 'removed areas: ' . implode(', ', $removedNames);
            }

            ActivityLog::create([
                'user_id' => $user?->id,
                'action' => 'updated_areas',
                'description' => $user?->name . ' updated assigned areas for user "' . $this->record->name . '" (' . $this->record->email . '): ' . implode('; ', $changes),
            ]);
        }
    }
}