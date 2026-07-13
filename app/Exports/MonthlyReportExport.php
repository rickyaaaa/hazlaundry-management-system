<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected const MONTHS = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    public function __construct(protected int $year)
    {
    }

    public function collection()
    {
        $rows = Transaction::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw("SUM(CASE WHEN payment_status = 'lunas' THEN total_price ELSE 0 END) as paid_revenue")
            )
            ->whereYear('created_at', $this->year)
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        return collect(range(1, 12))->map(function ($month) use ($rows) {
            $row = $rows->get($month);

            return (object) [
                'month'        => $month,
                'orders'       => $row->orders ?? 0,
                'revenue'      => $row->revenue ?? 0,
                'paid_revenue' => $row->paid_revenue ?? 0,
            ];
        });
    }

    public function headings(): array
    {
        return ['Bulan', 'Tahun', 'Jumlah Order', 'Total Pendapatan (Rp)', 'Pendapatan Lunas (Rp)'];
    }

    public function map($row): array
    {
        return [
            self::MONTHS[$row->month],
            $this->year,
            $row->orders,
            (float) $row->revenue,
            (float) $row->paid_revenue,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
