@extends('layouts.admin')
@section('title','Transactions')
@section('content')

<style>
/* Stats Grid */
.tx-stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 32px; }
.tx-stat-card { background: white; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; border: 1px solid #f1f5f9; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
.tx-stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.tx-stat-icon svg { width: 24px; height: 24px; }
.tx-stat-info { display: flex; flex-direction: column; }
.tx-stat-label { font-size: 11px; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px; }
.tx-stat-value { font-size: 24px; font-weight: 700; color: #0f172a; }

/* Table Section */
.tx-table-card { background: white; border-radius: 12px; padding: 24px; border: 1px solid #f1f5f9; box-shadow: 0 2px 10px rgba(0,0,0,0.02); margin-bottom: 32px; }
.tx-table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.tx-table-title { font-size: 18px; font-weight: 700; color: #0f172a; }
.tx-table-actions { display: flex; gap: 12px; }
.btn-outline-gray { display: flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 13px; font-weight: 600; color: #475569; background: white; transition: all 0.2s; cursor: pointer; }
.btn-outline-gray:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a; }

.tx-table { width: 100%; border-collapse: collapse; }
.tx-table th { text-align: left; padding: 12px 16px; font-size: 11px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
.tx-table td { padding: 16px; font-size: 14px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
.tx-table tr:last-child td { border-bottom: none; }

.tx-id { color: #94a3b8; font-weight: 500; font-size: 13px; }
.tx-customer { display: flex; align-items: center; gap: 12px; }
.tx-avatar { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: #0f172a; }
.tx-customer-name { font-weight: 700; color: #0f172a; }

.badge-service { background: #e0e7ff; color: #3730a3; padding: 6px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-block; line-height: 1.2; text-align: center; }
.badge-status { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 50px; font-size: 10px; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase; }
.status-dot { width: 6px; height: 6px; border-radius: 50%; }

/* Custom status colors */
.bg-menunggujemputan { background: #fee2e2; color: #b91c1c; } .bg-menunggujemputan .status-dot { background: #b91c1c; }
.bg-prosespenjemputan { background: #ffedd5; color: #c2410c; } .bg-prosespenjemputan .status-dot { background: #c2410c; }
.bg-diproses { background: #eff6ff; color: #2563eb; } .bg-diproses .status-dot { background: #2563eb; }
.bg-dicuci { background: #faf5ff; color: #9333ea; } .bg-dicuci .status-dot { background: #9333ea; }
.bg-dikeringkan { background: #fffbeb; color: #d97706; } .bg-dikeringkan .status-dot { background: #d97706; }
.bg-disetrika { background: #fefce8; color: #ca8a04; } .bg-disetrika .status-dot { background: #ca8a04; }
.bg-selesai { background: #ecfdf5; color: #059669; } .bg-selesai .status-dot { background: #059669; }
.bg-prosespengantaran { background: #cffafe; color: #0369a1; } .bg-prosespengantaran .status-dot { background: #0369a1; }
.bg-diambil { background: #f8fafc; color: #475569; } .bg-diambil .status-dot { background: #475569; }

.badge-pay { padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase; display: inline-block; }
.pay-lunas { background: #ecfdf5; color: #059669; }
.pay-belum { background: #fef2f2; color: #dc2626; }

.tx-actions-btn { color: #94a3b8; background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; border-radius: 4px; transition: 0.2s; }
.tx-actions-btn:hover { background: #f1f5f9; color: #0f172a; }

/* Pagination row */
.tx-pagination-row { display: flex; justify-content: space-between; align-items: center; margin-top: 24px; font-size: 13px; color: #64748b; }
.tx-pagination-nav { display: flex; gap: 4px; }
.tx-page-btn { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 13px; font-weight: 500; color: #475569; background: white; cursor: pointer; }
.tx-page-btn:hover { background: #f8fafc; }
.tx-page-btn.active { background: #0f172a; color: white; border-color: #0f172a; }

/* Bottom Section */
.bottom-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
.banner-card { background: linear-gradient(135deg, #003366 0%, #175487 100%); border-radius: 16px; padding: 40px; position: relative; overflow: hidden; color: white; display: flex; flex-direction: column; justify-content: center; min-height: 280px; }
.banner-card::after { content: ''; position: absolute; right: 0; top: 0; bottom: 0; width: 50%; background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="10"/></svg>') repeat right center; background-size: cover; opacity: 0.5; pointer-events: none; }
.banner-title { font-size: 32px; font-weight: 700; margin-bottom: 12px; position: relative; z-index: 1; }
.banner-desc { font-size: 15px; color: rgba(255,255,255,0.8); max-width: 360px; line-height: 1.6; margin-bottom: 24px; position: relative; z-index: 1; }
.banner-btn { display: inline-block; background: #4ade80; color: #064e3b; padding: 12px 24px; border-radius: 50px; font-weight: 700; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase; border: none; cursor: pointer; transition: 0.2s; position: relative; z-index: 1; align-self: flex-start; text-decoration: none; }
.banner-btn:hover { background: #22c55e; }

.alerts-panel { background: #e0e7ff; border-radius: 16px; padding: 24px; display: flex; flex-direction: column; }
.alerts-header { display: flex; align-items: center; gap: 12px; font-size: 16px; font-weight: 600; color: #1e3a8a; margin-bottom: 20px; }
.alert-item { background: white; border-radius: 12px; padding: 16px; margin-bottom: 12px; display: flex; align-items: flex-start; gap: 12px; }
.alert-dot { width: 8px; height: 8px; border-radius: 50%; margin-top: 5px; flex-shrink: 0; }
.alert-title { font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
.alert-desc { font-size: 11px; color: #64748b; }
.alerts-link { font-size: 13px; font-weight: 600; color: #1e3a8a; margin-top: auto; display: flex; align-items: center; gap: 4px; text-decoration: none; }
.alerts-link:hover { text-decoration: underline; }
</style>

@php
    $activeOrders = \App\Models\Transaction::inProcess()->count();
    $processing = \App\Models\Transaction::whereIn('status', ['Dicuci', 'Dikeringkan', 'Disetrika'])->count();
    $completed = \App\Models\Transaction::where('status', 'Selesai')->count();
    $pendingPayAmount = \App\Models\Transaction::where('payment_status', 'belum_bayar')->sum('total_price');
    $pendingPayStr = 'Rp ' . number_format($pendingPayAmount / 1000000, 1) . 'M';
    if ($pendingPayAmount < 1000000) $pendingPayStr = 'Rp ' . number_format($pendingPayAmount / 1000, 1) . 'K';
    if ($pendingPayAmount == 0) $pendingPayStr = 'Rp 0';
@endphp

<!-- Stats -->
<div class="tx-stats-grid">
    <div class="tx-stat-card">
        <div class="tx-stat-icon" style="background:#f1f5f9; color:#475569;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><circle cx="12" cy="14" r="3"/></svg>
        </div>
        <div class="tx-stat-info">
            <div class="tx-stat-label">Active Orders</div>
            <div class="tx-stat-value">{{ number_format($activeOrders) }}</div>
        </div>
    </div>
    <div class="tx-stat-card">
        <div class="tx-stat-icon" style="background:#fff7ed; color:#ea580c;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="tx-stat-info">
            <div class="tx-stat-label">Processing</div>
            <div class="tx-stat-value">{{ number_format($processing) }}</div>
        </div>
    </div>
    <div class="tx-stat-card">
        <div class="tx-stat-icon" style="background:#ecfdf5; color:#059669;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="tx-stat-info">
            <div class="tx-stat-label">Completed</div>
            <div class="tx-stat-value">{{ number_format($completed) }}</div>
        </div>
    </div>
    <div class="tx-stat-card">
        <div class="tx-stat-icon" style="background:#f5f3ff; color:#7e22ce;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/><circle cx="12" cy="15" r="2"/></svg>
        </div>
        <div class="tx-stat-info">
            <div class="tx-stat-label">Pending Pay</div>
            <div class="tx-stat-value">{{ $pendingPayStr }}</div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="tx-table-card">
    <div class="tx-table-header">
        <div class="tx-table-title">Recent Transactions</div>
        <div class="tx-table-actions">
            <!-- Filter Dropdown Simulation -->
            <button class="btn-outline-gray" onclick="document.getElementById('filterForm').submit()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg>
                Filter
            </button>
            <a href="{{ route('admin.reports.index') }}" class="btn-outline-gray" style="text-decoration:none">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
            </a>
        </div>
        
        <!-- Hidden filter form -->
        <form id="filterForm" method="GET" style="display:none;">
            <input type="hidden" name="status" value="Diproses">
        </form>
    </div>

    <table class="tx-table">
        <thead>
            <tr>
                <th>ORDER ID</th>
                <th>CUSTOMER NAME</th>
                <th>SERVICE TYPE</th>
                <th>WEIGHT (KG)</th>
                <th>STATUS</th>
                <th>PAYMENT STATUS</th>
                <th>ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
            <tr>
                <td>
                    <div style="display:flex; flex-direction:column; gap:4px; align-items:flex-start;">
                        <span class="tx-id">#{{ $t->tracking_code }}</span>
                        @if($t->delivery_type == 'pickup_delivery')
                            <span style="padding:2px 6px; background:#fef08a; color:#854d0e; font-size:9px; border-radius:4px; font-weight:800;">PICKUP</span>
                        @else
                            <span style="padding:2px 6px; background:#e2e8f0; color:#475569; font-size:9px; border-radius:4px; font-weight:800;">WALK-IN</span>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="tx-customer">
                        @php 
                            $colors=['#e0e7ff','#fce7f3','#ffedd5','#dcfce7','#f3e8ff','#e2e8f0']; 
                            $bg = $colors[$loop->index % 6];
                        @endphp
                        <div class="tx-avatar" style="background: {{ $bg }}">{{ strtoupper(substr($t->customer_name,0,2)) }}</div>
                        <span class="tx-customer-name">{{ $t->customer_name }}</span>
                    </div>
                </td>
                <td>
                    <span class="badge-service">{{ str_replace(' ', "\n", $t->service->name ?? '-') }}</span>
                </td>
                <td style="font-weight: 600; color: #0f172a;">{{ number_format($t->weight, 1) }}</td>
                <td>
                    @php $sl = strtolower(str_replace(' ', '', $t->status)); @endphp
                    <span class="badge-status bg-{{ $sl }}">
                        <span class="status-dot"></span>
                        {{ $t->status }}
                    </span>
                </td>
                <td>
                    <span class="badge-pay {{ $t->payment_status == 'lunas' ? 'pay-lunas' : 'pay-belum' }}">
                        {{ $t->payment_status == 'lunas' ? 'LUNAS' : 'BELUM BAYAR' }}
                    </span>
                </td>
                <td>
                    <div style="position:relative">
                        <button class="tx-actions-btn" onclick="window.location.href='{{ route('admin.transactions.show', $t) }}'">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center; padding: 32px; color: #94a3b8;">Belum ada transaksi</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="tx-pagination-row">
        <span>Showing <strong>{{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }}</strong> of <strong>{{ number_format($transactions->total()) }}</strong> orders</span>
        <div class="tx-pagination-nav">
            @if ($transactions->onFirstPage())
                <span class="tx-page-btn" style="opacity:0.5">‹</span>
            @else
                <a href="{{ $transactions->previousPageUrl() }}" class="tx-page-btn" style="text-decoration:none">‹</a>
            @endif
            
            <span class="tx-page-btn active">{{ $transactions->currentPage() }}</span>
            
            @if ($transactions->hasMorePages())
                <a href="{{ $transactions->nextPageUrl() }}" class="tx-page-btn" style="text-decoration:none">›</a>
            @else
                <span class="tx-page-btn" style="opacity:0.5">›</span>
            @endif
        </div>
    </div>
</div>

<!-- Bottom Section -->
<div class="bottom-grid">
    <!-- Banner -->
    <div class="banner-card">
        <div class="banner-title">Facility Optimization</div>
        <div class="banner-desc">Manage machine workload and energy consumption directly from your operations hub.</div>
        <a href="{{ route('admin.reports.index') }}" class="banner-btn">VIEW ANALYTICS</a>
    </div>

    <!-- Alerts -->
    <div class="alerts-panel">
        <div class="alerts-header">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            System Alerts
        </div>
        
        <div class="alert-item">
            <div class="alert-dot" style="background: #dc2626;"></div>
            <div>
                <div class="alert-title">Machine #4 Overheating</div>
                <div class="alert-desc">Service required immediately</div>
            </div>
        </div>
        
        <div class="alert-item">
            <div class="alert-dot" style="background: #059669;"></div>
            <div>
                <div class="alert-title">Stock Replenished</div>
                <div class="alert-desc">Detergent supplies updated</div>
            </div>
        </div>

        <a href="#" class="alerts-link">
            View All Activity
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
    </div>
</div>

@endsection
