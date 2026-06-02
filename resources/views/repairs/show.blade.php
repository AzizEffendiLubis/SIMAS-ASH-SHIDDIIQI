@extends('layouts.app')
@section('title', 'Detail Perbaikan – ' . $repair->kode_perbaikan)
@section('page-title', 'Perbaikan Aset')
@section('page-parent', 'Detail Laporan')

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header-row">
    <div class="ph-left">
        <h1>Detail Perbaikan</h1>
        <p>Kode: <strong style="color:var(--gray-700);">{{ $repair->kode_perbaikan }}</strong></p>
    </div>
    <div class="ph-right">
        {{-- Edit: Admin Utama bisa semua; Teknisi hanya laporan yang ditanganinya --}}
        @if(auth()->user()->isAdminUtama() ||
            (auth()->user()->isTeknisi() && $repair->ditangani_oleh === auth()->id()))
        <a href="{{ route('repairs.edit', $repair) }}" class="btn btn-primary">
            <i class="fas fa-pen"></i> Update
        </a>
        @endif
        <a href="{{ route('repairs.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:18px;align-items:start;" class="dash-two-col">

    {{-- ── Kolom Kiri ── --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Status Card --}}
        <div class="card">
            <div class="card-body" style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                {{-- Ikon status dinamis sesuai enum Repair model --}}
                @php
                    $statusMeta = match($repair->status) {
                        'selesai'           => ['bg' => '#dcfce7', 'color' => '#16a34a', 'icon' => 'circle-check'],
                        'sedang_diperbaiki' => ['bg' => '#e0f2fe', 'color' => '#0369a1', 'icon' => 'gear'],
                        default             => ['bg' => '#fef9c3', 'color' => '#a16207', 'icon' => 'clock'],
                    };
                @endphp
                <div style="width:54px;height:54px;border-radius:14px;flex-shrink:0;
                    background:{{ $statusMeta['bg'] }};color:{{ $statusMeta['color'] }};
                    display:flex;align-items:center;justify-content:center;font-size:22px;">
                    <i class="fas fa-{{ $statusMeta['icon'] }}{{ $repair->status === 'sedang_diperbaiki' ? ' fa-spin' : '' }}"></i>
                </div>
                <div>
                    <p style="font-size:12px;color:var(--gray-400);margin-bottom:4px;">Status Perbaikan</p>
                    {{-- status_badge & status_label: accessor di Repair model --}}
                    <span class="badge {{ $repair->status_badge }}" style="font-size:13.5px;padding:4px 14px;">
                        {{ $repair->status_label }}
                    </span>
                </div>
                @if($repair->tanggal_selesai)
                <div style="margin-left:auto;text-align:right;">
                    <p style="font-size:12px;color:var(--gray-400);">Selesai pada</p>
                    <p style="font-weight:700;font-size:14px;color:var(--gray-700);">
                        {{ $repair->tanggal_selesai->format('d M Y') }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        {{-- Info Utama --}}
        <div class="card">
            <div class="card-header">
                <h2>Informasi Laporan</h2>
            </div>
            <div class="card-body">
                <div class="form-grid" style="gap:14px;margin-bottom:16px;">
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Nama Barang (Laporan)</p>
                        {{-- nama_aset_laporan selalu ada, ditulis manual oleh pelapor --}}
                        <p style="font-weight:700;font-size:15px;color:var(--gray-800);">
                            {{ $repair->nama_aset_laporan }}
                        </p>
                        {{-- FK opsional: tampil kode aset jika sudah dikaitkan Admin Utama --}}
                        @if($repair->asset)
                        <p style="font-size:12px;margin-top:2px;">
                            <a href="{{ route('assets.show', $repair->asset) }}"
                               style="color:var(--primary);font-weight:600;">
                                <i class="fas fa-link" style="font-size:10px;"></i>
                                {{ $repair->asset->kode_aset }}
                            </a>
                        </p>
                        @endif
                    </div>
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Lokasi Kerusakan</p>
                        <p style="font-weight:500;color:var(--gray-700);">{{ $repair->lokasi_kerusakan ?? '-' }}</p>
                        @if($repair->asset && $repair->asset->unit)
                        <p style="font-size:12px;color:var(--gray-400);margin-top:2px;">
                            {{ $repair->asset->unit->nama_unit }}
                        </p>
                        @endif
                    </div>
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Dilaporkan Oleh</p>
                        <p style="font-weight:500;color:var(--gray-700);">{{ $repair->pelapor->name ?? '-' }}</p>
                        {{-- Unit pelapor: relasi pelapor->unit (belongsTo Unit di User model) --}}
                        <p style="font-size:12px;color:var(--gray-400);margin-top:2px;">
                            <i class="fas fa-building" style="font-size:10px;opacity:.6;"></i>
                            {{ optional($repair->pelapor?->unit)->nama_unit ?? 'Tanpa Unit' }}
                        </p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Tanggal Laporan</p>
                        <p style="font-weight:500;color:var(--gray-700);">
                            {{ $repair->tanggal_laporan->format('d M Y') }}
                        </p>
                    </div>

                    {{-- Teknisi: hanya Admin Utama & Teknisi yang menangani
                         Dokumen: "Petugas perbaikan tidak ditampilkan kepada pengguna pelapor." --}}
                    @if($showTeknisi)
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Ditangani Oleh</p>
                        @if($repair->teknisi)
                            <p style="font-weight:500;color:var(--gray-700);">{{ $repair->teknisi->name }}</p>
                        @else
                            <p style="font-size:13px;color:var(--gray-300);font-style:italic;">Belum ditugaskan</p>
                        @endif
                    </div>
                    @endif

                    @if($repair->biaya_perbaikan)
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Biaya Perbaikan</p>
                        <p style="font-weight:700;font-size:16px;color:var(--primary);">
                            Rp {{ number_format($repair->biaya_perbaikan, 0, ',', '.') }}
                        </p>
                    </div>
                    @endif
                </div>

                {{-- Deskripsi Kerusakan --}}
                <div style="padding-top:14px;border-top:1px solid var(--gray-100);">
                    <p style="font-size:12px;color:var(--gray-400);font-weight:600;margin-bottom:6px;">
                        Deskripsi Kerusakan
                    </p>
                    <p style="font-size:13.5px;color:var(--gray-700);
                        background:#fef9c3;border:1px solid #fde68a;
                        border-radius:var(--radius-sm);padding:12px 14px;line-height:1.7;">
                        {{ $repair->deskripsi_kerusakan }}
                    </p>
                </div>

                {{-- Tindakan Perbaikan (jika sudah diisi teknisi / admin) --}}
                @if($repair->tindakan_perbaikan)
                <div style="margin-top:12px;">
                    <p style="font-size:12px;color:var(--gray-400);font-weight:600;margin-bottom:6px;">
                        Tindakan Perbaikan
                    </p>
                    <p style="font-size:13.5px;color:var(--gray-700);
                        background:#f0fdf4;border:1px solid #bbf7d0;
                        border-radius:var(--radius-sm);padding:12px 14px;line-height:1.7;">
                        {{ $repair->tindakan_perbaikan }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Kolom Kanan: Foto ── --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Foto Kerusakan (RepairPhoto hasMany via $repair->photos) --}}
        <div class="card">
            <div class="card-header">
                <h2>Foto Kerusakan</h2>
                @if($repair->photos->isNotEmpty())
                <span class="badge badge-secondary">{{ $repair->photos->count() }} foto</span>
                @endif
            </div>
            <div class="card-body">
                @if($repair->photos->isNotEmpty())
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        @foreach($repair->photos as $foto)
                        <a href="{{ Storage::url($foto->file_path) }}" target="_blank" rel="noopener">
                            <img src="{{ Storage::url($foto->file_path) }}" alt="Foto kerusakan {{ $loop->iteration }}"
                                 style="width:100%;border-radius:var(--radius-sm);
                                        border:1px solid var(--gray-200);
                                        object-fit:cover;max-height:220px;
                                        transition:opacity .15s;"
                                 onmouseover="this.style.opacity='.85'"
                                 onmouseout="this.style.opacity='1'">
                        </a>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state" style="padding:32px 16px;">
                        <i class="fas fa-image"></i>
                        <p>Tidak ada foto kerusakan</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Foto Aset (accessor foto_utama di Asset model) — jika laporan sudah dikaitkan ke aset --}}
        @if($repair->asset && $repair->asset->foto_utama)
        <div class="card">
            <div class="card-header">
                <h2>Foto Aset</h2>
            </div>
            <div class="card-body">
                <a href="{{ route('assets.show', $repair->asset) }}" title="Lihat detail aset">
                    <img src="{{ Storage::url($repair->asset->foto_utama->file_path) }}"
                         alt="Foto aset {{ $repair->asset->nama_barang }}"
                         style="width:100%;border-radius:var(--radius-sm);
                                border:1px solid var(--gray-200);object-fit:cover;max-height:200px;">
                </a>
                <p style="font-size:12px;color:var(--gray-400);margin-top:8px;text-align:center;">
                    {{ $repair->asset->nama_barang }}
                </p>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection