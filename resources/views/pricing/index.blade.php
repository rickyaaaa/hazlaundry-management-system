<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Pricing – LuxeLaundry</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<style>
.nav-link { color: #64748b; font-weight: 500; font-size: 14px; transition: color 0.2s; }
.nav-link:hover { color: #003366; }
.header-btn { background: #003366; color: white; padding: 8px 20px; border-radius: 8px; font-weight: 600; font-size: 13px; transition: background 0.2s; }
.header-btn:hover { background: #1e293b; }
.pricing-title { font-size: 36px; font-weight: 800; color: #003366; margin-top: 48px; text-align: center; }
.pricing-subtitle { font-size: 16px; color: #64748b; text-align: center; margin-top: 12px; margin-bottom: 48px; }

.pricing-grid { max-width: 1000px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 32px; padding: 0 24px; }
.pricing-card { background: white; border-radius: 16px; padding: 40px 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; display: flex; flex-direction: column; transition: transform 0.2s; }
.pricing-card:hover { transform: translateY(-4px); box-shadow: 0 10px 40px rgba(0,0,0,0.08); }
.p-name { font-size: 20px; font-weight: 700; color: #003366; margin-bottom: 8px; }
.p-desc { font-size: 14px; color: #64748b; margin-bottom: 24px; line-height: 1.5; flex: 1; }
.p-price { font-size: 32px; font-weight: 800; color: #003366; margin-bottom: 24px; }
.p-price span { font-size: 15px; font-weight: 500; color: #64748b; }
.p-btn { display: block; text-align: center; background: #f8fafc; color: #003366; padding: 12px; border-radius: 8px; font-weight: 600; font-size: 14px; transition: all 0.2s; }
.p-btn:hover { background: #003366; color: white; }

.pricing-card.featured { border: 2px solid #003366; position: relative; }
.featured-badge { position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: #003366; color: white; padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; letter-spacing: 1px; }
</style>
</head>
<body class="tracking-body">

<nav style="background: white; padding: 20px 40px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f5f9;">
    <a href="{{ route('tracking.index') }}" style="font-size: 18px; font-weight: 800; color: #003366; letter-spacing: 0.5px; text-decoration: none;">LUXELAUNDRY</a>
    <div style="display: flex; gap: 32px; align-items: center;">
        <a href="{{ route('tracking.index') }}" class="nav-link">Tracking</a>
        <a href="{{ route('pricing.index') }}" class="nav-link" style="color: #003366; font-weight: 600;">Pricing</a>
        <a href="{{ route('support.index') }}" class="nav-link">Support</a>
    </div>
    <a href="{{ route('login') }}" class="header-btn">Admin Login</a>
</nav>

<main style="flex: 1; padding-bottom: 60px;">
    <h1 class="pricing-title">Simple, Transparent Pricing</h1>
    <p class="pricing-subtitle">Premium care for your garments without the premium price tag.</p>

    <div class="pricing-grid">
        @foreach($services as $i => $s)
        <div class="pricing-card {{ $i == 1 ? 'featured' : '' }}">
            @if($i == 1)
            <div class="featured-badge">MOST POPULAR</div>
            @endif
            <div class="p-name">{{ $s->name }}</div>
            <div class="p-desc">{{ $s->description ?? 'Layanan premium untuk kebersihan dan kerapian pakaian Anda.' }}</div>
            <div class="p-price">Rp {{ number_format($s->price_per_kg, 0, ',', '.') }}<span> /kg</span></div>
            <a href="{{ route('tracking.index') }}" class="p-btn {{ $i == 1 ? '' : '' }}" style="{{ $i == 1 ? 'background: #003366; color: white;' : '' }}">Track Order</a>
        </div>
        @endforeach
    </div>
</main>

<footer style="padding: 32px 40px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #64748b; border-top: 1px solid #f1f5f9; background: white;">
    <span>© {{ date('Y') }} LuxeLaundry. Precision Laundry Logistics.</span>
    <div style="display: flex; gap: 24px;">
        <a href="#" class="nav-link" style="font-size: 13px;">Privacy Policy</a>
        <a href="#" class="nav-link" style="font-size: 13px;">Terms of Service</a>
        <a href="#" class="nav-link" style="font-size: 13px;">FAQ</a>
    </div>
</footer>

</body>
</html>
