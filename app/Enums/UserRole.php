<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case UnitKerja = 'unit_kerja';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::UnitKerja => 'Unit Kerja',
        };
    }
}
