<?php

namespace App\Observers;

use App\Models\User;
use App\Models\ActivityLog;

class UserObserver
{
    public function created(User $user): void
    {
      
    }

    public function updated(User $user): void
    {
        $changes = [];
        $oldData = [
            'name' => $user->getOriginal('name'),
            'email' => $user->getOriginal('email'),
            'role' => $user->getOriginal('role'),
        ];

        foreach ($oldData as $field => $oldValue) {
            $newValue = $user->{$field};
            if ($oldValue != $newValue) {
                $changes[] = ucfirst($field) . ' changed from "' . $oldValue . '" to "' . $newValue . '"';
            }
        }

        if (!empty($changes)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'edited_user',
                'description' => 'Edited user "' . $user->name . '" (' . $user->email . '): ' . implode('; ', $changes),
            ]);
        }
    }

    public function deleted(User $user): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted_user',
            'description' => 'Deleted user "' . $user->name . '" (' . $user->email . ')',
        ]);
    }

    public function restored(User $user): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'restored_user',
            'description' => 'Restored user "' . $user->name . '" (' . $user->email . ')',
        ]);
    }

    public function forceDeleted(User $user): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'force_deleted_user',
            'description' => 'Permanently deleted user "' . $user->name . '" (' . $user->email . ')',
        ]);
    }
}
