<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\LaundryService;
use App\Exports\MonthlyReportExport;
use App\Exports\PromoReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type  = $request->input('type', 'monthly');   // daily | monthly
        $year  = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        // Monthly revenue (12 months of selected year)
        $monthlyRevenue = Transaction::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Daily revenue (days of selected month/year)
        $dailyRevenue = Transaction::select(
                DB::raw('DAY(created_at) as day'),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Status distribution
        $statusReport = Transaction::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        // Revenue by service
        $revenueByService = Transaction::select(
                'laundry_services.name',
                DB::raw('SUM(transactions.total_price) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->join('laundry_services', 'transactions.service_id', '=', 'laundry_services.id')
            ->groupBy('laundry_services.id', 'laundry_services.name')
            ->orderByDesc('revenue')
            ->get();

        // Summary stats
        $totalRevenue      = Transaction::where('payment_status', 'lunas')->sum('total_price');
        $totalOrders       = Transaction::count();
        $avgOrderValue     = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $totalCustomers    = Transaction::distinct('customer_name')->count('customer_name');

        // Recent transactions for the report table
        $recentTransactions = Transaction::with('service')
            ->latest()
            ->paginate(10);

        $years = range(now()->year - 2, now()->year);

        return view('admin.reports.index', compact(
            'monthlyRevenue',
            'dailyRevenue',
            'statusReport',
            'revenueByService',
            'totalRevenue',
            'totalOrders',
            'avgOrderValue',
            'totalCustomers',
            'recentTransactions',
            'type',
            'year',
            'month',
            'years'
        ));
    }

    public function exportPdf(Request $request)
    {
        $year  = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        $monthlyRevenue = Transaction::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $statusReport = Transaction::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $totalRevenue = Transaction::whereYear('created_at', $year)
            ->where('payment_status', 'lunas')
            ->sum('total_price');

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'monthlyRevenue',
            'statusReport',
            'totalRevenue',
            'year'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("laporan-laundry-{$year}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $year = (int) $request->input('year', now()->year);

        return Excel::download(new MonthlyReportExport($year), "laporan-bulanan-{$year}.xlsx");
    }

    public static function promoUsageQuery($year, $month)
    {
        return Transaction::select(
                'transactions.promo_used as code',
                'promos.percentage as percentage',
                DB::raw('COUNT(transactions.id) as total_used'),
                DB::raw('COALESCE(SUM(transactions.discount_amount), 0) as total_discount')
            )
            ->join('promos', 'promos.code', '=', 'transactions.promo_used')
            ->whereNotNull('transactions.promo_used')
            ->whereYear('transactions.created_at', $year)
            ->whereMonth('transactions.created_at', $month)
            ->groupBy('transactions.promo_used', 'promos.percentage')
            ->orderByDesc('total_used');
    }

    public function promoReport(Request $request)
    {
        $year  = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        $promoUsage = $this->promoUsageQuery($year, $month)->get();

        $totalPromoUsed  = $promoUsage->sum('total_used');
        $totalTransactions = Transaction::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();
        $mostUsedPromo = $promoUsage->first();

        $years = range(now()->year - 2, now()->year);

        return view('admin.reports.promo', compact(
            'promoUsage',
            'totalPromoUsed',
            'totalTransactions',
            'mostUsedPromo',
            'year',
            'month',
            'years'
        ));
    }

    public function exportPromoPdf(Request $request)
    {
        $year  = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        $promoUsage = $this->promoUsageQuery($year, $month)->get();

        $totalPromoUsed = $promoUsage->sum('total_used');

        $pdf = Pdf::loadView('admin.reports.promo-pdf', compact(
            'promoUsage',
            'totalPromoUsed',
            'year',
            'month'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("laporan-promo-{$year}-{$month}.pdf");
    }

    public function exportPromoExcel(Request $request)
    {
        $year  = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        return Excel::download(
            new PromoReportExport((int) $year, (int) $month),
            "laporan-promo-{$year}-{$month}.xlsx"
        );
    }
}
