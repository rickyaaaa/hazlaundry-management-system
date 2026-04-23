<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Track Your Laundry – LuxeLaundry</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<style>
/* Custom styles matching the provided design */
.nav-link { color: #64748b; font-weight: 500; font-size: 14px; transition: color 0.2s; }
.nav-link:hover { color: #003366; }
.header-btn { background: #003366; color: white; padding: 8px 20px; border-radius: 8px; font-weight: 600; font-size: 13px; transition: background 0.2s; }
.header-btn:hover { background: #1e293b; }
.tracking-title { font-size: 32px; font-weight: 700; color: #003366; margin-top: 48px; text-align: center; }
.tracking-subtitle { font-size: 15px; color: #64748b; text-align: center; margin-top: 12px; margin-bottom: 32px; }
.search-container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 6px; display: flex; align-items: center; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
.search-input { flex: 1; border: none; outline: none; padding: 12px 16px; font-size: 15px; background: transparent; }
.search-btn { background: #003366; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: background 0.2s; }
.search-btn:hover { background: #1e293b; }
.how-card { background: white; border-radius: 16px; padding: 32px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; }
</style>
</head>
<body class="tracking-body">

<nav style="background: white; padding: 20px 40px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f5f9;">
    <a href="{{ route('tracking.index') }}" style="font-size: 18px; font-weight: 800; color: #003366; letter-spacing: 0.5px; text-decoration: none;">LUXELAUNDRY</a>
    <div style="display: flex; gap: 32px; align-items: center;">
        <a href="{{ route('tracking.index') }}" class="nav-link" style="color: #003366; font-weight: 600;">Tracking</a>
        <a href="{{ route('pricing.index') }}" class="nav-link">Pricing</a>
        <a href="{{ route('support.index') }}" class="nav-link">Support</a>
    </div>
    <a href="{{ route('login') }}" class="header-btn">Admin Login</a>
</nav>

<main style="flex: 1; padding: 0 24px;">
    <h1 class="tracking-title">Track Your Laundry</h1>
    <p class="tracking-subtitle">Enter your details below to see exactly where your order is in our precision care process.</p>

    @if($errors->any())
    <div style="max-width:600px;margin:0 auto 16px;background:#fef2f2;border:1px solid #fca5a5;border-radius:12px;padding:12px 20px;text-align:left;font-size:14px;color:#991b1b">
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('tracking.track') }}">
        @csrf
        <div class="search-container">
            <svg style="margin-left: 12px; width: 20px; height: 20px; stroke: #94a3b8; fill: none; stroke-width: 2;" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="tracking_code" placeholder="Order ID or Phone Number" class="search-input" value="{{ old('tracking_code') }}">
            <button type="submit" class="search-btn">Track Order</button>
        </div>
    </form>

    <div style="max-width: 900px; margin: 60px auto 0; display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
        <div class="how-card">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: #f1f5f9; color: #003366; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; margin: 0 auto 16px;">1</div>
            <div style="font-size: 15px; font-weight: 600; color: #003366;">Masukkan Kode</div>
        </div>
        <div class="how-card">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: #f1f5f9; color: #003366; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; margin: 0 auto 16px;">2</div>
            <div style="font-size: 15px; font-weight: 600; color: #003366;">Pantau Proses</div>
        </div>
        <div class="how-card">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: #f1f5f9; color: #003366; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; margin: 0 auto 16px;">3</div>
            <div style="font-size: 15px; font-weight: 600; color: #003366;">Selesai & Ambil</div>
        </div>
    </div>
</main>

<footer style="padding: 32px 40px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #64748b;">
    <span>© {{ date('Y') }} LuxeLaundry. Precision Laundry Logistics.</span>
    <div style="display: flex; gap: 24px;">
        <a href="#" class="nav-link" style="font-size: 13px;">Privacy Policy</a>
        <a href="#" class="nav-link" style="font-size: 13px;">Terms of Service</a>
        <a href="#" class="nav-link" style="font-size: 13px;">FAQ</a>
    </div>
</footer>

</body>
</html>
