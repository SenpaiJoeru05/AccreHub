<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
    ];

    // Relationship to User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
