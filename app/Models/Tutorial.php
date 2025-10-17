<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tutorial extends Model
{
    protected $fillable = [
        'title',
        'description',
        'long_description',
        'level',
        'status',
        'category_id',
        'office_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }

    public function supportingMaterials()
    {
        return $this->morphToMany(Content::class, 'contentable');
    }
}
