@extends('layouts.admin')
@section('title','Edit Transaksi')
@section('content')
<div class="breadcrumb"><a href="{{ route('admin.transactions.index') }}">Transaksi</a><span class="breadcrumb-sep">›</span><span>Edit {{ $transaction->tracking_code }}</span></div>
<div class="page-header">
    <div><h1 class="page-title">Edit Transaksi</h1><p class="page-subtitle">{{ $transaction->tracking_code }}</p></div>
</div>
<div style="max-width:720px">
<div class="card">
<div class="card-body">
<form method="POST" action="{{ route('admin.transactions.update',$transaction) }}">
    @csrf @method('PUT')
    @if($errors->any())<div class="flash flash-error" style="margin-bottom:16px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $errors->first() }}</div>@endif
    <div class="grid-2">
        <div class="form-group"><label class="form-label">Customer Name</label><input type="text" name="customer_name" class="form-control" value="{{ old('customer_name',$transaction->customer_name) }}" required></div>
        <div class="form-group"><label class="form-label">Phone Number</label><input type="text" name="phone_number" class="form-control" value="{{ old('phone_number',$transaction->phone_number) }}" required></div>
    </div>
    <div class="grid-2">
        <div class="form-group">
            <label class="form-label">Service Type</label>
            <select name="service_id" class="form-control" id="serviceSelect" required>
                @foreach($services as $s)
                <option value="{{ $s->id }}" data-price="{{ $s->price_per_kg }}" {{ old('service_id',$transaction->service_id)==$s->id?'selected':'' }}>
                    {{ $s->name }} – Rp {{ number_format($s->price_per_kg,0,',','.') }}/kg
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group"><label class="form-label">Weight (KG)</label><input type="number" name="weight" id="weightInput" class="form-control" value="{{ old('weight',$transaction->weight) }}" min="0.1" step="0.1" required></div>
    </div>
    <div class="form-group">
        <label class="form-label">Payment Status</label>
        <select name="payment_status" class="form-control">
            <option value="belum_bayar" {{ old('payment_status',$transaction->payment_status)=='belum_bayar'?'selected':'' }}>Belum Bayar</option>
            <option value="lunas" {{ old('payment_status',$transaction->payment_status)=='lunas'?'selected':'' }}>Lunas</option>
        </select>
    </div>
    <div class="form-group"><label class="form-label">Total Harga (auto)</label>
        <div style="padding:10px 14px;background:var(--bg);border-radius:8px;font-size:18px;font-weight:700;color:var(--primary)">Rp <span id="sumTotal">0</span></div>
    </div>
    <div class="form-group"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="3">{{ old('notes',$transaction->notes) }}</textarea></div>
    <div style="display:flex;justify-content:flex-end;gap:12px">
        <a href="{{ route('admin.transactions.show',$transaction) }}" class="btn-secondary">Batal</a>
        <button type="submit" class="btn-primary">Simpan Perubahan</button>
    </div>
</form>
</div>
</div>
</div>
@endsection
@push('scripts')
<script>
function calc(){
    const sel=document.getElementById('serviceSelect');
    const price=parseFloat(sel.options[sel.selectedIndex]?.dataset?.price||0);
    const w=parseFloat(document.getElementById('weightInput').value||0);
    document.getElementById('sumTotal').textContent=(price*w).toLocaleString('id');
}
document.getElementById('serviceSelect').addEventListener('change',calc);
document.getElementById('weightInput').addEventListener('input',calc);
calc();
</script>
@endpush
