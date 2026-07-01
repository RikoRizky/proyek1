<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Perti = 'perti';
    case UnitKerja = 'unit_kerja';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Perti => 'Perguruan Tinggi',
            self::UnitKerja => 'Program Studi',
        };
    }
}
