<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Parameter extends Model
{
    protected $fillable = ['area_id', 'name', 'slug', 'description'];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'parameter_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'parameter_id');
    }
    protected static function booted()
    {
        static::creating(function ($parameter) {
            $parameter->slug = Str::slug($parameter->name);
        });

        static::updating(function ($parameter) {
            $parameter->slug = Str::slug($parameter->name);
        });
    }
}