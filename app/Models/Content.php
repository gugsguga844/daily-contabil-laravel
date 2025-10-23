<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Content extends Model
{
    protected $fillable = [
        'title',
        'type',
        'path_or_content',
        'office_id',
        'uploader_id',
        'size_bytes',
    ];

    protected $appends = ['full_url'];

    protected function fullUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::disk('s3')->url($this->path_or_content),
        );
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function companies()
    {
        return $this->morphedByMany(Company::class, 'contentable');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}
