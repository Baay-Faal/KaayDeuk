<?php

namespace App\Enums;

enum TypeBien: string
{
    case APPARTEMENT = 'appartement';
    case VILLA = 'villa';
    case BUREAU = 'bureau';
    case TERRAIN = 'terrain';
    case COMMERCE = 'commerce';

    public function label(): string
    {
        return match($this) {
            self::APPARTEMENT => 'Appartement',
            self::VILLA => 'Villa',
            self::BUREAU => 'Bureau',
            self::TERRAIN => 'Terrain',
            self::COMMERCE => 'Commerce',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}