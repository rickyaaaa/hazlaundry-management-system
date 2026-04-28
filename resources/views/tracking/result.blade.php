<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Tracking {{ $transaction->tracking_code }} – HAZ Laundry</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<style>
/* Header nav styles retained */
.nav-link { color: #64748b; font-weight: 500; font-size: 14px; transition: color 0.2s; }
.nav-link:hover { color: #003366; }
.header-btn { background: #003366; color: white; padding: 8px 20px; border-radius: 8px; font-weight: 600; font-size: 13px; transition: background 0.2s; }
.header-btn:hover { background: #1e293b; }

/* Page Typography & Layout */
.tracking-title { font-size: 32px; font-weight: 700; color: #003366; margin-top: 48px; text-align: center; }
.tracking-subtitle { font-size: 15px; color: #64748b; text-align: center; margin-top: 12px; margin-bottom: 32px; }
.search-container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 6px; display: flex; align-items: center; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; }
.search-input { flex: 1; border: none; outline: none; padding: 12px 16px; font-size: 15px; background: transparent; }
.search-btn { background: #003366; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: background 0.2s; }

/* Cards */
.card-white { background: white; border-radius: 12px; padding: 32px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #f1f5f9; }
.alert-box { background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 12px; padding: 24px; display: flex; gap: 16px; align-items: flex-start; }
.alert-icon { width: 36px; height: 36px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #003366; }
.alert-title { font-size: 18px; font-weight: 600; color: #003366; margin-bottom: 6px; margin-top: 4px; }
.alert-desc { font-size: 14px; color: #64748b; line-height: 1.5; }

/* Live Tracking Stepper */
.stepper-container { position: relative; display: flex; justify-content: space-between; margin-top: 48px; margin-bottom: 16px; }
.stepper-line-bg { position: absolute; top: 18px; left: 5%; right: 5%; height: 3px; background: #e2e8f0; z-index: 1; }
.stepper-line-progress { position: absolute; top: 18px; left: 5%; height: 3px; background: #10b981; z-index: 2; transition: width 0.3s ease; }
.step-node { position: relative; z-index: 3; display: flex; flex-direction: column; align-items: center; gap: 12px; width: 80px; }
.step-circle { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #f1f5f9; color: #94a3b8; border: 3px solid white; }
.step-circle svg { width: 18px; height: 18px; }

.step-node.done .step-circle { background: #10b981; color: white; }
.step-node.active .step-circle { background: #003366; color: white; box-shadow: 0 0 0 4px #e2e8f0; border-color: transparent; }

.step-label { font-size: 11px; font-weight: 600; color: #94a3b8; text-align: center; }
.step-node.done .step-label { color: #10b981; }
.step-node.active .step-label { color: #003366; }

/* Bottom Grid Cards (Driver & Location) */
.driver-location-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 24px; }
.info-card { display: flex; align-items: center; gap: 16px; padding: 20px 24px; border: 1px solid #f1f5f9; border-radius: 12px; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
.info-icon { width: 44px; height: 44px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
.info-card:nth-child(1) .info-icon { background: #eff6ff; color: #2563eb; }
.info-card:nth-child(2) .info-icon { background: #eff6ff; color: #2563eb; }
.info-label { font-size: 11px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px; }
.info-value { font-size: 15px; font-weight: 700; color: #003366; }

/* Right Column Summary */
.summary-card { background: white; border-radius: 12px; border: 1px solid #f1f5f9; box-shadow: 0 2px 10px rgba(0,0,0,0.02); overflow: hidden; }
.summary-header { background: #003366; color: white; padding: 24px; position: relative; }
.summary-body { padding: 32px 24px; }

.summary-title { font-size: 20px; font-weight: 600; }
.summary-id { font-size: 14px; color: #94a3b8; margin-top: 6px; }
.badge-active { font-size: 10px; font-weight: 800; background: #10b981; color: white; padding: 4px 8px; border-radius: 4px; position: absolute; top: 24px; right: 24px; letter-spacing: 0.5px; }

.item-row { display: flex; justify-content: space-between; align-items: center; font-size: 14px; color: #475569; padding: 10px 0; }
.item-left { display: flex; align-items: center; gap: 10px; }
.item-val { font-weight: 600; color: #003366; }
.item-total { display: flex; justify-content: space-between; align-items: center; margin-top: 16px; padding-top: 24px; border-top: 1px solid #e2e8f0; font-size: 15px; font-weight: 700; color: #003366; }
.total-val { font-size: 20px; font-weight: 800; }

.est-box { background: #eff6ff; border-radius: 12px; padding: 20px; margin-top: 32px; }
.est-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.est-row:last-child { margin-bottom: 0; }
.est-label { font-size: 11px; font-weight: 600; color: #64748b; letter-spacing: 0.5px; text-transform: uppercase; }
.est-val { font-size: 13px; font-weight: 700; color: #003366; }
.badge-priority { background: #cbd5e1; color: #003366; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 800; letter-spacing: 0.5px; }

.btn-download { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 14px; border: 1.5px solid #003366; border-radius: 8px; color: #003366; font-weight: 600; font-size: 14px; background: transparent; cursor: pointer; transition: all 0.2s; margin-top: 24px; text-decoration: none; }
.btn-download:hover { background: #f8fafc; }

.help-card { background: white; border-radius: 12px; border: 1px solid #f1f5f9; padding: 24px; display: flex; align-items: center; gap: 16px; margin-top: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
.help-icon { width: 44px; height: 44px; border-radius: 50%; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.help-title { font-size: 15px; font-weight: 600; color: #003366; margin-bottom: 2px; }
.help-desc { font-size: 13px; color: #64748b; }
</style>
</head>
<body class="tracking-body" style="background:#f8fafc;">

<nav style="background: white; padding: 20px 40px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f5f9;">
    <a href="{{ route('tracking.index') }}" style="font-size: 18px; font-weight: 800; color: #003366; letter-spacing: 0.5px; text-decoration: none;">HAZ Laundry</a>
    <div style="display: flex; gap: 32px; align-items: center;">
        <a href="{{ route('tracking.index') }}" class="nav-link" style="color: #003366; font-weight: 600;">Tracking</a>
        <a href="{{ route('pricing.index') }}" class="nav-link">Pricing</a>
        <a href="{{ route('support.index') }}" class="nav-link">Support</a>
    </div>
    @auth <a href="{{ route('admin.dashboard') }}" class="header-btn">Dashboard</a> @else <a href="{{ route('login') }}" class="header-btn">Admin Login</a> @endauth
</nav>

<main style="flex: 1; padding: 0 24px 60px;">
    <h1 class="tracking-title">Track Your Laundry</h1>
    <p class="tracking-subtitle">Enter your details below to see exactly where your order is in our precision care process.</p>

    <form method="POST" action="{{ route('tracking.track') }}">
        @csrf
        <div class="search-container">
            <svg style="margin-left: 16px; width: 20px; height: 20px; stroke: #94a3b8; fill: none; stroke-width: 2;" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="tracking_code" placeholder="Order ID or Phone Number" class="search-input" value="{{ $transaction->tracking_code }}">
            <button type="submit" class="search-btn">Track Order</button>
        </div>
    </form>

    <div style="max-width: 1060px; margin: 48px auto 0; display: grid; grid-template-columns: 1.6fr 1fr; gap: 32px;">
        
        <!-- Left Column -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            @php
                $msgs = [
                    'Diproses'   => 'Your laundry is currently being processed by our team.',
                    'Dicuci'     => 'Your laundry is currently being washed using our premium detergents.',
                    'Dikeringkan'=> 'Your laundry is currently being dried carefully.',
                    'Disetrika'  => 'Your laundry is currently being ironed and will be ready soon! Our team is ensuring the highest precision for your garments.',
                    'Selesai'    => 'Your laundry is ready for pickup at our facility.',
                    'Diambil'    => 'Order has been picked up. Thank you for choosing HAZ Laundry!',
                ];
                $activeIdx = array_search($transaction->status, $statuses);
                if($activeIdx === false) $activeIdx = 0;
            @endphp

            <!-- Alert Card -->
            <div class="alert-box">
                <div class="alert-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                </div>
                <div>
                    <div class="alert-title">Your laundry is in progress</div>
                    <div class="alert-desc">{{ $msgs[$transaction->status] ?? 'Status pesanan Anda sedang diperbarui.' }}</div>
                </div>
            </div>

            <!-- Stepper Card -->
            <div class="card-white">
                <div style="font-size: 20px; font-weight: 600; color: #003366;">Live Tracking</div>
                
                <div class="stepper-container">
                    <div class="stepper-line-bg"></div>
                    <div class="stepper-line-progress" style="width: {{ ($activeIdx / (count($statuses)-1)) * 90 }}%;"></div>
                    
                    @foreach($statuses as $i => $s)
                    @php 
                        $done = $i < $activeIdx; 
                        $active = $i === $activeIdx;
                        
                        // Map statuses to english
                        $enLabel = [
                            'Diproses' => 'Order Received',
                            'Dicuci' => 'Washing',
                            'Dikeringkan' => 'Drying',
                            'Disetrika' => 'Ironing',
                            'Selesai' => 'Ready for Pickup',
                            'Diambil' => 'Completed'
                        ][$s] ?? $s;
                    @endphp
                    <div class="step-node {{ $done ? 'done' : '' }} {{ $active ? 'active' : '' }}">
                        <div class="step-circle">
                            @if($done)
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            @elseif($active)
                                <!-- Replace with iron icon for ironing, but we'll use a generic icon based on status or a dot -->
                                @if($s == 'Disetrika')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 14h14.5a2.5 2.5 0 0 0 0-5H10"/><path d="M4 14v4a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-4"/><path d="M4 14c-1.3 0-2-1-2-2v-2c0-1.7 1.3-3 3-3h12c2.2 0 4 1.8 4 4v3"/></svg>
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/></svg>
                                @endif
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            @endif
                        </div>
                        <div class="step-label">{!! str_replace(' ', '<br>', $enLabel) !!}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Driver & Location -->
            <div class="driver-location-grid">
                <div class="info-card">
                    <div class="info-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                    <div>
                        <div class="info-label">ASSIGNED DRIVER</div>
                        <div class="info-value">Robert Chen</div>
                    </div>
                </div>
                <div class="info-card">
                    <div class="info-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <div class="info-label">PICKUP LOCATION</div>
                        <div class="info-value">Downtown Facility</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column -->
        <div>
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-title">Order Summary</div>
                    <div class="summary-id">Order ID: #{{ $transaction->tracking_code }}</div>
                    @if(!in_array($transaction->status,['Diambil']))
                        <div class="badge-active">ACTIVE</div>
                    @else
                        <div class="badge-active" style="background:#64748b;">COMPLETED</div>
                    @endif
                </div>
                
                <div class="summary-body">
                    <div class="item-row">
                        <div class="item-left">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="16 8 10 14 8 12"/></svg>
                            <span>{{ $transaction->service->name ?? 'Layanan' }}</span>
                        </div>
                        <span class="item-val">Rp {{ number_format($transaction->price_per_kg,0,',','.') }}</span>
                    </div>
                    <div class="item-row">
                        <div class="item-left">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="16 8 10 14 8 12"/></svg>
                            <span>Weight ({{ $transaction->weight }} kg)</span>
                        </div>
                        <span class="item-val"></span>
                    </div>
                    
                    <div class="item-total">
                        <span>Total Amount</span>
                        <span class="total-val">Rp {{ number_format($transaction->total_price,0,',','.') }}</span>
                    </div>

                    <div class="est-box">
                        <div class="est-row">
                            <span class="est-label">EST. COMPLETION</span>
                            <span class="est-val">{{ $transaction->estimated_completion ? $transaction->estimated_completion->format('D, M d, h:i A') : 'TBD' }}</span>
                        </div>
                        <div class="est-row">
                            <span class="est-label">PRIORITY</span>
                            <span class="badge-priority">STANDARD</span>
                        </div>
                    </div>

                    <a href="{{ route('receipt.download', $transaction) }}" class="btn-download">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Download Receipt
                    </a>
                </div>
            </div>

            <div class="help-card">
                <div class="help-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </div>
                <div>
                    <div class="help-title">Need Help?</div>
                    <div class="help-desc">Chat with our precision team.</div>
                </div>
                <svg style="margin-left:auto; stroke:#cbd5e1;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
        </div>

    </div>
</main>

<footer style="padding: 32px 40px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #64748b; border-top: 1px solid #f1f5f9;">
    <span>© {{ date('Y') }} HAZ Laundry. Precision Laundry Logistics.</span>
    <div style="display: flex; gap: 24px;">
        <a href="#" class="nav-link" style="font-size: 13px;">Privacy Policy</a>
        <a href="#" class="nav-link" style="font-size: 13px;">Terms of Service</a>
        <a href="#" class="nav-link" style="font-size: 13px;">FAQ</a>
    </div>
</footer>

</body>
</html>

