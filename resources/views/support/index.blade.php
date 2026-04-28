<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Support – HAZ Laundry</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<style>
.nav-link { color: #64748b; font-weight: 500; font-size: 14px; transition: color 0.2s; }
.nav-link:hover { color: #003366; }
.header-btn { background: #003366; color: white; padding: 8px 20px; border-radius: 8px; font-weight: 600; font-size: 13px; transition: background 0.2s; }
.header-btn:hover { background: #1e293b; }
.support-title { font-size: 36px; font-weight: 800; color: #003366; margin-top: 48px; text-align: center; }
.support-subtitle { font-size: 16px; color: #64748b; text-align: center; margin-top: 12px; margin-bottom: 48px; }

.contact-grid { max-width: 900px; margin: 0 auto; display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; padding: 0 24px; }
.contact-card { background: white; border-radius: 16px; padding: 32px 24px; text-align: center; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
.c-icon { width: 48px; height: 48px; border-radius: 50%; background: #f0f4ff; color: #2563eb; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
.c-title { font-size: 16px; font-weight: 700; color: #003366; margin-bottom: 8px; }
.c-desc { font-size: 14px; color: #64748b; margin-bottom: 16px; }
.c-link { font-size: 14px; font-weight: 600; color: #2563eb; text-decoration: none; }
.c-link:hover { text-decoration: underline; }

.faq-section { max-width: 700px; margin: 60px auto; padding: 0 24px; }
.faq-title { font-size: 24px; font-weight: 800; color: #003366; margin-bottom: 24px; text-align: center; }
.faq-item { background: white; border-radius: 12px; padding: 20px 24px; margin-bottom: 16px; border: 1px solid #f1f5f9; }
.faq-q { font-size: 16px; font-weight: 700; color: #003366; margin-bottom: 8px; }
.faq-a { font-size: 14px; color: #64748b; line-height: 1.6; }
</style>
</head>
<body class="tracking-body">

<nav style="background: white; padding: 20px 40px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f5f9;">
    <a href="{{ route('tracking.index') }}" style="font-size: 18px; font-weight: 800; color: #003366; letter-spacing: 0.5px; text-decoration: none;">HAZ Laundry</a>
    <div style="display: flex; gap: 32px; align-items: center;">
        <a href="{{ route('tracking.index') }}" class="nav-link">Tracking</a>
        <a href="{{ route('tracking.pickup.form') }}" class="nav-link">Antar Jemput</a>
        <a href="{{ route('pricing.index') }}" class="nav-link">Pricing</a>
        <a href="{{ route('support.index') }}" class="nav-link" style="color: #003366; font-weight: 600;">Support</a>
    </div>
    @auth <a href="{{ route('admin.dashboard') }}" class="header-btn">Dashboard</a> @else <a href="{{ route('login') }}" class="header-btn">Admin Login</a> @endauth
</nav>

<main style="flex: 1; padding-bottom: 60px;">
    <h1 class="support-title">How can we help?</h1>
    <p class="support-subtitle">Get in touch with our precision team or check our FAQ below.</p>

    <div class="contact-grid">
        <div class="contact-card">
            <div class="c-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </div>
            <div class="c-title">Call Us</div>
            <div class="c-desc">Speak directly with our support team.</div>
            <a href="#" class="c-link">+62 812-3456-7890</a>
        </div>
        <div class="contact-card">
            <div class="c-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <div class="c-title">Email Us</div>
            <div class="c-desc">Send us an email for general queries.</div>
            <a href="#" class="c-link">support@HAZ Laundry.com</a>
        </div>
        <div class="contact-card">
            <div class="c-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <div class="c-title">Live Chat</div>
            <div class="c-desc">Chat with our bot or human agents.</div>
            <a href="#" class="c-link">Start Chat</a>
        </div>
    </div>

    <div class="faq-section">
        <h2 class="faq-title">Frequently Asked Questions</h2>
        <div class="faq-item">
            <div class="faq-q">Bagaimana cara melacak pesanan saya?</div>
            <div class="faq-a">Anda dapat memasukkan ID Pesanan (contoh: LDY-2026-XXXX) pada halaman Tracking kami untuk melihat status real-time dari cucian Anda.</div>
        </div>
        <div class="faq-item">
            <div class="faq-q">Berapa lama waktu proses pengerjaan?</div>
            <div class="faq-a">Estimasi waktu pengerjaan bergantung pada layanan yang Anda pilih. Untuk Express, selesai dalam 24 jam. Untuk Reguler, sekitar 2-3 hari.</div>
        </div>
        <div class="faq-item">
            <div class="faq-q">Apakah HAZ Laundry menerima layanan antar-jemput?</div>
            <div class="faq-a">Ya, kami melayani pick-up & delivery untuk wilayah tertentu. Silakan hubungi tim kami untuk jadwal pengantaran.</div>
        </div>
    </div>
</main>

<footer style="padding: 32px 40px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #64748b; border-top: 1px solid #f1f5f9; background: white;">
    <span>© {{ date('Y') }} HAZ Laundry. Precision Laundry Logistics.</span>
    <div style="display: flex; gap: 24px;">
        <a href="#" class="nav-link" style="font-size: 13px;">Privacy Policy</a>
        <a href="#" class="nav-link" style="font-size: 13px;">Terms of Service</a>
        <a href="#" class="nav-link" style="font-size: 13px;">FAQ</a>
    </div>
</footer>

</body>
</html>

