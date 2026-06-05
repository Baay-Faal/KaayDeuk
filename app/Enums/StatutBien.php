<?php

namespace App\Enums;

enum StatutBien: string
{
    case DISPONIBLE = 'disponible';
    case RESERVE = 'reserve';
    case VENDU = 'vendu';
    case LOUE = 'loue';
    case ARCHIVE = 'archive';

    public function label(): string
    {
        return match($this) {
            self::DISPONIBLE => 'Disponible',
            self::RESERVE => 'Réservé',
            self::VENDU => 'Vendu',
            self::LOUE => 'Loué',
            self::ARCHIVE => 'Archivé',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DISPONIBLE => 'green',
            self::RESERVE => 'orange',
            self::VENDU => 'blue',
            self::LOUE => 'purple',
            self::ARCHIVE => 'gray',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}