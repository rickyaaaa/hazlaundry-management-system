<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders     = Transaction::count();
        $inProcess       = Transaction::inProcess()->count();
        $completed       = Transaction::completed()->count();
        $totalRevenue    = Transaction::where('payment_status', 'lunas')->sum('total_price');

        // Recent transactions
        $recentTransactions = Transaction::with('service')
            ->latest()
            ->take(10)
            ->get();

        // Monthly revenue for chart (last 12 months)
        $monthlyRevenue = Transaction::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('COUNT(*) as total')
            )
            ->where('payment_status', 'lunas')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Status distribution
        $statusCounts = Transaction::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', compact(
            'totalOrders',
            'inProcess',
            'completed',
            'totalRevenue',
            'recentTransactions',
            'monthlyRevenue',
            'statusCounts'
        ));
    }
}
