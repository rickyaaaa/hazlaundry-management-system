<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\LaundryService;
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
        ]);

        $trackingCode = Transaction::generateTrackingCode();

        $transaction = Transaction::create([
            'tracking_code'  => $trackingCode,
            'customer_name'  => $request->customer_name,
            'phone_number'   => $request->phone_number,
            'address'        => $request->address,
            'pickup_time'    => $request->pickup_time,
            'service_id'     => $request->service_id,
            'delivery_type'  => 'pickup_delivery',
            'status'         => 'Menunggu Jemputan',
            'payment_status' => 'belum_bayar',
            'weight'         => 0,
            'price_per_kg'   => 0,
            'total_price'    => 0,
        ]);

        $transaction->statusHistories()->create([
            'status'     => 'Menunggu Jemputan',
            'changed_at' => now(),
        ]);

        // Kirim Notifikasi Telegram ke Admin
        $botToken = config('services.telegram.bot_token');
        $chatId   = config('services.telegram.chat_id');

        if ($botToken && $chatId) {
            $transaction->load('service');
            $serviceName = $transaction->service ? $transaction->service->name : 'N/A';
            $pickupTime  = $transaction->pickup_time ? $transaction->pickup_time->format('d-m-Y H:i') : '-';

            $message = "🔔 *Permintaan Antar-Jemput Baru*\n\n"
                     . "📄 *Kode Tracking:* `{$transaction->tracking_code}`\n"
                     . "👤 *Pelanggan:* {$transaction->customer_name}\n"
                     . "📞 *No. HP:* {$transaction->phone_number}\n"
                     . "📍 *Alamat:* {$transaction->address}\n"
                     . "⏰ *Waktu Jemput:* {$pickupTime}\n"
                     . "🧺 *Layanan:* {$serviceName}\n"
                     . "🔄 *Status:* {$transaction->status}";

            try {
                \Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                    'chat_id'    => $chatId,
                    'text'       => $message,
                    'parse_mode' => 'Markdown',
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Gagal mengirim notifikasi Telegram: " . $e->getMessage());
            }
        }

        return redirect()->route('tracking.index')
                         ->with('success', "Permintaan antar jemput berhasil dibuat. Kode Tracking Anda: {$trackingCode}. Tim kami akan segera menghubungi Anda.");
    }
}
