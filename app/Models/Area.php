<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Area extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'area_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_area');
    }

    public function parameters(): HasMany
    {
        return $this->hasMany(Parameter::class, 'area_id');
    }
}