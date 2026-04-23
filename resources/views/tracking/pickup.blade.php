<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Antar Jemput – LuxeLaundry</title>
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
.form-container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; }
.form-group { margin-bottom: 20px; text-align: left; }
.form-label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #334155; }
.form-input { width: 100%; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; transition: border-color 0.2s; box-sizing: border-box; font-family: 'Inter', sans-serif;}
.form-input:focus { border-color: #003366; }
.submit-btn { width: 100%; background: #003366; color: white; border: none; padding: 14px; border-radius: 8px; font-weight: 600; font-size: 15px; cursor: pointer; transition: background 0.2s; margin-top: 10px; }
.submit-btn:hover { background: #1e293b; }
</style>
</head>
<body class="tracking-body" style="background: #f8fafc; min-height: 100vh; display: flex; flex-direction: column;">

<nav style="background: white; padding: 20px 40px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f5f9;">
    <a href="{{ route('tracking.index') }}" style="font-size: 18px; font-weight: 800; color: #003366; letter-spacing: 0.5px; text-decoration: none;">LUXELAUNDRY</a>
    <div style="display: flex; gap: 32px; align-items: center;">
        <a href="{{ route('tracking.index') }}" class="nav-link">Tracking</a>
        <a href="{{ route('tracking.pickup.form') }}" class="nav-link" style="color: #003366; font-weight: 600;">Antar Jemput</a>
        <a href="{{ route('pricing.index') }}" class="nav-link">Pricing</a>
        <a href="{{ route('support.index') }}" class="nav-link">Support</a>
    </div>
    <a href="{{ route('login') }}" class="header-btn">Admin Login</a>
</nav>

<main style="flex: 1; padding: 0 24px 60px;">
    <h1 class="tracking-title">Layanan Antar Jemput</h1>
    <p class="tracking-subtitle">Isi formulir di bawah ini dan kurir kami akan segera menjemput pakaian Anda.</p>

    @if($errors->any())
    <div style="max-width:600px;margin:0 auto 16px;background:#fef2f2;border:1px solid #fca5a5;border-radius:12px;padding:12px 20px;text-align:left;font-size:14px;color:#991b1b">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="form-container">
        <form method="POST" action="{{ route('tracking.pickup.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="customer_name">Nama Lengkap</label>
                <input type="text" id="customer_name" name="customer_name" class="form-input" value="{{ old('customer_name') }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="phone_number">Nomor WhatsApp</label>
                <input type="text" id="phone_number" name="phone_number" class="form-input" value="{{ old('phone_number') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="service_id">Pilih Layanan</label>
                <select id="service_id" name="service_id" class="form-input" required>
                    <option value="">-- Pilih Layanan --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }} (Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}/kg)
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="pickup_time">Waktu Penjemputan</label>
                <input type="datetime-local" id="pickup_time" name="pickup_time" class="form-input" value="{{ old('pickup_time') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="address">Alamat Lengkap</label>
                <textarea id="address" name="address" class="form-input" rows="3" required>{{ old('address') }}</textarea>
            </div>

            <button type="submit" class="submit-btn">Pesan Sekarang</button>
        </form>
    </div>
</main>

<footer style="padding: 32px 40px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #64748b; background: white; border-top: 1px solid #f1f5f9;">
    <span>© {{ date('Y') }} LuxeLaundry. Precision Laundry Logistics.</span>
    <div style="display: flex; gap: 24px;">
        <a href="#" class="nav-link" style="font-size: 13px;">Privacy Policy</a>
        <a href="#" class="nav-link" style="font-size: 13px;">Terms of Service</a>
        <a href="#" class="nav-link" style="font-size: 13px;">FAQ</a>
    </div>
</footer>

</body>
</html>
