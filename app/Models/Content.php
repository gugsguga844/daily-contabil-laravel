<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = [
        'title',
        'type',
        'path_or_content',
    ];

    public function contentables()
    {
        return $this->morphedByMany(Contentable::class);
    }
}
