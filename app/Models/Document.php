<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'path',
        'area_id',
        'parameter_id',
        'section_id',
        'subfolder_id',
        'year',
        'description',
        'uploaded_by',
    ];
    

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(Parameter::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function subfolder(): BelongsTo
    {
        return $this->belongsTo(Subfolder::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}