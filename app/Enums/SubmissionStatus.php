<?php

namespace App\Enums;

enum SubmissionStatus: string
{
    case Pending = 'pending';
    case Uploaded = 'uploaded';
    case UnderReview = 'under_review';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu unggah',
            self::Uploaded => 'Terunggah',
            self::UnderReview => 'Dalam peninjauan',
            self::Completed => 'Selesai dinilai',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'bg-slate-100 text-slate-700 ring-slate-500/15',
            self::Uploaded => 'bg-sky-50 text-sky-800 ring-sky-500/20',
            self::UnderReview => 'bg-amber-50 text-amber-900 ring-amber-500/25',
            self::Completed => 'bg-emerald-50 text-emerald-900 ring-emerald-500/20',
        };
    }
}
