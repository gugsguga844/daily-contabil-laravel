<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'fantasy_name',
        'cnpj',
        'phone',
        'email',
        'street',
        'number',
        'city',
        'state',
        'zip_code',
        'is_active',
        'office_owner_id',
        'current_plan',
    ];

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function office_owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'office_owner_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
