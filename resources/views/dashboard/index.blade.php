@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ── Greeting ── --}}
<div class="page-header">
    <h1>Dashboard</h1>
    <p>Selamat datang, <strong style="color:var(--gray-700);">{{ auth()->user()->name }}</strong>
        &mdash; {{ auth()->user()->role_label }}</p>
</div>

{{-- ── Stats Grid ── --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-boxes-stacked"></i>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ number_format($totalAset) }}</div>
            <div class="stat-label">Total Aset</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-circle-check"></i>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ number_format($asetAktif) }}</div>
            <div class="stat-label">Aset Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        {{-- $perbaikanAktif = jumlah laporan pending + sedang_diperbaiki --}}
        <div class="stat-icon orange">
            <i class="fas fa-screwdriver-wrench"></i>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ number_format($perbaikanAktif) }}</div>
            <div class="stat-label">Perlu Perbaikan</div>
        </div>
    </div>
    <div class="stat-card">
        {{-- $totalKomputer = sum jumlah_barang kategori Komputer --}}
        <div class="stat-icon purple">
            <i class="fas fa-laptop"></i>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ number_format($totalKomputer) }}</div>
            <div class="stat-label">Komputer</div>
        </div>
    </div>
    <div class="stat-card">
        {{-- $totalFurnitur = sum jumlah_barang kategori Furnitur --}}
        <div class="stat-icon teal">
            <i class="fas fa-chair"></i>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ number_format($totalFurnitur) }}</div>
            <div class="stat-label">Furnitur</div>
        </div>
    </div>
</div>

{{-- ── Activity Section ── --}}
{{-- align-items:stretch memastikan kedua card punya tinggi yang sama,
     sehingga batas bawah keduanya sejajar --}}
