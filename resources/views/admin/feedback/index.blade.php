@extends('layouts.admin')
@section('title', 'Kritik & Saran')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Kritik & Saran</h1>
        <p class="page-subtitle">Masukan yang dikirim pelanggan lewat halaman tracking</p>
    </div>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th style="width: 50px">#</th>
                <th>Pelanggan</th>
                <th>Tracking Code</th>
                <th>Pesan</th>
                <th style="width: 150px">Tanggal</th>
                <th style="width: 90px">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($feedbacks as $f)
        <tr>
            <td style="color:var(--text-3)">{{ $loop->iteration }}</td>
            <td>
                <div style="font-weight:600">{{ $f->name ?? '-' }}</div>
                <div style="font-size:12px; color:var(--text-3)">{{ $f->phone_number ?? '-' }}</div>
            </td>
            <td>
                @if($f->transaction)
                    <a href="{{ route('admin.transactions.show', $f->transaction) }}" style="font-weight:600; color:var(--primary)">#{{ $f->transaction->tracking_code }}</a>
                @else
                    <span style="color:var(--text-3)">-</span>
                @endif
            </td>
            <td style="color:var(--text-2); font-size:13px; max-width:420px">{{ $f->message }}</td>
            <td style="font-size:12px;color:var(--text-2)">{{ $f->created_at->format('d M Y • H:i') }}</td>
            <td>
                <form method="POST" action="{{ route('admin.feedback.destroy', $f) }}" style="margin:0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger" style="padding:6px 10px; font-size:12px" onclick="return confirm('Apakah Anda yakin ingin menghapus masukan ini?');">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center" style="padding:32px; color:var(--text-3)">Belum ada kritik & saran dari pelanggan</td>
        </tr>
        @endforelse
        </tbody>
    </table>
    <div class="pagination-wrapper">
        <span>Showing {{ $feedbacks->firstItem()??0 }} to {{ $feedbacks->lastItem()??0 }} of {{ $feedbacks->total() }} masukan</span>
        <div>{{ $feedbacks->links('vendor.pagination.simple') }}</div>
    </div>
</div>
@endsection
