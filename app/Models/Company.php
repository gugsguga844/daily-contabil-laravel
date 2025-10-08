<?php

namespace App\Models;

use App\Enums\TaxRegime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'fantasy_name',
        'cnpj',
        'tax_regime',
        'phone',
        'email',
        'street',
        'number',
        'city',
        'state',
        'zip_code',
        'is_active',
        'creator_id',
        'office_id',
        'accountant_id',
    ];

    protected $casts = [
        'tax_regime' => TaxRegime::class,
    ];

    protected function taxRegimeLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tax_regime?->label(),
        );
    }

    protected $appends = [
        'tax_regime_label',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function accountant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accountant_id');
    }

    public function contents()
    {
        return $this->morphToMany(Content::class, 'contentable');
    }
}
