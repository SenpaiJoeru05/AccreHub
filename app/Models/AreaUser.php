<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AreaUser extends Pivot
{
    protected $table = 'area_user';

    protected static function booted()
    {
        static::created(function ($pivot) {
            $user = auth()->user();
            $targetUser = \App\Models\User::find($pivot->user_id);
            $area = \App\Models\Area::find($pivot->area_id);

            \App\Models\ActivityLog::create([
                'user_id' => $user?->id,
                'action' => 'assigned_area',
                'description' => $user?->name . ' assigned area "' . $area?->name . '" to user "' . $targetUser?->name . '" (' . $targetUser?->email . ')',
            ]);
        });

        static::deleted(function ($pivot) {
            $user = auth()->user();
            $targetUser = \App\Models\User::find($pivot->user_id);
            $area = \App\Models\Area::find($pivot->area_id);

            \App\Models\ActivityLog::create([
                'user_id' => $user?->id,
                'action' => 'removed_area',
                'description' => $user?->name . ' removed area "' . $area?->name . '" from user "' . $targetUser?->name . '" (' . $targetUser?->email . ')',
            ]);
        });
    }
}