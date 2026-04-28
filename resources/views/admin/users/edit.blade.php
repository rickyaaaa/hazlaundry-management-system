@extends('layouts.admin')
@section('title', 'Edit User')
@section('content')
<div class="breadcrumb"><a href="{{ route('admin.users.index') }}">Users</a><span class="breadcrumb-sep">›</span><span>Edit</span></div>
<div class="page-header"><div><h1 class="page-title">Edit User: {{ $user->name }}</h1></div></div>
<div style="max-width:560px">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')
                
                @if($errors->any())
                    <div class="flash flash-error" style="margin-bottom:16px">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <div class="form-group">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div style="margin: 24px 0 12px; border-top: 1px solid var(--border); padding-top: 24px;">
                    <h3 style="font-size: 14px; color: var(--text-1); margin-bottom: 4px;">Ubah Password</h3>
                    <p style="font-size: 12px; color: var(--text-3); margin-bottom: 16px;">Kosongkan jika tidak ingin mengubah password.</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                
                <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:24px">
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Perbarui User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
