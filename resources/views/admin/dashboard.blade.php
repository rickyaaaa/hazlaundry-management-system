@extends('layouts.admin')
@section('title','Dashboard')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Selamat datang kembali, {{ Auth::user()->name }}!</p>
    </div>
</div>

@if($activePromos->count() > 0)
<!-- Promo Banner Widget (CRM - Customer Retention) -->
<div class="card" style="margin-bottom: 20px; overflow: hidden; border: none; box-shadow: var(--shadow-sm); position: relative; height: 130px; border-radius: 12px; background: #003366;">
    <div class="admin-promo-carousel" style="width: 100%; height: 100%; position: relative;">
        @foreach($activePromos as $idx => $p)
            <div class="admin-promo-slide" data-slide-admin="{{ $idx }}" style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: space-between; transition: opacity 0.5s ease-in-out; {{ $idx === 0 ? 'opacity: 1; z-index: 5;' : 'opacity: 0; z-index: 1;' }}">
                <!-- Background image -->
                <div style="position: absolute; inset: 0; width: 100%; height: 100%; background: url('{{ $p->image_url }}') center/cover no-repeat; opacity: 0.25; filter: blur(2px);"></div>
                <div style="position: absolute; inset: 0; background: linear-gradient(90deg, #002244 30%, rgba(0,34,68,0.8) 70%, rgba(0,34,68,0.2) 100%);"></div>
                
                <!-- Content -->
                <div style="position: relative; z-index: 10; padding: 20px 24px; color: white; max-width: 70%; display: flex; flex-direction: column; gap: 4px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 9px; font-weight: 800; background: #f97316; color: white; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Promo CRM</span>
                        <span style="font-size: 11px; color: rgba(255,255,255,0.7);">Customer Retention Program</span>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 700; margin: 4px 0 2px; line-height: 1.2; font-family: 'Plus Jakarta Sans', sans-serif;">{{ $p->title }}</h3>
                    <p style="font-size: 11px; color: rgba(255,255,255,0.85); margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $p->description }}</p>
                </div>
                
                <!-- Graphic decoration on the right -->
                <div style="position: relative; z-index: 10; padding-right: 32px; display: flex; align-items: center; justify-content: flex-end; width: 30%; height: 100%;">
                    <div style="width: 80px; height: 80px; border-radius: 8px; overflow: hidden; border: 2px solid rgba(255,255,255,0.2); box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
                        <img src="{{ $p->image_url }}" alt="{{ $p->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
            </div>
        @endforeach
        
        <!-- Dot Navigation for dashboard carousel if > 1 -->
        @if($activePromos->count() > 1)
            <div style="position: absolute; bottom: 10px; left: 24px; z-index: 15; display: flex; gap: 4px;">
                @foreach($activePromos as $idx => $p)
                    <span class="admin-promo-dot" data-goto-admin="{{ $idx }}" style="width: 6px; height: 6px; border-radius: 50%; cursor: pointer; transition: all 0.3s; {{ $idx === 0 ? 'background: #f97316; width: 15px;' : 'background: rgba(255,255,255,0.4);' }}"></span>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slides = document.querySelectorAll('.admin-promo-slide');
        const dots = document.querySelectorAll('.admin-promo-dot');
        if (slides.length <= 1) return;

        let current = 0;
        const total = slides.length;
        const interval = 6000; // 6 seconds

        function show(index) {
            slides.forEach((slide, i) => {
                if (i === index) {
                    slide.style.opacity = '1';
                    slide.style.zIndex = '5';
                } else {
                    slide.style.opacity = '0';
                    slide.style.zIndex = '1';
                }
            });
            dots.forEach((dot, i) => {
                if (i === index) {
                    dot.style.background = '#f97316';
                    dot.style.width = '15px';
                } else {
                    dot.style.background = 'rgba(255,255,255,0.4)';
                    dot.style.width = '6px';
                }
            });
            current = index;
        }

        function next() {
            show((current + 1) % total);
        }

        let slideTimer = setInterval(next, interval);

        dots.forEach(dot => {
            dot.addEventListener('click', function () {
                clearInterval(slideTimer);
                const target = parseInt(this.getAttribute('data-goto-admin'));
                show(target);
                slideTimer = setInterval(next, interval);
            });
        });
    });
</script>
@endif

<!-- Stats -->
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))">
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
    <div class="stat-card" style="border-left: 4px solid #f97316;">
        <div class="stat-header">
            <div class="stat-icon" style="background:#fff7ed;display:flex;align-items:center;justify-content:center;border-radius:10px;width:40px;height:40px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" style="width:20px;height:20px;"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
            </div>
            <span class="stat-badge" style="background:#ffedd5;color:#c2410c">Pickup</span>
        </div>
        <div class="stat-label">Menunggu Jemputan</div>
        <div class="stat-value" style="color: #c2410c;">{{ number_format($pendingPickups) }}</div>
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
