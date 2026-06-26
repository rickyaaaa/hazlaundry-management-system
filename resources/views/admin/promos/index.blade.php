@extends('layouts.admin')
@section('title', 'Manajemen Promo')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Promo</h1>
        <p class="page-subtitle">Kelola banner promo CRM untuk pelanggan</p>
    </div>
    <a href="{{ route('admin.promos.create') }}" class="btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Promo
    </a>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th style="width: 50px">#</th>
                <th style="width: 120px">Gambar</th>
                <th>Judul Promo</th>
                <th>Deskripsi</th>
                <th style="width: 120px">Status</th>
                <th style="width: 150px">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($promos as $p)
        <tr>
            <td style="color:var(--text-3)">{{ $loop->iteration }}</td>
            <td>
                <div style="width: 100px; height: 60px; overflow: hidden; border-radius: 6px; border: 1px solid var(--border); background: #f8fafc; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ $p->image_url }}" alt="{{ $p->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </td>
            <td style="font-weight:600">{{ $p->title }}</td>
            <td style="color:var(--text-2); font-size:12px">
                {{ $p->description ?? '-' }}
            </td>
            <td>
                @if($p->is_active)
                    <span class="badge badge-selesai">Aktif</span>
                @else
                    <span class="badge badge-diambil">Nonaktif</span>
                @endif
            </td>
            <td>
                <div style="display:flex; gap:6px; align-items: center;">
                    <form method="POST" action="{{ route('admin.promos.broadcast', $p) }}" style="margin:0">
                        @csrf
                        <button type="submit" class="btn-primary" style="padding:6px 10px; font-size:12px; background: #0ea5e9; border: none; cursor:pointer;" onclick="return confirm('Apakah Anda yakin ingin mengirim broadcast email promo ini ke seluruh pelanggan?');">Broadcast</button>
                    </form>
                    <a href="{{ route('admin.promos.edit', $p) }}" class="btn-secondary" style="padding:6px 10px; font-size:12px">Edit</a>
                    <form method="POST" action="{{ route('admin.promos.destroy', $p) }}" style="margin:0">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="btn-danger" style="padding:6px 10px; font-size:12px" onclick="return confirm('Apakah Anda yakin ingin menghapus promo ini?');">Hapus</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center" style="padding:32px; color:var(--text-3)">Belum ada banner promo</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
