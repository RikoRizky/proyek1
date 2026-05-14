<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Asesor = 'asesor';
    case UnitKerja = 'unit_kerja';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Asesor => 'Asesor',
            self::UnitKerja => 'Unit Kerja',
        };
    }
}
