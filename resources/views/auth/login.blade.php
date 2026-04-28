<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login – HAZ Laundry</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<style>
body{background:#eef2f7;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px}
.version-badge{position:fixed;bottom:16px;left:16px;background:rgba(0,0,0,.06);padding:8px 14px;border-radius:8px;font-size:11px;color:#64748b}
</style>
</head>
<body>
<div class="auth-logo">
    <div class="auth-logo-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/>
        </svg>
    </div>
    <div class="auth-logo-name">HAZ Laundry</div>
    <div class="auth-logo-sub">Enterprise Laundry Management</div>
</div>

<div class="auth-card">
    <div class="auth-title">Welcome back</div>
    <div class="auth-sub">Silakan masuk ke akun admin Anda.</div>

    @if ($errors->any())
    <div class="flash flash-error" style="margin-bottom:16px">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Username</label>
            <div class="input-icon-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                <label class="form-label" style="margin:0">Password</label>
            </div>
            <div class="input-icon-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>
        <div class="form-group">
            <label class="checkbox-wrap">
                <input type="checkbox" name="remember"> Ingat saya selama 30 hari
            </label>
        </div>
        <button type="submit" class="btn-primary w-full" style="justify-content:center;padding:12px;font-size:14px">
            Masuk →
        </button>
    </form>

    <div class="auth-footer" style="margin-top:20px">
        Tidak punya akun? <a href="#">Hubungi Administrator</a>
    </div>

    <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border); text-align: center;">
        <div style="font-size: 13px; color: var(--text-2); margin-bottom: 12px;">Bukan Admin?</div>
        <a href="{{ route('tracking.index') }}" class="btn-secondary w-full" style="justify-content: center; padding: 12px; font-size: 14px; background: var(--bg);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Track Laundry Anda
        </a>
    </div>
</div>

<div class="auth-links">
    <span>🔒 Secure Access</span>
    <span>·</span>
    <span>Support Center</span>
</div>

<div class="version-badge">V 1.0.0-Stable · HAZ Laundry</div>
</body>
</html>
