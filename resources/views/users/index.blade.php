@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header-row">
    <div class="ph-left">
        <h1>Manajemen Pengguna</h1>
        <p>Kelola akun pengguna dan atur hak akses sistem</p>
    </div>
    <div class="ph-right">
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Tambah Pengguna
        </a>
    </div>
</div>

{{-- ── Filter ── --}}
<div class="card mb-16">
    <div class="card-body" style="padding:14px 18px;">
        <form method="GET" action="{{ route('users.index') }}" class="filter-row">

            <div class="search-wrap" style="flex:1;min-width:200px;">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" name="search" class="form-control"
                    placeholder="Cari username, nama, atau email..."
                    value="{{ request('search') }}">
            </div>

            {{-- Filter role — enum: kepala_yayasan|admin_utama|admin_unit|teknisi|user --}}
            <select name="role" class="form-control" style="min-width:160px;width:auto;">
                <option value="">Semua Role</option>
                <option value="admin_utama"    {{ request('role') === 'admin_utama'    ? 'selected' : '' }}>Admin Utama</option>
                <option value="kepala_yayasan" {{ request('role') === 'kepala_yayasan' ? 'selected' : '' }}>Kepala Yayasan</option>
                <option value="admin_unit"     {{ request('role') === 'admin_unit'     ? 'selected' : '' }}>Admin Unit</option>
                <option value="teknisi"        {{ request('role') === 'teknisi'        ? 'selected' : '' }}>Teknisi</option>
                <option value="user"           {{ request('role') === 'user'           ? 'selected' : '' }}>User</option>
            </select>

            {{-- Filter status — enum: aktif|nonaktif --}}
            <select name="status" class="form-control" style="min-width:140px;width:auto;">
                <option value="">Semua Status</option>
                <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
            </select>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'role', 'status']))
                <a href="{{ route('users.index') }}" class="btn btn-outline">
                    <i class="fas fa-xmark"></i> Reset
                </a>
                @endif
            </div>

        </form>
    </div>
</div>

{{-- ── Tabel Pengguna ── --}}
<div class="card">
    <div class="card-header">
        <h2>Daftar Pengguna</h2>
        <span style="font-size:12px;color:var(--gray-400);">
            {{ $users->total() }} pengguna terdaftar
        </span>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:9px;">
                                {{-- Warna avatar sesuai role --}}
                                @php
                                    $avatarBg = match($u->role) {
                                        'admin_utama'    => '#7c3aed',
                                        'kepala_yayasan' => '#c2410c',
                                        'admin_unit'     => '#2563eb',
                                        'teknisi'        => '#15803d',
                                        default          => '#475569',
                                    };
                                @endphp
                                <div class="avatar avatar-sm"
                                     style="background:{{ $avatarBg }};">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <span style="font-weight:600;font-size:13.5px;color:var(--gray-800);">
                                    {{ $u->username }}
                                </span>
                            </div>
                        </td>
                        <td style="font-size:13.5px;color:var(--gray-700);">
                            {{ $u->name }}
                        </td>
                        <td style="font-size:13px;color:var(--gray-500);">
                            {{ $u->email ?? '—' }}
                        </td>
                        <td>
                            {{-- role_badge & role_label: accessor di User model --}}
                            <span class="badge {{ $u->role_badge }}">{{ $u->role_label }}</span>
                        </td>
                        <td style="font-size:13px;color:var(--gray-600);">
                            {{-- unit: relasi belongsTo Unit di User model --}}
                            {{ $u->unit->nama_unit ?? '—' }}
                        </td>
                        <td>
                            {{-- badge-aktif / badge-nonaktif dari app.blade --}}
                            <span class="badge badge-{{ $u->status }}">{{ $u->status_label }}</span>
                        </td>
                        <td style="font-size:12.5px;color:var(--gray-400);white-space:nowrap;">
                            {{ $u->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <a href="{{ route('users.show', $u) }}"
                                   class="btn btn-outline btn-sm btn-icon" title="Lihat detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $u) }}"
                                   class="btn btn-outline btn-sm btn-icon" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                {{-- TIDAK ADA tombol hapus/nonaktif di sini.
                                     Nonaktifkan lewat halaman edit (field status = nonaktif).
                                     Dokumen: "Pengguna tidak dihapus dari sistem, hanya dinonaktifkan."
                                     destroy() selalu abort(403). --}}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <p>
                                    @if(request()->hasAny(['search', 'role', 'status']))
                                        Tidak ada pengguna yang sesuai filter
                                    @else
                                        Belum ada pengguna terdaftar
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="card-footer">
            <div class="pagination">
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
        @endif

    </div>
</div>

@endsection