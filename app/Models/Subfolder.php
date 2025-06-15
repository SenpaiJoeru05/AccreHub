<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Subfolder extends Model
{
    protected $fillable = ['section_id', 'parent_id', 'name', 'slug', 'description'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Subfolder::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Subfolder::class, 'parent_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'subfolder_id');
    }
    protected static function booted()
    {
        static::creating(function ($subfolder) {
            $subfolder->slug = Str::slug($subfolder->name);
        });

        static::updating(function ($subfolder) {
            $subfolder->slug = Str::slug($subfolder->name);
        });
    }
}