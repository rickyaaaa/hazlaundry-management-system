@extends('layouts.admin')
@section('title','Laporan Promo')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Laporan Penggunaan Promo</h1><p class="page-subtitle">Promo yang paling banyak digunakan pelanggan bulan ini</p></div>
    <div class="page-actions">
        <a href="{{ route('admin.reports.promo.exportPdf',['year'=>$year,'month'=>$month]) }}" class="btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export PDF
        </a>
        <form method="GET" style="display:flex;gap:8px">
            <select name="year" class="filter-select" onchange="this.form.submit()">
                @foreach($years as $y)<option value="{{ $y }}" {{ $y==$year?'selected':'' }}>{{ $y }}</option>@endforeach
            </select>
            <select name="month" class="filter-select" onchange="this.form.submit()">
                @foreach(range(1,12) as $m)<option value="{{ $m }}" {{ $m==$month?'selected':'' }}>{{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}</option>@endforeach
            </select>
        </form>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon stat-icon-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg></div></div>
        <div class="stat-label">Total Transaksi Pakai Promo</div>
        <div class="stat-value">{{ number_format($totalPromoUsed) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon stat-icon-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg></div></div>
        <div class="stat-label">Total Transaksi Bulan Ini</div>
        <div class="stat-value">{{ number_format($totalTransactions) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon stat-icon-orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div></div>
        <div class="stat-label">Promo Terpopuler</div>
        <div class="stat-value" style="font-size:20px">{{ $mostUsedPromo->code ?? '-' }}</div>
    </div>
</div>

<!-- Promo usage table -->
<div class="card">
    <div class="card-header" style="padding-bottom:12px"><span class="card-title">Penggunaan Promo</span><span style="font-size:12px;color:var(--text-3)">{{ \Carbon\Carbon::create()->month($month)->locale('id')->isoFormat('MMMM') }} {{ $year }}</span></div>
    <div class="table-wrapper" style="border:none;box-shadow:none;border-radius:0">
        <table>
            <thead><tr><th>Kode Promo</th><th>Potongan Diskon (%)</th><th>Jumlah Dipakai</th><th>Total Diskon</th></tr></thead>
            <tbody>
            @forelse($promoUsage as $p)
            <tr>
                <td style="font-weight:600;color:var(--text)">{{ $p->code }}</td>
                <td>{{ $p->percentage }}%</td>
                <td style="font-weight:700">{{ $p->total_used }}</td>
                <td style="font-weight:600">Rp {{ number_format($p->total_discount,0,',','.') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center" style="padding:24px;color:var(--text-3)">Belum ada promo yang digunakan bulan ini</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
