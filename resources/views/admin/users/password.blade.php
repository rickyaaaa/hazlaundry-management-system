@extends('layouts.admin')
@section('title', 'Reset Password')
@section('content')
<div class="breadcrumb"><span>Keamanan</span><span class="breadcrumb-sep">›</span><span>Reset Password</span></div>
<div class="page-header"><div><h1 class="page-title">Reset Password</h1></div></div>
<div style="max-width:560px">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.password.update') }}">
                @csrf
                @if($errors->any())
                    <div class="flash flash-error" style="margin-bottom:16px">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <div class="form-group">
                    <label class="form-label">Password Saat Ini</label>
                    <input type="password" name="current_password" class="form-control" required autofocus>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                
                <div style="display:flex;gap:12px;justify-content:flex-end">
                    <a href="{{ route('admin.dashboard') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
