@extends('layouts.admin')
@section('title', 'Tambah User')
@section('content')
<div class="breadcrumb"><a href="{{ route('admin.users.index') }}">Users</a><span class="breadcrumb-sep">›</span><span>Tambah</span></div>
<div class="page-header"><div><h1 class="page-title">Tambah User</h1></div></div>
<div style="max-width:560px">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                @if($errors->any())
                    <div class="flash flash-error" style="margin-bottom:16px">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <div class="form-group">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                
                <div style="display:flex;gap:12px;justify-content:flex-end">
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
