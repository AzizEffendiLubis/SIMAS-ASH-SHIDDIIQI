@extends('layouts.app')
@section('title', 'Perbaikan Aset')
@section('page-title', 'Perbaikan Aset')

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header-row">
    <div class="ph-left">
        <h1>Perbaikan Aset</h1>
        <p>Kelola laporan kerusakan dan perbaikan aset</p>
    </div>
    <div class="ph-right">
        {{-- Teknisi tidak bisa membuat laporan baru --}}
        @if(!auth()->user()->isTeknisi())
        <a href="{{ route('repairs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Laporkan Kerusakan
        </a>
        @endif
    </div>
</div>

{{-- ── Filter ── --}}
<div class="card mb-16">
    <div class="card-body" style="padding:14px 18px;">
        <form method="GET" action="{{ route('repairs.index') }}" class="filter-row">

            <div class="search-wrap" style="flex:1;min-width:200px;">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" name="search" class="form-control"
                    placeholder="Cari nama barang, kode, deskripsi..."
                    value="{{ request('search') }}">
            </div>

            {{-- Nilai sesuai enum migration: pending|sedang_diperbaiki|selesai --}}
            <select name="status" class="form-control" style="min-width:170px;width:auto;">
                <option value="">Semua Status</option>
                <option value="pending"
                    {{ request('status') === 'pending' ? 'selected' : '' }}>
                    Menunggu
                </option>
                <option value="sedang_diperbaiki"
                    {{ request('status') === 'sedang_diperbaiki' ? 'selected' : '' }}>
                    Sedang Diperbaiki
                </option>
                <option value="selesai"
                    {{ request('status') === 'selesai' ? 'selected' : '' }}>
                    Selesai
                </option>
            </select>

            {{-- Sort — RepairController::index() membaca request('sort') --}}
            <select name="sort" class="form-control" style="min-width:140px;width:auto;">
                <option value="terbaru"
                    {{ request('sort', 'terbaru') === 'terbaru' ? 'selected' : '' }}>
                    Terbaru
                </option>
                <option value="terlama"
                    {{ request('sort') === 'terlama' ? 'selected' : '' }}>
                    Terlama
                </option>
            </select>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'sort']))
                <a href="{{ route('repairs.index') }}" class="btn btn-outline">
                    <i class="fas fa-xmark"></i> Reset
                </a>
                @endif
            </div>

        </form>
    </div>
</div>

{{-- ── Tabel ── --}}
<div class="card">
    <div class="card-header">
        <h2>Semua Laporan</h2>
        <span style="font-size:12px;color:var(--gray-400);">
            {{ $repairs->total() }} laporan ditemukan
        </span>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            @php
                $colCount = (auth()->user()->isAdminUtama() || auth()->user()->isTeknisi()) ? 8 : 7;
            @endphp
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Lokasi</th>
                        <th>Deskripsi</th>
                        <th style="white-space:nowrap;">Tanggal</th>
                        {{-- Petugas: hanya Admin Utama & Teknisi
                             Dokumen: "Petugas perbaikan tidak ditampilkan kepada pengguna pelapor." --}}
                        @if(auth()->user()->isAdminUtama() || auth()->user()->isTeknisi())
                        <th>Petugas</th>
                        @endif
                        <th>Status</th>
                        <th style="width:90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($repairs as $repair)
                    <tr>
                        <td>
                            <code style="font-size:12px;background:var(--gray-100);
                                padding:2px 7px;border-radius:5px;color:var(--gray-600);">
                                {{ $repair->kode_perbaikan }}
                            </code>
                        </td>
                        <td>
                            <div style="font-weight:600;font-size:13.5px;color:var(--gray-800);">
                                {{ $repair->nama_aset_laporan }}
                            </div>
                            {{-- asset: FK opsional, dikaitkan Admin Utama setelah verifikasi --}}
                            @if($repair->asset)
                            <div style="font-size:12px;color:var(--gray-400);">
                                {{ $repair->asset->kode_aset }} · {{ $repair->asset->kategori }}
                            </div>
                            @endif
                        </td>
                        <td style="font-size:13px;color:var(--gray-500);">
                            {{-- lokasi laporan; fallback lokasi aset jika sudah dikaitkan --}}
                            {{ $repair->lokasi_kerusakan
                                ?? optional($repair->asset)->lokasi_barang
                                ?? '—' }}
                        </td>
                        <td style="font-size:13px;color:var(--gray-600);max-width:200px;">
                            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;"
                                 title="{{ $repair->deskripsi_kerusakan }}">
                                {{ $repair->deskripsi_kerusakan }}
                            </div>
                        </td>
                        <td style="font-size:13px;white-space:nowrap;color:var(--gray-600);">
                            {{ $repair->tanggal_laporan->format('d M Y') }}
                        </td>
                        {{-- Teknisi: ada di DB, tidak tampil ke pelapor biasa --}}
                        @if(auth()->user()->isAdminUtama() || auth()->user()->isTeknisi())
                        <td style="font-size:13px;">
                            @if($repair->teknisi)
                                <span style="font-weight:500;color:var(--gray-700);">
                                    {{ $repair->teknisi->name }}
                                </span>
                            @else
                                <span style="color:var(--gray-300);">Belum ditugaskan</span>
                            @endif
                        </td>
                        @endif
                        <td>
                            {{-- status_badge & status_label: accessor Repair model --}}
                            <span class="badge {{ $repair->status_badge }}">
                                {{ $repair->status_label }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <a href="{{ route('repairs.show', $repair) }}"
                                   class="btn btn-outline btn-sm btn-icon" title="Lihat detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                {{-- Edit: Admin Utama semua laporan;
                                     Teknisi hanya laporan yang ditanganinya --}}
                                @if(auth()->user()->isAdminUtama() ||
                                    (auth()->user()->isTeknisi() &&
                                     $repair->ditangani_oleh === auth()->id()))
                                <a href="{{ route('repairs.edit', $repair) }}"
                                   class="btn btn-outline btn-sm btn-icon" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                @endif
                                {{-- TIDAK ADA tombol hapus — destroy() selalu abort(403)
                                     Dokumen: "Laporan kerusakan tidak dapat dihapus." --}}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $colCount }}">
                            <div class="empty-state">
                                <i class="fas fa-screwdriver-wrench"></i>
                                <p>
                                    @if(request()->hasAny(['search', 'status']))
                                        Tidak ada laporan yang sesuai filter
                                    @else
                                        Belum ada laporan perbaikan
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination — appends() mempertahankan query string --}}
        @if($repairs->hasPages())
        <div class="card-footer">
            <div class="pagination">
                {{ $repairs->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

@endsection