<?php

namespace App\Enums;

enum StatutVisite: string
{
    case PLANIFIEE = 'planifiee';
    case REALISEE = 'realisee';
    case ANNULEE = 'annulee';

    public function label(): string
    {
        return match($this) {
            self::PLANIFIEE => 'Planifiée',
            self::REALISEE => 'Réalisée',
            self::ANNULEE => 'Annulée',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}