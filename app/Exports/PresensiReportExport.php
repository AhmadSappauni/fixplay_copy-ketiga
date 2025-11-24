<?php

namespace App\Exports;

use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PresensiReportExport implements FromCollection, WithHeadings
{
    // public $fileName = 'laporan-presensi.xlsx';

    protected $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function collection()
    {
        return collect($this->rows);
    }

    public function headings(): array
    {
        return [
            'Karyawan',
            'Total Hari Kerja',
            'Jumlah Telat',
            'Perkiraan Hari Tidak Hadir',
        ];
    }
}
