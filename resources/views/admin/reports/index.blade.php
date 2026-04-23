@extends('layouts.admin')
@section('title','Laporan')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Revenue Analytics</h1><p class="page-subtitle">Comprehensive financial performance overview</p></div>
    <div class="page-actions">
        <a href="{{ route('admin.reports.exportPdf',['year'=>$year,'month'=>$month]) }}" class="btn-secondary">
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
        <div class="stat-header"><div class="stat-icon stat-icon-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg></div><span class="stat-badge stat-badge-up">+12.5%</span></div>
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value" style="font-size:20px">Rp {{ number_format($totalRevenue,0,',','.') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon stat-icon-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg></div><span class="stat-badge stat-badge-up">+8.2%</span></div>
        <div class="stat-label">Total Orders</div>
        <div class="stat-value">{{ number_format($totalOrders) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon stat-icon-orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div><span class="stat-badge stat-badge-down">-2.4%</span></div>
        <div class="stat-label">Avg. Order Value</div>
        <div class="stat-value" style="font-size:22px">Rp {{ number_format($avgOrderValue,0,',','.') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon stat-icon-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div><span class="stat-badge stat-badge-up">+18%</span></div>
        <div class="stat-label">Total Customers</div>
        <div class="stat-value">{{ number_format($totalCustomers) }}</div>
    </div>
</div>

<!-- Charts row -->
<div style="display:grid;grid-template-columns:1fr 280px;gap:20px;margin-bottom:24px">
    <!-- Monthly Revenue Chart -->
    <div class="card">
        <div class="card-header">
            <div><div class="card-title">Revenue Growth</div><div style="font-size:12px;color:var(--text-3)">Monthly comparison – {{ $year }}</div></div>
        </div>
        <div class="card-body"><canvas id="monthlyChart" height="200"></canvas></div>
    </div>
    <!-- Revenue by Service -->
    <div class="card">
        <div class="card-header"><span class="card-title">Revenue by Service</span></div>
        <div class="card-body" style="padding-top:8px">
            @php $maxRev = $revenueByService->max('revenue') ?: 1; @endphp
            @foreach($revenueByService->take(5) as $rs)
            @php $pct = round(($rs->revenue/$maxRev)*100); $colors=['#003366','#10b981','#f97316','#8b5cf6','#ef4444']; $color=$colors[$loop->index%5]; @endphp
            <div style="margin-bottom:14px">
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:5px">
                    <span style="font-weight:500;color:var(--text)">{{ Str::limit($rs->name,20) }}</span>
                    <span style="font-weight:600;color:var(--text-2)">{{ $rs->orders }} orders</span>
                </div>
                <div style="height:6px;background:var(--bg);border-radius:50px;overflow:hidden">
                    <div style="width:{{ $pct }}%;height:100%;background:{{ $color }};border-radius:50px"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Status Report + Recent Transactions -->
<div style="display:grid;grid-template-columns:220px 1fr;gap:20px">
    <!-- Status counts -->
    <div class="card">
        <div class="card-header"><span class="card-title">Status</span></div>
        <div class="card-body" style="padding-top:0">
            @foreach($statusReport as $s)
            @php $sl=strtolower(str_replace(' ','',$s->status)); @endphp
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--border)">
                <span class="badge badge-{{ $sl }}" style="font-size:10px">{{ $s->status }}</span>
                <span style="font-weight:700">{{ $s->total }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent transactions table -->
    <div class="card">
        <div class="card-header" style="padding-bottom:12px"><span class="card-title">Recent Transactions</span><span style="font-size:12px;color:var(--text-3)">Live feed of revenue-generating activities</span></div>
        <div class="table-wrapper" style="border:none;box-shadow:none;border-radius:0">
            <table>
                <thead><tr><th>Transaction ID</th><th>Customer</th><th>Service Type</th><th>Date & Time</th><th>Amount</th><th>Status</th></tr></thead>
                <tbody>
                @forelse($recentTransactions as $t)
                @php $sl=strtolower(str_replace(' ','',$t->status)); $colors=['blue','green','orange','purple','red','pink']; $c=$colors[$loop->index%6]; @endphp
                <tr>
                    <td><span style="font-weight:600;color:var(--primary)">#{{ $t->tracking_code }}</span></td>
                    <td>
                        <div class="customer-cell">
                            <div class="avatar avatar-{{ $c }}" style="width:28px;height:28px;font-size:10px">{{ strtoupper(substr($t->customer_name,0,2)) }}</div>
                            {{ $t->customer_name }}
                        </div>
                    </td>
                    <td><span style="font-size:11px;background:var(--bg);padding:2px 8px;border-radius:6px;font-weight:600">{{ strtoupper(Str::limit($t->service->name??'',12)) }}</span></td>
                    <td style="font-size:12px;color:var(--text-2)">{{ $t->created_at->format('d M Y • H:i') }}</td>
                    <td style="font-weight:600">Rp {{ number_format($t->total_price,0,',','.') }}</td>
                    <td><span class="badge badge-{{ $sl }}">{{ strtoupper($t->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center" style="padding:24px;color:var(--text-3)">Belum ada transaksi</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="pagination-wrapper">
                <span>Showing {{ $recentTransactions->firstItem()??0 }} to {{ $recentTransactions->lastItem()??0 }} of {{ $recentTransactions->total() }} transactions</span>
                <div>{{ $recentTransactions->links('vendor.pagination.simple') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const mData = @json($monthlyRevenue);
const mLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
const mValues = [];
for(let i=1;i<=12;i++){
    const f = mData.find ? [...Object.values(mData)].find(d=>d.month==i) : null;
    mValues.push(f ? parseFloat(f.revenue) : 0);
}
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: { labels: mLabels, datasets: [{ label: 'Revenue', data: mValues, backgroundColor: '#003366', borderRadius: 6, borderSkipped: false }] },
    options: { plugins:{legend:{display:false}}, scales:{x:{grid:{display:false}},y:{ticks:{callback:v=>'Rp '+v.toLocaleString('id')},grid:{color:'#f0f2f5'}}}, maintainAspectRatio:false, responsive:true }
});
</script>
@endpush
