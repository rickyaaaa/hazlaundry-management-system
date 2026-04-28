@extends('layouts.admin')
@section('title','Detail Transaksi')
@section('content')
<div class="breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a><span class="breadcrumb-sep">›</span><a href="{{ route('admin.transactions.index') }}">Transaksi</a><span class="breadcrumb-sep">›</span><span>{{ $transaction->tracking_code }}</span></div>
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $transaction->tracking_code }}</h1>
        <p class="page-subtitle">Detail transaksi – {{ $transaction->customer_name }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('receipt.download',$transaction) }}" class="btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download Receipt
        </a>
        <a href="{{ route('admin.transactions.edit',$transaction) }}" class="btn-primary">Edit</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px">
    <!-- Left -->
    <div style="display:flex;flex-direction:column;gap:20px">
        <!-- Info card -->
        <div class="card">
            <div class="card-header"><span class="card-title">Informasi Pelanggan</span></div>
            <div class="card-body">
                <div class="grid-2">
                    <div><div class="form-label">Nama</div><div style="font-weight:600">{{ $transaction->customer_name }}</div></div>
                    <div><div class="form-label">Nomor HP</div><div style="font-weight:600">{{ $transaction->phone_number }}</div></div>
                    <div><div class="form-label">Layanan</div><div style="font-weight:600">{{ $transaction->service->name ?? '-' }}</div></div>
                    <div><div class="form-label">Berat</div><div style="font-weight:600">{{ $transaction->weight }} kg</div></div>
                    <div><div class="form-label">Harga/kg</div><div style="font-weight:600">Rp {{ number_format($transaction->price_per_kg,0,',','.') }}</div></div>
                    <div><div class="form-label">Total Harga</div><div style="font-weight:700;font-size:18px;color:var(--primary)">Rp {{ number_format($transaction->total_price,0,',','.') }}</div></div>
                    <div><div class="form-label">Status Bayar</div><span class="badge badge-{{ $transaction->payment_status }}">{{ $transaction->payment_status=='lunas'?'Lunas':'Belum Bayar' }}</span></div>
                    <div><div class="form-label">Tanggal Masuk</div><div>{{ $transaction->created_at->format('d M Y, H:i') }}</div></div>
                    <div><div class="form-label">Tipe Pengiriman</div><div style="font-weight:600">{{ $transaction->delivery_type == 'pickup_delivery' ? 'Antar Jemput' : 'Drop Off' }}</div></div>
                    @if($transaction->delivery_type == 'pickup_delivery')
                    <div style="grid-column: span 2;"><div class="form-label">Alamat</div><div style="font-weight:600">{{ $transaction->address }}</div></div>
                    @endif
                </div>
                @if($transaction->notes)
                <div style="margin-top:16px;padding:12px;background:var(--bg);border-radius:8px;font-size:13px">
                    <span style="font-weight:600;color:var(--text-2)">Catatan:</span> {{ $transaction->notes }}
                </div>
                @endif
            </div>
        </div>

        <!-- Status tracker -->
        <div class="card">
            <div class="card-header"><span class="card-title">Progress Laundry</span></div>
            <div class="card-body">
                <div class="stepper">
                    @foreach($statuses as $i => $s)
                    @php $idx = array_search($transaction->status, $statuses); $done=$i<$idx; $active=$i==$idx; @endphp
                    <div class="step {{ $done?'done':'' }} {{ $active?'active':'' }}">
                        <div class="step-dot">
                            @if($done)
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            @elseif($active)
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3" fill="white"/></svg>
                            @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><circle cx="12" cy="12" r="3"/></svg>
                            @endif
                        </div>
                        <div class="step-label">{{ $s }}</div>
                    </div>
                    @endforeach
                </div>

                <!-- Update status form -->
                <div style="background:var(--bg);border-radius:10px;padding:16px;margin-top:16px">
                    <div style="font-size:13px;font-weight:600;margin-bottom:12px">Update Status</div>
                    <form method="POST" action="{{ route('admin.transactions.updateStatus',$transaction) }}">
                        @csrf
                        <div style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap">
                            <div style="flex:1;min-width:150px">
                                <select name="status" class="form-control">
                                    @foreach($statuses as $s)
                                    <option value="{{ $s }}" {{ $transaction->status==$s?'selected':'' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="flex:2;min-width:150px">
                                <input type="text" name="notes" class="form-control" placeholder="Catatan (opsional)">
                            </div>
                            <button type="submit" class="btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- History -->
        <div class="card">
            <div class="card-header"><span class="card-title">Riwayat Status</span></div>
            <div class="card-body" style="padding-top:0">
                @forelse($transaction->statusHistories as $h)
                @php $sl=strtolower(str_replace(' ','',$h->status)); @endphp
                <div style="display:flex;align-items:flex-start;gap:12px;padding:12px 0;border-bottom:1px solid var(--border)">
                    <div style="width:8px;height:8px;border-radius:50%;background:var(--primary);margin-top:5px;flex-shrink:0"></div>
                    <div style="flex:1">
                        <span class="badge badge-{{ $sl }}">{{ $h->status }}</span>
                        @if($h->notes)<span style="font-size:12px;color:var(--text-2);margin-left:8px">{{ $h->notes }}</span>@endif
                    </div>
                    <div style="font-size:12px;color:var(--text-3);white-space:nowrap">{{ $h->changed_at->format('d M, H:i') }}</div>
                </div>
                @empty
                <p class="text-muted text-sm" style="padding:16px 0">Belum ada riwayat</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right: Order summary -->
    <div>
        <div class="order-summary-card">
            <div class="summary-label">Order Summary</div>
            <div class="summary-title" style="font-size:14px;word-break:break-all">{{ $transaction->tracking_code }}</div>
            <div class="order-summary-row"><span>Layanan</span><span>{{ $transaction->service->name ?? '-' }}</span></div>
            <div class="order-summary-row"><span>Berat</span><span>{{ $transaction->weight }} kg</span></div>
            <div class="order-summary-row"><span>Rate</span><span>Rp {{ number_format($transaction->price_per_kg,0,',','.') }}/kg</span></div>
            <div style="padding-top:12px;border-top:1px solid rgba(255,255,255,.2);margin-top:4px">
                <div style="font-size:11px;opacity:.7;margin-bottom:4px">Total Amount</div>
                <div class="order-summary-total">Rp {{ number_format($transaction->total_price,0,',','.') }}</div>
            </div>
            @if($transaction->estimated_completion)
            <div style="margin-top:16px;background:rgba(255,255,255,.1);padding:10px;border-radius:8px;font-size:12px">
                <div style="opacity:.7">EST. COMPLETION</div>
                <div style="font-weight:600;margin-top:2px">{{ $transaction->estimated_completion->format('d M Y') }}</div>
            </div>
            @endif
        </div>

        <div style="margin-top:12px">
            <a href="{{ route('receipt.download',$transaction) }}" class="btn-secondary w-full" style="justify-content:center;margin-top:8px">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download Receipt
            </a>
        </div>
    </div>
</div>
@endsection
