<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Section extends Model
{
    protected $fillable = ['parameter_id', 'name', 'slug', 'description'];

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(Parameter::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'section_id');
    }

    public function subfolders(): HasMany
    {
        return $this->hasMany(Subfolder::class, 'section_id');
    }
    protected static function booted()
    {
        static::creating(function ($section) {
            $section->slug = Str::slug($section->name);
        });

        static::updating(function ($section) {
            $section->slug = Str::slug($section->name);
        });
    }
}