@extends('layouts.admin')
@section('title','Tambah Transaksi')
@section('content')
<div class="breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a><span class="breadcrumb-sep">›</span><a href="{{ route('admin.transactions.index') }}">Transaksi</a><span class="breadcrumb-sep">›</span><span>New Entry</span></div>
<div class="page-header">
    <div>
        <h1 class="page-title">New Laundry Transaction</h1>
        <p class="page-subtitle">Register a new incoming order into the system.</p>
    </div>
    <div style="background:#f0fdf4;border:1px solid #a7f3d0;border-radius:12px;padding:12px 20px;display:flex;align-items:center;gap:12px">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
        <div>
            <div style="font-size:10px;color:#065f46;font-weight:600;letter-spacing:.8px">CURRENT RATE</div>
            <div style="font-size:16px;font-weight:800;color:#065f46" id="currentRate">Rp 0 / kg</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start">
    <!-- Form -->
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.transactions.store') }}" id="txForm">
                @csrf
                @if($errors->any())
                <div class="flash flash-error" style="margin-bottom:16px">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    {{ $errors->first() }}
                </div>
                @endif

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Customer Name</label>
                        <div class="input-icon-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <input type="text" name="customer_name" class="form-control" placeholder="e.g. Michael Chen" value="{{ old('customer_name') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <div class="input-icon-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 9.81a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 0h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 7.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 14.92z"/></svg>
                            <input type="text" name="phone_number" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('phone_number') }}" required>
                        </div>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Service Type</label>
                        <select name="service_id" class="form-control" id="serviceSelect" required>
                            <option value="">-- Pilih Layanan --</option>
                            @foreach($services as $s)
                            <option value="{{ $s->id }}" data-price="{{ $s->price_per_kg }}" {{ old('service_id')==$s->id?'selected':'' }}>
                                {{ $s->name }} – Rp {{ number_format($s->price_per_kg,0,',','.') }}/kg
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Weight (KG)</label>
                        <div class="input-icon-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                            <input type="number" name="weight" id="weightInput" class="form-control" placeholder="0.0" min="0.1" step="0.1" value="{{ old('weight') }}" required>
                        </div>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-control" required>
                            <option value="belum_bayar" {{ old('payment_status')=='belum_bayar'?'selected':'' }}>Belum Bayar</option>
                            <option value="lunas" {{ old('payment_status')=='lunas'?'selected':'' }}>Lunas</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Special Instructions</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Add specific fabric care or delivery notes...">{{ old('notes') }}</textarea>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:8px">
                    <a href="{{ route('admin.transactions.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Create Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Order Summary Sidebar -->
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="order-summary-card">
            <div class="summary-label">Order Summary</div>
            <div class="summary-title">Kalkulasi Harga</div>
            <div class="order-summary-row"><span>Base Rate (per kg)</span><span id="sumRate">Rp 0</span></div>
            <div class="order-summary-row"><span>Berat</span><span id="sumWeight">0 kg</span></div>
            <div class="order-summary-row"><span>Total Harga</span></div>
            <div class="order-summary-total">
                <span>Rp </span><span id="sumTotal">0</span>
            </div>
        </div>

        <!-- Quick Check -->
        <div class="card">
            <div class="card-body">
                <div style="font-size:12px;font-weight:700;color:var(--text-2);letter-spacing:.8px;text-transform:uppercase;margin-bottom:12px">Quick Check</div>
                <div style="display:flex;flex-direction:column;gap:8px;font-size:13px;color:var(--text-2)">
                    <div style="display:flex;align-items:center;gap:8px"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Kantong diperiksa</div>
                    <div style="display:flex;align-items:center;gap:8px"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Barang mudah pecah dipisah</div>
                    <div style="display:flex;align-items:center;gap:8px;opacity:.4"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Pre-treatment applied</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function calcPrice() {
    const sel = document.getElementById('serviceSelect');
    const opt = sel.options[sel.selectedIndex];
    const price = parseFloat(opt?.dataset?.price || 0);
    const weight = parseFloat(document.getElementById('weightInput').value || 0);
    const total = price * weight;
    document.getElementById('currentRate').textContent = 'Rp ' + price.toLocaleString('id') + ' / kg';
    document.getElementById('sumRate').textContent = 'Rp ' + price.toLocaleString('id');
    document.getElementById('sumWeight').textContent = weight.toFixed(1) + ' kg';
    document.getElementById('sumTotal').textContent = total.toLocaleString('id');
}
document.getElementById('serviceSelect').addEventListener('change', calcPrice);
document.getElementById('weightInput').addEventListener('input', calcPrice);
calcPrice();
</script>
@endpush
