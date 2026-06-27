@extends('layouts.app')
@section('title', 'Detail Aset')
@section('page-title', 'Daftar Aset')

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header-row">
    <div class="ph-left">
        <h1>Detail Aset</h1>
        <p>Informasi lengkap aset <strong>{{ $asset->nama_barang }}</strong></p>
    </div>
    <div class="ph-right">
        {{-- Edit: hanya Admin Utama & Admin Unit --}}
        {{-- Dokumen: "Kepala Yayasan hanya berperan sebagai pihak monitoring." --}}
        @if(auth()->user()->canEditAset())
        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        @endif
        <a href="{{ route('assets.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="dash-two-col">

    <!-- Main Info -->
    {{-- min-width:0 wajib di sini: <table class="detail-table"> tetap kena
         rule global `table { min-width:580px }` dari layout.blade.php
         (class detail-table tidak override min-width). Tanpa min-width:0,
         grid item ini akan melebar mengikuti 580px tersebut dan batas
         kanannya tidak sejajar dengan tombol "Edit"/"Kembali" di atas. --}}
    <div style="display:flex;flex-direction:column;gap:20px;min-width:0;">

        <div class="card">
            <div class="card-body">
                <p class="section-title">Informasi Barang</p>
                <table class="detail-table">
                    <tr>
                        <td class="dt-label">Kode Aset</td>
                        <td class="dt-val">
                            {{-- kode_aset, bukan kode_barang --}}
                            <code style="font-size:13px;font-weight:700;background:var(--gray-100);padding:3px 9px;border-radius:6px;">{{ $asset->kode_aset }}</code>
                        </td>
                    </tr>
                    <tr>
                        <td class="dt-label">Nama Barang</td>
                        <td class="dt-val" style="font-weight:600;font-size:15px;">{{ $asset->nama_barang }}</td>
                    </tr>
                    <tr>
                        <td class="dt-label">Kategori</td>
                        <td class="dt-val">{{ $asset->kategori }}</td>
                    </tr>
                    @if($asset->spesifikasi)
                    <tr>
                        <td class="dt-label">Spesifikasi</td>
                        <td class="dt-val">{{ $asset->spesifikasi }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="dt-label">Lokasi Barang</td>
                        <td class="dt-val">{{ $asset->lokasi_barang ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="dt-label">Unit</td>
                        {{-- unit adalah relasi belongsTo Unit (Asset.php) --}}
                        <td class="dt-val">{{ $asset->unit->nama_unit ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="dt-label">Jumlah Barang</td>
                        {{-- satuan adalah relasi belongsTo UnitSatuan (Asset.php) --}}
                        <td class="dt-val" style="font-weight:600;font-size:16px;">
                            {{ $asset->jumlah_barang }}
                            <span style="font-size:13px;font-weight:400;color:var(--gray-500);">
                                {{ $asset->satuan->nama_satuan ?? 'unit' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="dt-label">Kondisi Barang</td>
                        <td class="dt-val">
                            {{-- kondisi_badge & kondisi_label dari accessor di Asset model --}}
                            <span class="badge {{ $asset->kondisi_badge }}">
                                {{ $asset->kondisi_label }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="dt-label">Sumber Dana</td>
                        {{-- fundingSource adalah relasi belongsTo FundingSource (Asset.php) --}}
                        <td class="dt-val">{{ $asset->fundingSource->nama_sumber ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="dt-label">Harga Barang</td>
                        <td class="dt-val" style="font-weight:700;font-size:15px;color:var(--primary);">
                            Rp {{ number_format($asset->harga_barang, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="dt-label">Tanggal Pengadaan</td>
                        <td class="dt-val">
                            {{ $asset->tanggal_pengadaan ? $asset->tanggal_pengadaan->format('d M Y') : '-' }}
                        </td>
                    </tr>
                    @if($asset->keterangan_dasar)
                    <tr>
                        <td class="dt-label">Dasar Penambahan</td>
                        <td class="dt-val">
                            <p style="font-size:13px;color:var(--gray-700);background:var(--gray-50);border-radius:8px;padding:9px 11px;border:1px solid var(--gray-200);">{{ $asset->keterangan_dasar }}</p>
                        </td>
                    </tr>
                    @endif
                    @if($asset->keterangan)
                    <tr>
                        <td class="dt-label">Keterangan</td>
                        <td class="dt-val">
                            <p style="font-size:13px;color:var(--gray-700);background:var(--gray-50);border-radius:8px;padding:9px 11px;border:1px solid var(--gray-200);">{{ $asset->keterangan }}</p>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td class="dt-label">Ditambahkan Oleh</td>
                        {{-- creator adalah relasi belongsTo User (Asset.php) --}}
                        <td class="dt-val">{{ $asset->creator->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="dt-label">Tanggal Input</td>
                        <td class="dt-val">{{ $asset->created_at->format('d M Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Foto Aset (multi-foto) -->
        @if($asset->photos->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h2>Foto Aset</h2>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:10px;">
                    @foreach($asset->photos as $foto)
                    <div style="position:relative;">
                        <img src="{{ Storage::url($foto->file_path) }}"
                             alt="Foto {{ $asset->nama_barang }}"
                             style="width:100%;aspect-ratio:1;object-fit:cover;border-radius:8px;border:{{ $foto->is_primary ? '2px solid var(--primary)' : '1px solid var(--gray-200)' }};">
                        @if($foto->is_primary)
                        <span style="position:absolute;bottom:4px;left:4px;background:var(--primary);color:#fff;font-size:9px;padding:2px 5px;border-radius:4px;font-weight:600;">UTAMA</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Riwayat Perbaikan -->
        <div class="card">
            <div class="card-header">
                <h2>Riwayat Perbaikan</h2>
                {{-- Tombol laporkan: Admin Utama, Admin Unit, User (bukan Teknisi & Kepala Yayasan) --}}
                {{-- Dokumen: laporan repair tidak pakai query string asset_id --}}
                {{-- karena nama aset ditulis manual (bukan dropdown) --}}
                @if(auth()->user()->canAccess('perbaikan_aset') && !auth()->user()->isTeknisi() && !auth()->user()->isKepalaYayasan())
                <a href="{{ route('repairs.create') }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-plus"></i> Laporkan Kerusakan
                </a>
                @endif
            </div>
            <div class="card-body">
                {{-- $asset->repairs dimuat via eager load di AssetController::show() --}}
                <div class="activity-list">
                @forelse($asset->repairs as $repair)
                    <div class="activity-item" style="flex-direction:column;align-items:stretch;">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;flex-wrap:wrap;margin-bottom:6px;">
                            <div>
                                <code style="font-size:12px;background:var(--gray-100);padding:2px 7px;border-radius:5px;">{{ $repair->kode_perbaikan }}</code>
                                {{-- status_badge & status_label dari accessor di Repair model --}}
                                <span class="badge {{ $repair->status_badge }}" style="margin-left:6px;font-size:11px;">
                                    {{ $repair->status_label }}
                                </span>
                            </div>
                            <span style="font-size:12px;color:var(--gray-400);white-space:nowrap;">{{ $repair->tanggal_laporan->format('d M Y') }}</span>
                        </div>
                        <p style="font-size:13px;color:var(--gray-700);margin-bottom:3px;">{{ $repair->deskripsi_kerusakan }}</p>
                        @if($repair->tindakan_perbaikan)
                        <p style="font-size:12px;color:var(--gray-500);">
                            <i class="fas fa-wrench" style="margin-right:4px;"></i>{{ $repair->tindakan_perbaikan }}
                        </p>
                        @endif
                        {{-- Teknisi TIDAK ditampilkan ke pelapor --}}
                        {{-- Dokumen: "Petugas perbaikan tidak ditampilkan kepada pengguna pelapor." --}}
                        {{-- Hanya Admin Utama & Teknisi yang bisa melihat siapa teknisinya --}}
                        @if((auth()->user()->isAdminUtama() || auth()->user()->isTeknisi()) && $repair->teknisi)
                        <p style="font-size:11px;color:var(--gray-400);margin-top:3px;">
                            <i class="fas fa-user" style="margin-right:3px;"></i>{{ $repair->teknisi->name }}
                        </p>
                        @endif
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-check-circle" style="color:#bbf7d0;"></i>
                        <p>Belum ada riwayat perbaikan</p>
                    </div>
                @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar: Foto Utama & Riwayat Kondisi -->
    {{-- min-width:0 ditambahkan juga untuk konsistensi/jaga-jaga. --}}
    <div style="display:flex;flex-direction:column;gap:20px;min-width:0;">

        <!-- Foto Utama -->
        <div class="card">
            <div class="card-body" style="text-align:center;">
                <p class="section-title" style="text-align:left;">Foto Utama</p>
                {{-- foto_utama adalah accessor di Asset model --}}
                @if($asset->foto_utama)
                <img src="{{ Storage::url($asset->foto_utama->file_path) }}"
                     alt="Foto {{ $asset->nama_barang }}"
                     style="width:100%;border-radius:10px;border:1px solid var(--gray-200);object-fit:cover;">
                @else
                <div style="width:100%;aspect-ratio:1;background:var(--gray-50);border-radius:10px;border:2px dashed var(--gray-200);display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--gray-300);">
                    <i class="fas fa-image" style="font-size:32px;margin-bottom:8px;"></i>
                    <p style="font-size:12px;">Tidak ada foto</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Riwayat Kondisi -->
        <div class="card">
            <div class="card-header">
                <h2>Riwayat Kondisi</h2>
            </div>
            <div class="card-body">
                {{-- conditionHistories dimuat via eager load di AssetController::show() --}}
                <div class="activity-list">
                @forelse($asset->conditionHistories as $history)
                    <div class="activity-item" style="flex-direction:column;align-items:stretch;">
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:4px;">
                            <span style="font-size:13px;font-weight:600;color:var(--gray-800);">
                                {{-- getKondisiChangeLabel() dari AssetConditionHistory model --}}
                                {{ $history->getKondisiChangeLabel() }}
                            </span>
                            <span style="font-size:11px;color:var(--gray-400);white-space:nowrap;">
                                {{ $history->changed_at?->format('d M Y') ?? '-' }}
                            </span>
                        </div>
                        @if($history->lokasi_lama || $history->lokasi_baru)
                        <p style="font-size:12px;color:var(--gray-500);margin-bottom:2px;">
                            Lokasi: {{ $history->lokasi_lama ?? '-' }} → {{ $history->lokasi_baru ?? '-' }}
                        </p>
                        @endif
                        @if($history->catatan)
                        <p style="font-size:12px;color:var(--gray-400);">{{ $history->catatan }}</p>
                        @endif
                        {{-- changedBy adalah relasi belongsTo User (AssetConditionHistory model) --}}
                        @if($history->changedBy)
                        <p style="font-size:11px;color:var(--gray-300);margin-top:2px;">
                            <i class="fas fa-user" style="margin-right:3px;"></i>{{ $history->changedBy->name }}
                        </p>
                        @endif
                    </div>
                @empty
                    <div class="empty-state">
                        <p>Belum ada riwayat kondisi</p>
                    </div>
                @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection