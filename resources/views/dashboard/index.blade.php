@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
    <p>Selamat datang di Sistem Informasi Manajemen Aset Sekolah, {{ auth()->user()->name }}.</p>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-boxes-stacked"></i></div>
        <div>
            <div class="stat-value">{{ number_format($totalAset) }}</div>
            <div class="stat-label">Total Aset</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
        <div>
            <div class="stat-value">{{ number_format($asetAktif) }}</div>
            <div class="stat-label">Aset Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-screwdriver-wrench"></i></div>
        <div>
            <div class="stat-value">{{ number_format($perbaikan) }}</div>
            <div class="stat-label">Perlu Perbaikan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-laptop"></i></div>
        <div>
            <div class="stat-value">{{ number_format($komputer) }}</div>
            <div class="stat-label">Komputer</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teal"><i class="fas fa-chair"></i></div>
        <div>
            <div class="stat-value">{{ number_format($mejaKursi) }}</div>
            <div class="stat-label">Meja &amp; Kursi</div>
        </div>
    </div>
</div>

<!-- Activity Section -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
    <!-- Recent Repairs -->
    <div class="card">
        <div class="card-header" style="padding:20px 20px 14px;display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:15px;font-weight:700;">Aktivitas Perbaikan</h2>
            @if(auth()->user()->canAccess('perbaikan_aset'))
            <a href="{{ route('repairs.index') }}" style="font-size:12px;color:#0C6638;text-decoration:none;font-weight:600;">Lihat Semua →</a>
            @endif
        </div>
        <div class="card-body" style="padding:0 20px 16px;">
            @forelse($recentRepairs as $repair)
            <div class="activity-item">
                <div class="activity-icon {{ $repair->status === 'Selesai' ? 'repair-done' : 'repair' }}">
                    <i class="fas fa-wrench"></i>
                </div>
                <div class="activity-meta">
                    <div class="title">{{ $repair->asset->nama_barang ?? '-' }}</div>
                    <div class="sub">{{ $repair->tanggal_laporan->format('d/m/Y') }} • {{ $repair->asset->lokasi_barang ?? '-' }}</div>
                </div>
                <span class="badge badge-{{ $repair->status === 'Selesai' ? 'success' : ($repair->status === 'Sedang Diperbaiki' ? 'info' : 'warning') }}" style="font-size:11px;">
                    {{ $repair->status }}
                </span>
            </div>
            @empty
            <div style="padding:20px 0;text-align:center;color:#94a3b8;font-size:13px;">
                <i class="fas fa-inbox" style="font-size:24px;display:block;margin-bottom:8px;opacity:.4;"></i>
                Belum ada data perbaikan
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Procurements -->
    <div class="card">
        <div class="card-header" style="padding:20px 20px 14px;display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:15px;font-weight:700;">Pengadaan Terbaru</h2>
            @if(auth()->user()->canAccess('pengadaan_aset'))
            <a href="{{ route('procurements.index') }}" style="font-size:12px;color:#0C6638;text-decoration:none;font-weight:600;">Lihat Semua →</a>
            @endif
        </div>
        <div class="card-body" style="padding:0 20px 16px;">
            @forelse($recentProcurements as $proc)
            <div class="activity-item">
                <div class="activity-icon procurement">
                    <i class="fas fa-cart-plus"></i>
                </div>
                <div class="activity-meta">
                    <div class="title">{{ $proc->nama_barang }}</div>
                    <div class="sub">{{ $proc->tanggal_pengajuan->format('d/m/Y') }} • {{ $proc->unit_kerja }}</div>
                </div>
                <span class="badge badge-{{ $proc->status === 'Disetujui' ? 'success' : ($proc->status === 'Ditolak' ? 'danger' : 'warning') }}" style="font-size:11px;">
                    {{ $proc->status }}
                </span>
            </div>
            @empty
            <div style="padding:20px 0;text-align:center;color:#94a3b8;font-size:13px;">
                <i class="fas fa-inbox" style="font-size:24px;display:block;margin-bottom:8px;opacity:.4;"></i>
                Belum ada pengadaan
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
