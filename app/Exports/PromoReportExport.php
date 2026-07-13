<?php

namespace App\Exports;

use App\Http\Controllers\Admin\ReportController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PromoReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(protected int $year, protected int $month)
    {
    }

    public function collection()
    {
        return ReportController::promoUsageQuery($this->year, $this->month)->get();
    }

    public function headings(): array
    {
        return ['Kode Promo', 'Potongan Diskon (%)', 'Jumlah Dipakai', 'Total Diskon (Rp)'];
    }

    public function map($row): array
    {
        return [
            $row->code,
            $row->percentage,
            $row->total_used,
            (float) $row->total_discount,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
