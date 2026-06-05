<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case AGENT = 'agent';
    case CLIENT = 'client';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrateur',
            self::AGENT => 'Agent Immobilier',
            self::CLIENT => 'Client',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}