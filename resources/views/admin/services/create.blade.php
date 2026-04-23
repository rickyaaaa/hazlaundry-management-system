@extends('layouts.admin')
@section('title','Tambah Layanan')
@section('content')
<div class="breadcrumb"><a href="{{ route('admin.services.index') }}">Layanan</a><span class="breadcrumb-sep">›</span><span>Tambah</span></div>
<div class="page-header"><div><h1 class="page-title">Tambah Layanan</h1></div></div>
<div style="max-width:560px">
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('admin.services.store') }}">
    @csrf
    @if($errors->any())<div class="flash flash-error" style="margin-bottom:16px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>{{ $errors->first() }}</div>@endif
    <div class="form-group"><label class="form-label">Nama Layanan</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
    <div class="form-group"><label class="form-label">Deskripsi</label><input type="text" name="description" class="form-control" value="{{ old('description') }}" placeholder="Opsional"></div>
    <div class="form-group"><label class="form-label">Harga per KG (Rp)</label><input type="number" name="price_per_kg" class="form-control" value="{{ old('price_per_kg') }}" min="100" required></div>
    <div class="form-group"><label class="checkbox-wrap"><input type="checkbox" name="is_active" value="1" {{ old('is_active',1)?'checked':'' }}> Layanan Aktif</label></div>
    <div style="display:flex;gap:12px;justify-content:flex-end">
        <a href="{{ route('admin.services.index') }}" class="btn-secondary">Batal</a>
        <button type="submit" class="btn-primary">Simpan</button>
    </div>
</form>
</div></div>
</div>
@endsection
