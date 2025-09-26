<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
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
        'creator_id',
        'accountant_id',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function accountant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accountant_id');
    }
}
