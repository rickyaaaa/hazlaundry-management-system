@extends('layouts.admin')
@section('title', 'Edit Promo')
@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.promos.index') }}">Promo</a>
    <span class="breadcrumb-sep">›</span>
    <span>Edit</span>
</div>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Promo</h1>
    </div>
</div>

<div style="max-width:560px">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.promos.update', $promo) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                @if($errors->any())
                    <div class="flash flash-error" style="margin-bottom:16px">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Judul Promo</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $promo->title) }}" placeholder="Contoh: Promo Cashback 20%" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Kode Promo</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $promo->code) }}" placeholder="Contoh: HAZ20" style="text-transform:uppercase">
                    <span style="font-size: 11px; color: var(--text-3); display: block; margin-top: 4px;">Kode yang dimasukkan pelanggan saat checkout. Kosongkan jika promo ini hanya banner informasi (tanpa kode).</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Persentase Diskon (%)</label>
                    <input type="number" name="percentage" class="form-control" value="{{ old('percentage', $promo->percentage) }}" min="0" max="100" placeholder="Contoh: 20">
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Promo</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi detail promo (opsional)">{{ old('description', $promo->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" style="display: block; margin-bottom: 8px;">Gambar Saat Ini</label>
                    <div style="width: 160px; height: 100px; overflow: hidden; border-radius: 6px; border: 1px solid var(--border); background: #f8fafc; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <img src="{{ $promo->image_url }}" alt="{{ $promo->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    
                    <label class="form-label">Ganti Gambar Promo</label>
                    <input type="file" name="image" class="form-control" accept="image/jpeg,image/jpg,image/png">
                    <span style="font-size: 11px; color: var(--text-3); display: block; margin-top: 4px;">Format: jpeg, jpg, png. Ukuran maks: 2MB. Kosongkan jika tidak ingin mengganti gambar.</span>
                </div>

                <div class="form-group">
                    <label class="checkbox-wrap">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $promo->is_active) ? 'checked' : '' }}>
                        Promo Aktif
                    </label>
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end; margin-top: 24px;">
                    <a href="{{ route('admin.promos.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
