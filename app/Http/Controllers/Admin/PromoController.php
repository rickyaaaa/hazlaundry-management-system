<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::latest()->get();
        return view('admin.promos.index', compact('promos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.promos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'code'        => 'nullable|string|max:50|unique:promos,code',
            'percentage'  => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // 2MB Max
        ]);

        $imagePath = 'images/promo-bg.png'; // default fallback path

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('promos', 'public');
        }

        Promo::create([
            'title'       => $request->title,
            'code'        => $request->code ? strtoupper($request->code) : null,
            'percentage'  => $request->percentage ?? 0,
            'description' => $request->description,
            'image_path'  => $imagePath,
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->route('admin.promos.index')
                         ->with('success', 'Promo baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promo $promo)
    {
        return view('admin.promos.edit', compact('promo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'code'        => 'nullable|string|max:50|unique:promos,code,' . $promo->id,
            'percentage'  => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // 2MB Max
        ]);

        $imagePath = $promo->image_path;

        if ($request->hasFile('image')) {
            // Delete old image if it is not the default placeholder
            if ($promo->image_path && $promo->image_path !== 'images/promo-bg.png') {
                Storage::disk('public')->delete($promo->image_path);
            }
            $imagePath = $request->file('image')->store('promos', 'public');
        }

        $promo->update([
            'title'       => $request->title,
            'code'        => $request->code ? strtoupper($request->code) : null,
            'percentage'  => $request->percentage ?? 0,
            'description' => $request->description,
            'image_path'  => $imagePath,
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->route('admin.promos.index')
                         ->with('success', 'Promo berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promo $promo)
    {
        // Delete image file from storage if it's a custom uploaded image
        if ($promo->image_path && $promo->image_path !== 'images/promo-bg.png') {
            Storage::disk('public')->delete($promo->image_path);
        }

        $promo->delete();

        return redirect()->route('admin.promos.index')
                         ->with('success', 'Promo berhasil dihapus.');
    }

    /**
     * Check a promo code and calculate the discount for a given subtotal.
     * Public endpoint used by the customer pickup form (no auth required).
     */
    public function checkPromo(Request $request)
    {
        $validated = $request->validate([
            'code'     => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $promo = Promo::where('is_active', true)
            ->whereNotNull('code')
            ->whereRaw('UPPER(code) = ?', [strtoupper($validated['code'])])
            ->first();

        if (! $promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak ditemukan atau sudah tidak aktif.',
            ], 404);
        }

        $subtotal   = (float) $validated['subtotal'];
        $percentage = (int) $promo->percentage;
        $discount   = round($subtotal * $percentage / 100);
        $total      = max($subtotal - $discount, 0);

        return response()->json([
            'success'    => true,
            'code'       => $promo->code,
            'percentage' => $percentage,
            'discount'   => $discount,
            'total'      => $total,
        ]);
    }

    /**
     * Broadcast the promo to all newsletter subscribers.
     */
    public function broadcast(Promo $promo)
    {
        $subscribers = \App\Models\Subscriber::all();

        if ($subscribers->isEmpty()) {
            return back()->with('error', 'Belum ada pelanggan yang berlangganan newsletter.');
        }

        $successCount = 0;
        $failCount = 0;

        foreach ($subscribers as $sub) {
            try {
                \Illuminate\Support\Facades\Mail::to($sub->email)->send(new \App\Mail\PromoMail($promo));
                $successCount++;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Gagal mengirim email broadcast ke {$sub->email}: " . $e->getMessage());
                $failCount++;
            }
        }

        if ($failCount > 0) {
            return redirect()->route('admin.promos.index')
                             ->with('success', "Promo berhasil dikirim ke {$successCount} email. Gagal dikirim ke {$failCount} email.");
        }

        return redirect()->route('admin.promos.index')
                         ->with('success', "Promo berhasil dibroadcast ke seluruh ({$successCount}) email pelanggan!");
    }
}
