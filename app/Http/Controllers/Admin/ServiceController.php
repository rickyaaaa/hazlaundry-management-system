<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaundryService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = LaundryService::withCount('transactions')->latest()->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100|unique:laundry_services,name',
            'description'  => 'nullable|string|max:255',
            'price_per_kg' => 'required|numeric|min:100',
            'is_active'    => 'boolean',
        ]);

        LaundryService::create($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function edit(LaundryService $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, LaundryService $service)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100|unique:laundry_services,name,' . $service->id,
            'description'  => 'nullable|string|max:255',
            'price_per_kg' => 'required|numeric|min:100',
            'is_active'    => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $service->update($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroy(LaundryService $service)
    {
        if ($service->transactions()->count() > 0) {
            return back()->with('error', 'Layanan tidak dapat dihapus karena memiliki transaksi terkait.');
        }
        $service->delete();
        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}
