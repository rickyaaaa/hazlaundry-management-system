<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\LaundryService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('service')->latest();

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('tracking_code', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Filter by payment_status
        if ($paymentStatus = $request->get('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        $transactions = $query->paginate(15)->withQueryString();
        $services     = LaundryService::active()->get();
        $statuses     = Transaction::STATUSES;

        return view('admin.transactions.index', compact(
            'transactions',
            'services',
            'statuses'
        ));
    }

    public function create()
    {
        $services = LaundryService::active()->get();
        return view('admin.transactions.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'phone_number'   => 'required|string|max:20',
            'service_id'     => 'required|exists:laundry_services,id',
            'weight'         => 'required|numeric|min:0.1',
            'notes'          => 'nullable|string|max:500',
            'payment_status' => 'required|in:lunas,belum_bayar',
        ]);

        $service      = LaundryService::findOrFail($validated['service_id']);
        $pricePerKg   = $service->price_per_kg;
        $totalPrice   = $pricePerKg * $validated['weight'];
        $trackingCode = Transaction::generateTrackingCode();

        $transaction = Transaction::create([
            'tracking_code'       => $trackingCode,
            'customer_name'       => $validated['customer_name'],
            'phone_number'        => $validated['phone_number'],
            'service_id'          => $validated['service_id'],
            'weight'              => $validated['weight'],
            'price_per_kg'        => $pricePerKg,
            'total_price'         => $totalPrice,
            'status'              => 'Diproses',
            'payment_status'      => $validated['payment_status'],
            'notes'               => $validated['notes'] ?? null,
            'estimated_completion' => now()->addDays(2),
        ]);

        // Log initial status
        TransactionStatus::create([
            'transaction_id' => $transaction->id,
            'status'         => 'Diproses',
            'notes'          => 'Transaksi dibuat',
            'changed_at'     => now(),
        ]);

        return redirect()
            ->route('admin.transactions.show', $transaction)
            ->with('success', "Transaksi {$trackingCode} berhasil dibuat!");
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['service', 'statusHistories']);
        $statuses = Transaction::STATUSES;
        return view('admin.transactions.show', compact('transaction', 'statuses'));
    }

    public function edit(Transaction $transaction)
    {
        $services = LaundryService::active()->get();
        $statuses = Transaction::STATUSES;
        return view('admin.transactions.edit', compact('transaction', 'services', 'statuses'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'phone_number'   => 'required|string|max:20',
            'service_id'     => 'required|exists:laundry_services,id',
            'weight'         => 'required|numeric|min:0.1',
            'notes'          => 'nullable|string|max:500',
            'payment_status' => 'required|in:lunas,belum_bayar',
        ]);

        $service    = LaundryService::findOrFail($validated['service_id']);
        $totalPrice = $service->price_per_kg * $validated['weight'];

        $transaction->update([
            'customer_name'  => $validated['customer_name'],
            'phone_number'   => $validated['phone_number'],
            'service_id'     => $validated['service_id'],
            'weight'         => $validated['weight'],
            'price_per_kg'   => $service->price_per_kg,
            'total_price'    => $totalPrice,
            'payment_status' => $validated['payment_status'],
            'notes'          => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('admin.transactions.show', $transaction)
            ->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', Transaction::STATUSES),
            'notes'  => 'nullable|string|max:255',
        ]);

        $transaction->update(['status' => $validated['status']]);

        TransactionStatus::create([
            'transaction_id' => $transaction->id,
            'status'         => $validated['status'],
            'notes'          => $validated['notes'] ?? null,
            'changed_at'     => now(),
        ]);

        return redirect()
            ->route('admin.transactions.show', $transaction)
            ->with('success', "Status diperbarui menjadi {$validated['status']}");
    }

    public function destroy(Transaction $transaction)
    {
        $code = $transaction->tracking_code;
        $transaction->delete();

        return redirect()
            ->route('admin.transactions.index')
            ->with('success', "Transaksi {$code} berhasil dihapus.");
    }

    /**
     * AJAX: Get price per kg for a given service
     */
    public function getServicePrice(Request $request)
    {
        $service = LaundryService::find($request->service_id);
        if (!$service) {
            return response()->json(['price_per_kg' => 0]);
        }
        return response()->json(['price_per_kg' => $service->price_per_kg]);
    }
}
