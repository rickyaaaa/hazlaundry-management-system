<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\LaundryService;
use App\Models\Promo;
use App\Mail\StatusLaundryMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('service')->latest();

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('tracking_code', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter by payment_status
        if ($paymentStatus = $request->input('payment_status')) {
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
            'customer_name'            => 'required|string|max:255',
            'phone_number'             => 'required|string|max:20',
            'services'                 => 'required|array|min:1',
            'services.*.service_id'    => 'required|exists:laundry_services,id',
            'services.*.weight'        => 'required|integer|min:1',
            'promo_code'               => 'nullable|string|max:50',
            'notes'                    => 'nullable|string|max:500',
            'payment_status'           => 'required|in:lunas,belum_bayar',
            'delivery_type'            => 'required|in:drop_off,pickup_delivery',
            'address'                  => 'nullable|required_if:delivery_type,pickup_delivery|string|max:1000',
            'email'                    => 'required|email|max:255',
        ]);

        // Hitung subtotal per baris layanan berdasarkan harga resmi di database
        // (bukan dari input klien) supaya harga tidak bisa dimanipulasi.
        $serviceLines = [];
        $subtotal     = 0;

        foreach ($validated['services'] as $line) {
            $service  = LaundryService::findOrFail($line['service_id']);
            $qty      = (int) $line['weight'];
            $lineTotal = $service->price_per_kg * $qty;

            $serviceLines[] = [
                'service'   => $service,
                'quantity'  => $qty,
                'unit_price' => $service->price_per_kg,
                'subtotal'  => $lineTotal,
            ];

            $subtotal += $lineTotal;
        }

        // Validasi & hitung ulang diskon promo di server (jangan percaya nilai dari klien)
        $promoUsed      = null;
        $discountAmount = 0;

        if ($request->filled('promo_code')) {
            $promo = Promo::where('is_active', true)
                ->whereNotNull('code')
                ->whereRaw('UPPER(code) = ?', [strtoupper($validated['promo_code'])])
                ->first();

            if ($promo) {
                $promoUsed      = $promo->code;
                $discountAmount = round($subtotal * $promo->percentage / 100);
            }
        }

        $totalPrice   = max($subtotal - $discountAmount, 0);
        $totalWeight  = array_sum(array_column($serviceLines, 'quantity'));
        $trackingCode = Transaction::generateTrackingCode();
        $firstService = $serviceLines[0]['service'];

        $transaction = DB::transaction(function () use (
            $validated, $trackingCode, $firstService, $totalWeight,
            $totalPrice, $promoUsed, $discountAmount, $serviceLines
        ) {
            $transaction = Transaction::create([
                'tracking_code'        => $trackingCode,
                'delivery_type'        => $validated['delivery_type'],
                'customer_name'        => $validated['customer_name'],
                'phone_number'         => $validated['phone_number'],
                'address'              => $validated['address'] ?? null,
                'service_id'           => $firstService->id,
                'weight'               => $totalWeight,
                'price_per_kg'         => $firstService->price_per_kg,
                'total_price'          => $totalPrice,
                'promo_used'           => $promoUsed,
                'discount_amount'      => $discountAmount,
                'status'               => 'Diproses',
                'payment_status'       => $validated['payment_status'],
                'notes'                => $validated['notes'] ?? null,
                'estimated_completion' => now()->addDays(2),
                'email'                => $validated['email'],
            ]);

            foreach ($serviceLines as $line) {
                $transaction->laundryServices()->attach($line['service']->id, [
                    'quantity'     => $line['quantity'],
                    'price_per_kg' => $line['unit_price'],
                    'subtotal'     => $line['subtotal'],
                ]);
            }

            // Log initial status
            TransactionStatus::create([
                'transaction_id' => $transaction->id,
                'status'         => 'Diproses',
                'notes'          => 'Transaksi dibuat',
                'changed_at'     => now(),
            ]);

            return $transaction;
        });

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
            'weight'         => 'required|integer|min:1',
            'price_per_kg'   => 'required|numeric|min:0',
            'notes'          => 'nullable|string|max:500',
            'payment_status' => 'required|in:lunas,belum_bayar',
            'delivery_type'  => 'required|in:drop_off,pickup_delivery',
            'address'        => 'nullable|required_if:delivery_type,pickup_delivery|string|max:1000',
            'email'          => 'required|email|max:255',
        ]);

        $pricePerKg = $validated['price_per_kg'];
        $totalPrice = $pricePerKg * $validated['weight'];

        $transaction->update([
            'customer_name'  => $validated['customer_name'],
            'phone_number'   => $validated['phone_number'],
            'delivery_type'  => $validated['delivery_type'],
            'address'        => $validated['address'] ?? null,
            'service_id'     => $validated['service_id'],
            'weight'         => $validated['weight'],
            'price_per_kg'   => $pricePerKg,
            'total_price'    => $totalPrice,
            'payment_status' => $validated['payment_status'],
            'notes'          => $validated['notes'] ?? null,
            'email'          => $validated['email'],
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

        // Kirim email jika status diubah ke Proses Pengantaran atau Selesai
        if (in_array($validated['status'], ['Proses Pengantaran', 'Selesai'])) {
            $this->sendMailNotification($transaction);
        }

        // Auto transition to 'Proses Pengantaran' if delivery type is pickup_delivery
        if ($validated['status'] === 'Selesai' && $transaction->delivery_type === 'pickup_delivery') {
            $transaction->update(['status' => 'Proses Pengantaran']);
            
            TransactionStatus::create([
                'transaction_id' => $transaction->id,
                'status'         => 'Proses Pengantaran',
                'notes'          => 'Otomatis beralih ke pengantaran',
                'changed_at'     => now(),
            ]);

            // Kirim email untuk auto transition
            $this->sendMailNotification($transaction);
        }

        return redirect()
            ->route('admin.transactions.show', $transaction)
            ->with('success', "Status diperbarui menjadi {$transaction->status}");
    }

    /**
     * Kirim email pembaruan status transaksi ke pelanggan
     */
    private function sendMailNotification(Transaction $transaction): void
    {
        if ($transaction->email) {
            try {
                Mail::to($transaction->email)->send(new StatusLaundryMail($transaction));
            } catch (\Exception $e) {
                Log::error("Gagal mengirim email status laundry ke pelanggan: " . $e->getMessage());
            }
        }
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
