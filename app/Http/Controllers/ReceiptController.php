<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function download(Transaction $transaction)
    {
        $transaction->load('service');

        $pdf = Pdf::loadView('receipts.pdf', compact('transaction'))
            ->setPaper('a5', 'portrait');

        return $pdf->download("receipt-{$transaction->tracking_code}.pdf");
    }
}
