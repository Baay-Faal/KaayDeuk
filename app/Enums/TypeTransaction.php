<?php

namespace App\Enums;

enum TypeTransaction: string
{
    case VENTE = 'vente';
    case LOCATION = 'location';

    public function label(): string
    {
        return match($this) {
            self::VENTE => 'Vente',
            self::LOCATION => 'Location',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}