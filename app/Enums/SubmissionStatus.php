<?php

namespace App\Enums;

enum SubmissionStatus: string
{
    case Pending = 'pending';
    case Uploaded = 'uploaded';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu unggah',
            self::Uploaded => 'Terunggah',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'bg-slate-100 text-slate-700 ring-slate-500/15',
            self::Uploaded => 'bg-sky-50 text-sky-800 ring-sky-500/20',
        };
    }
}
