@extends('layouts.admin')
@section('title', 'Manajemen Users')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Users</h1>
        <p class="page-subtitle">Kelola administrator sistem</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah User
    </a>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Dibuat Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr>
                <td style="color:var(--text-3)">{{ $loop->iteration + ($users->firstItem() - 1) }}</td>
                <td style="font-weight:600">{{ $user->name }}</td>
                <td style="color:var(--text-2)">{{ $user->email }}</td>
                <td style="color:var(--text-2)">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <div style="display:flex;gap:6px">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary" style="padding:6px 10px;font-size:12px">Edit / Password</a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger" style="padding:6px 10px;font-size:12px" onclick="return confirm('Hapus user {{ $user->name }}?')">Hapus</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center" style="padding:32px;color:var(--text-3)">Belum ada user</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:20px">
    {{ $users->links() }}
</div>
@endsection
