@extends('layouts.admin')
@section('title','Layanan')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Layanan Laundry</h1><p class="page-subtitle">Kelola jenis dan harga layanan</p></div>
    <a href="{{ route('admin.services.create') }}" class="btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Layanan
    </a>
</div>
<div class="table-wrapper">
    <table>
        <thead><tr><th>#</th><th>Nama Layanan</th><th>Deskripsi</th><th>Harga/kg</th><th>Total Transaksi</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($services as $s)
        <tr>
            <td style="color:var(--text-3)">{{ $loop->iteration }}</td>
            <td style="font-weight:600">{{ $s->name }}</td>
            <td style="color:var(--text-2);font-size:12px">{{ $s->description ?? '-' }}</td>
            <td style="font-weight:600;color:var(--primary)">Rp {{ number_format($s->price_per_kg,0,',','.') }}</td>
            <td>{{ $s->transactions_count }} order</td>
            <td>
                @if($s->is_active)<span class="badge badge-selesai">Aktif</span>
                @else<span class="badge badge-diambil">Nonaktif</span>@endif
            </td>
            <td>
                <div style="display:flex;gap:6px">
                    <a href="{{ route('admin.services.edit',$s) }}" class="btn-secondary" style="padding:6px 10px;font-size:12px">Edit</a>
                    <form method="POST" action="{{ route('admin.services.destroy',$s) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger" style="padding:6px 10px;font-size:12px" data-confirm="Hapus layanan {{ $s->name }}?">Hapus</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center" style="padding:32px;color:var(--text-3)">Belum ada layanan</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
