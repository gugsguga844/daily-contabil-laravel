<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Models\Office;
use App\Models\User;

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
