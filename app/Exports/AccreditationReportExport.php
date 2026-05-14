<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AccreditationReportExport implements FromCollection, WithHeadings
{
    public function __construct(
        private readonly Collection $rows
    ) {}

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'Nama Unit Kerja',
            'Email',
            'Modul',
            'Persyaratan',
            'Status',
            'Versi Dokumen',
            'Skor (1-4)',
            'Asesor',
        ];
    }
}