<div class="dash-two-col" style="align-items:stretch;">

    {{-- ── Aktivitas Perbaikan ── --}}
    <div class="card" style="display:flex;flex-direction:column;height:100%;">
        <div class="card-header">
            <div style="display:flex;align-items:center;gap:8px;">
                <i class="fas fa-screwdriver-wrench" style="color:var(--warning);font-size:14px;"></i>
                <h2>Aktivitas Perbaikan</h2>
            </div>
            @if(auth()->user()->canAccess('perbaikan_aset'))
            <a href="{{ route('repairs.index') }}"
               style="font-size:12px;color:var(--primary);font-weight:600;
                      display:flex;align-items:center;gap:4px;">
                Lihat Semua <i class="fas fa-arrow-right" style="font-size:10px;"></i>
            </a>
            @endif
        </div>
        <div class="card-body" style="padding:4px 20px 16px;flex:1;display:flex;flex-direction:column;">
            {{-- Batasi maksimal 6 item agar tidak terlalu panjang --}}
            @forelse($recentRepairs->take(6) as $repair)
            <div class="activity-item">
                <div class="activity-icon {{ $repair->status === 'selesai' ? 'repair-done' : 'repair' }}">
                    <i class="fas fa-{{ $repair->status === 'selesai' ? 'circle-check' : 'screwdriver-wrench' }}"></i>
                </div>
                <div class="activity-body">
                    {{-- nama_aset_laporan ditulis manual oleh pelapor --}}
                    <div class="title">{{ $repair->nama_aset_laporan }}</div>
                    <div class="meta">
                        <i class="fas fa-calendar" style="font-size:10px;opacity:.5;"></i>
                        {{ $repair->tanggal_laporan->format('d M Y') }}
                        @if($repair->lokasi_kerusakan)
                            &nbsp;·&nbsp;
                            <i class="fas fa-location-dot" style="font-size:10px;opacity:.5;"></i>
                            {{ $repair->lokasi_kerusakan }}
                        @endif
                    </div>
                </div>
                {{-- status_badge & status_label: accessor di Repair model --}}
                <span class="badge {{ $repair->status_badge }}" style="font-size:11px;flex-shrink:0;">
                    {{ $repair->status_label }}
                </span>
            </div>
            @empty
            <div class="empty-state" style="padding:28px 0;margin:auto;">
                <i class="fas fa-inbox"></i>
                <p>Belum ada laporan perbaikan</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ── Log Aktivitas (Admin Utama & Kepala Yayasan)
             atau Ringkasan Kondisi Aset (role lain) ── --}}
    <div class="card" style="display:flex;flex-direction:column;height:100%;">
        <div class="card-header">
            @if($recentLogs !== null)
                {{-- Admin Utama & Kepala Yayasan
                     Dokumen: "Akses Kepala Yayasan meliputi dashboard, laporan aset,
                               dan log aktivitas sistem." --}}
                <div style="display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-clock-rotate-left" style="color:var(--primary);font-size:14px;"></i>
                    <h2>Log Aktivitas</h2>
                </div>
                @if(auth()->user()->canAccess('log_aktivitas'))
                <a href="{{ route('activity-logs.index') }}"
                   style="font-size:12px;color:var(--primary);font-weight:600;
                          display:flex;align-items:center;gap:4px;">
                    Lihat Semua <i class="fas fa-arrow-right" style="font-size:10px;"></i>
                </a>
                @endif
            @else
                <div style="display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-chart-bar" style="color:var(--primary);font-size:14px;"></i>
                    <h2>Kondisi Aset</h2>
                </div>
            @endif
        </div>
        <div class="card-body" style="padding:4px 20px 16px;flex:1;display:flex;flex-direction:column;">

            @if($recentLogs !== null)
                {{-- Batasi maksimal 6 log terbaru agar sejajar dengan kolom kiri --}}
                @forelse($recentLogs->take(6) as $log)
                <div class="activity-item">
                    <div class="activity-icon asset">
                        <i class="fas fa-clock-rotate-left"></i>
                    </div>
                    <div class="activity-body">
                        <div class="title">
                            {{ Str::limit($log->description ?? str_replace('_', ' ', $log->action), 52) }}
                        </div>
                        <div class="meta">
                            <i class="fas fa-calendar" style="font-size:10px;opacity:.5;"></i>
                            {{ $log->created_at->format('d M Y, H:i') }}
                            @if($log->user)
                                &nbsp;·&nbsp;
                                <i class="fas fa-user" style="font-size:10px;opacity:.5;"></i>
                                {{ $log->user->name }}
                            @endif
                        </div>
                    </div>
                    <span class="activity-time"
                          title="{{ $log->created_at->format('d M Y H:i:s') }}">
                        {{ $log->created_at->diffForHumans(null, true) }}
                    </span>
                </div>
                @empty
                <div class="empty-state" style="padding:28px 0;margin:auto;">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada aktivitas tercatat</p>
                </div>
                @endforelse

            @else
                {{-- Ringkasan kondisi aset untuk Admin Unit, Teknisi, User --}}
                <div class="activity-item">
                    <div class="activity-icon asset">
                        <i class="fas fa-circle-check"></i>
                    </div>
                    <div class="activity-body">
                        <div class="title">Aset Aktif</div>
                        <div class="meta">Kondisi baik, sedang digunakan</div>
                    </div>
                    <span class="badge badge-success" style="font-size:11px;flex-shrink:0;">
                        {{ number_format($asetAktif) }}
                    </span>
                </div>
                <div class="activity-item">
                    <div class="activity-icon repair">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                    <div class="activity-body">
                        <div class="title">Aset Rusak</div>
                        <div class="meta">Memerlukan perhatian atau perbaikan</div>
                    </div>
                    <span class="badge badge-danger" style="font-size:11px;flex-shrink:0;">
                        {{ number_format($asetRusak) }}
                    </span>
                </div>
                <div class="activity-item">
                    <div class="activity-icon" style="background:var(--warning-light);color:var(--warning);">
                        <i class="fas fa-circle-question"></i>
                    </div>
                    <div class="activity-body">
                        <div class="title">Aset Hilang</div>
                        <div class="meta">Tidak ditemukan keberadaannya</div>
                    </div>
                    <span class="badge badge-warning" style="font-size:11px;flex-shrink:0;">
                        {{ number_format($asetHilang) }}
                    </span>
                </div>
                <div class="activity-item">
                    <div class="activity-icon system">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="activity-body">
                        <div class="title">Habis Pakai</div>
                        <div class="meta">Sudah tidak dapat digunakan</div>
                    </div>
                    <span class="badge badge-secondary" style="font-size:11px;flex-shrink:0;">
                        {{ number_format($asetHabisPakai ?? 0) }}
                    </span>
                </div>
            @endif

        </div>
    </div>

</div>

@endsection