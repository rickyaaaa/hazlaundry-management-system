@extends('layouts.admin')
@section('title','Dashboard')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Selamat datang kembali, {{ Auth::user()->name }}!</p>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon stat-icon-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <span class="stat-badge stat-badge-up">+12% ↑</span>
        </div>
        <div class="stat-label">Total Orders</div>
        <div class="stat-value">{{ number_format($totalOrders) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon stat-icon-orange">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <span class="stat-badge stat-badge-neutral">Active Now</span>
        </div>
        <div class="stat-label">Orders In Process</div>
        <div class="stat-value">{{ number_format($inProcess) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon stat-icon-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <span class="stat-badge stat-badge-up">98% Success</span>
        </div>
        <div class="stat-label">Completed Orders</div>
        <div class="stat-value">{{ number_format($completed) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon stat-icon-purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
            </div>
            <span class="stat-badge stat-badge-up">+8.4% ↑</span>
        </div>
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value" style="font-size:24px">Rp {{ number_format($totalRevenue,0,',','.') }}</div>
    </div>
</div>

<!-- Recent Transactions + Notifications -->
<div style="display:grid;grid-template-columns:1fr 340px;gap:20px">
    <div class="card">
        <div class="card-header" style="padding-bottom:16px">
            <span class="card-title">Recent Transactions</span>
            <a href="{{ route('admin.transactions.index') }}" style="font-size:13px;color:var(--primary);font-weight:600">View All</a>
        </div>
        <div class="table-wrapper" style="border:none;border-radius:0;box-shadow:none">
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($recentTransactions as $t)
                <tr>
                    <td>
                        <div class="customer-cell">
                            @php $colors=['blue','green','orange','purple','red','pink']; $c=$colors[$loop->index%6]; @endphp
                            <div class="avatar avatar-{{$c}}">{{ strtoupper(substr($t->customer_name,0,2)) }}</div>
                            <div>
                                <div class="customer-name">{{ $t->customer_name }}</div>
                                <div style="font-size:11px;color:var(--text-3)">{{ $t->tracking_code }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $t->service->name ?? '-' }}</td>
                    <td>
                        @php $sl=strtolower(str_replace(' ','',($t->status??''))) @endphp
                        <span class="badge badge-{{$sl}}">{{ $t->status }}</span>
                    </td>
                    <td style="font-weight:600">Rp {{ number_format($t->total_price,0,',','.') }}</td>
                    <td style="color:var(--text-3);font-size:12px">{{ $t->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted" style="padding:32px">Belum ada transaksi</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Notifications Panel -->
    <div class="card">
        <div class="card-header" style="padding-bottom:16px">
            <span class="card-title">Status Overview</span>
        </div>
        <div class="card-body" style="padding-top:0">
            @foreach(\App\Models\Transaction::STATUSES as $s)
            @php $cnt=$statusCounts[$s]??0; $sl=strtolower(str_replace(' ','',$s)); @endphp
            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border)">
                <span class="badge badge-{{$sl}}">{{ $s }}</span>
                <span style="font-weight:700;color:var(--text)">{{ $cnt }}</span>
            </div>
            @endforeach

            <!-- Revenue chart -->
            <div style="margin-top:20px">
                <div style="font-size:12px;font-weight:600;color:var(--text-2);margin-bottom:12px">Revenue (12 bulan)</div>
                <div style="position:relative;height:180px;width:100%">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const monthlyData = @json($monthlyRevenue);
const labels = [];
const data = [];
const monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
for(let m=1;m<=12;m++){
    labels.push(monthNames[m-1]);
    const found = monthlyData.find(d=>d.month==m);
    data.push(found ? parseFloat(found.revenue) : 0);
}
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: { labels, datasets: [{ data, backgroundColor: '#003366', borderRadius: 6, borderSkipped: false }] },
    options: { plugins:{legend:{display:false}}, scales:{x:{grid:{display:false},ticks:{font:{size:10}}},y:{display:false}}, maintainAspectRatio:false }
});
</script>
@endpush
