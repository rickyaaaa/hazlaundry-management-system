<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('tracking.index');
    }

    public function track(Request $request)
    {
        $request->validate([
            'tracking_code' => 'required|string',
        ]);

        $trackingCode = strtoupper(trim($request->tracking_code));
        $transaction  = Transaction::with(['service', 'statusHistories'])
            ->where('tracking_code', $trackingCode)
            ->first();

        if (!$transaction) {
            return back()->withErrors([
                'tracking_code' => 'Kode tracking tidak ditemukan. Pastikan kode yang Anda masukkan benar.',
            ])->withInput();
        }

        $statuses    = Transaction::STATUSES;
        $statusIndex = array_search($transaction->status, $statuses);

        return view('tracking.result', compact(
            'transaction',
            'statuses',
            'statusIndex'
        ));
    }
}
