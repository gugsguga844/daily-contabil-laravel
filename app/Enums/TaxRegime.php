<?php

namespace App\Enums;

enum TaxRegime: string
{
    case SIMPLES_NACIONAL = 'simples_nacional';
    case LUCRO_PRESUMIDO = 'lucro_presumido';
    case LUCRO_REAL = 'lucro_real';

    public function label(): string
    {
        return match ($this) {
            self::SIMPLES_NACIONAL => 'Simples Nacional',
            self::LUCRO_PRESUMIDO => 'Lucro Presumido',
            self::LUCRO_REAL => 'Lucro Real',
        };
    }

    /**
     * Returns a comma-separated list of enum values for use in validation rules (e.g., 'in:'.TaxRegime::values()).
     */
    public static function values(): string
    {
        return implode(',', array_map(fn (self $c) => $c->value, self::cases()));
    }
}
