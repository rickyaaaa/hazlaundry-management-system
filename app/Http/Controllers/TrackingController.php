<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\LaundryService;
use App\Mail\StatusLaundryMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TrackingController extends Controller
{
    public function index()
    {
        $promos = \App\Models\Promo::where('is_active', true)->latest()->get();
        return view('tracking.index', compact('promos'));
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
        $promos      = \App\Models\Promo::where('is_active', true)->latest()->get();

        return view('tracking.result', compact(
            'transaction',
            'statuses',
            'statusIndex',
            'promos'
        ));
    }

    public function showPickupForm()
    {
        $services = LaundryService::where('is_active', true)->get();
        return view('tracking.pickup', compact('services'));
    }

    public function storePickup(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone_number'  => 'required|string|max:20',
            'address'       => 'required|string',
            'pickup_time'   => 'required|date|after:now',
            'service_id'    => 'required|exists:laundry_services,id',
            'weight'        => 'required|numeric|min:0.1',
            'email'         => 'required|email|max:255',
            'wants_promo'   => 'nullable',
            'promo_code'    => 'nullable|string|max:50',
        ]);

        $trackingCode = Transaction::generateTrackingCode();

        $service    = LaundryService::findOrFail($request->service_id);
        $weight     = (float) $request->weight;
        $pricePerKg = (float) $service->price_per_kg;
        $subtotal   = $pricePerKg * $weight;

        $promoUsed      = null;
        $discountAmount = 0;

        if ($request->filled('promo_code')) {
            $promo = \App\Models\Promo::where('is_active', true)
                ->whereNotNull('code')
                ->whereRaw('UPPER(code) = ?', [strtoupper($request->promo_code)])
                ->first();

            if ($promo) {
                $promoUsed      = $promo->code;
                $discountAmount = round($subtotal * $promo->percentage / 100);
            }
        }

        $totalPrice = max($subtotal - $discountAmount, 0);

        $transaction = Transaction::create([
            'tracking_code'   => $trackingCode,
            'customer_name'   => $request->customer_name,
            'phone_number'    => $request->phone_number,
            'address'         => $request->address,
            'pickup_time'     => $request->pickup_time,
            'service_id'      => $request->service_id,
            'delivery_type'   => 'pickup_delivery',
            'status'          => 'Menunggu Jemputan',
            'payment_status'  => 'belum_bayar',
            'weight'          => $weight,
            'price_per_kg'    => $pricePerKg,
            'total_price'     => $totalPrice,
            'promo_used'      => $promoUsed,
            'discount_amount' => $discountAmount,
            'email'           => $request->email,
        ]);

        $transaction->statusHistories()->create([
            'status'     => 'Menunggu Jemputan',
            'changed_at' => now(),
        ]);

        // Mendaftarkan subscriber jika mencentang checkbox promo
        if ($request->has('wants_promo')) {
            try {
                \App\Models\Subscriber::updateOrCreate(['email' => $request->email]);
                
                // Kirim email promo aktif terbaru jika ada
                $latestPromo = \App\Models\Promo::where('is_active', true)->latest()->first();
                if ($latestPromo) {
                    Mail::to($request->email)->send(new \App\Mail\PromoMail($latestPromo));
                }
            } catch (\Exception $e) {
                Log::error("Gagal mendaftarkan subscriber / mengirim email promo instan: " . $e->getMessage());
            }
        }

        // Kirim email konfirmasi pesanan baru ke pelanggan
        if ($transaction->email) {
            try {
                Mail::to($transaction->email)->send(new StatusLaundryMail($transaction));
            } catch (\Exception $e) {
                Log::error("Gagal mengirim email konfirmasi pesanan: " . $e->getMessage());
            }
        }

        return redirect()->route('tracking.index')
                         ->with('success', "Permintaan antar jemput berhasil dibuat. Kode Tracking Anda: {$trackingCode}. Tim kami akan segera menghubungi Anda.");
    }
}
