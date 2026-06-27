@extends('layouts.app')
@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas')

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header-row">
    <div class="ph-left">
        <h1>Log Aktivitas Sistem</h1>
        <p>Riwayat seluruh aktivitas yang tercatat dalam sistem</p>
    </div>
    {{--
        Log tidak bisa dihapus.
        Dokumen: "Log aktivitas tidak dapat dihapus — merupakan audit trail sistem."
        ActivityLogController::destroy() selalu abort(403).
    --}}
</div>

{{-- ── Filters ── --}}
<div class="card mb-16">
    <div class="card-body filter-card-body">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="filter-row">

            {{-- Search: di kolom description --}}
            <div class="search-wrap">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" name="search" class="form-control"
                    placeholder="Cari deskripsi aktivitas..."
                    value="{{ request('search') }}">
            </div>

            {{--
                Filter subject_type.
                Controller menyimpan FQCN, query pakai LIKE '%NamaClass%'
                sehingga nama class pendek sudah cukup.
            --}}
            <select name="subject_type" class="form-control">
                <option value="">Semua Entitas</option>
                <option value="Asset"         {{ request('subject_type') === 'Asset'         ? 'selected' : '' }}>Aset</option>
                <option value="Repair"        {{ request('subject_type') === 'Repair'        ? 'selected' : '' }}>Perbaikan</option>
                <option value="User"          {{ request('subject_type') === 'User'          ? 'selected' : '' }}>Pengguna</option>
                <option value="Unit"          {{ request('subject_type') === 'Unit'          ? 'selected' : '' }}>Unit</option>
                <option value="FundingSource" {{ request('subject_type') === 'FundingSource' ? 'selected' : '' }}>Sumber Dana</option>
            </select>

            {{-- Dari Tanggal --}}
            <div class="filter-date-group">
                <label>Dari</label>
                <input type="date" name="dari_tanggal" class="form-control"
                    value="{{ request('dari_tanggal') }}">
            </div>

            {{-- Sampai Tanggal --}}
            <div class="filter-date-group">
                <label>Sampai</label>
                <input type="date" name="sampai_tanggal" class="form-control"
                    value="{{ request('sampai_tanggal') }}">
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'subject_type', 'dari_tanggal', 'sampai_tanggal']))
                <a href="{{ route('activity-logs.index') }}" class="btn btn-outline">
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
        <h2>Riwayat Aktivitas</h2>
        <span class="text-muted" style="font-size:12px;">{{ $logs->total() }} log ditemukan</span>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:155px;">Waktu</th>
                        <th style="width:140px;">Pengguna</th>
                        <th style="width:170px;">Aksi</th>
                        <th style="width:130px;">Entitas</th>
                        <th>Deskripsi</th>
                        <th style="width:120px;">IP Address</th>
                        <th style="width:56px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td style="white-space:nowrap;">
                            {{-- created_at di-cast 'datetime'; $timestamps=false di model --}}
                            <p class="log-time-date">{{ $log->created_at->format('d M Y') }}</p>
                            <p class="log-time-clock">{{ $log->created_at->format('H:i:s') }}</p>
                        </td>

                        <td>
                            @if($log->user)
                                {{-- relasi belongsTo User di ActivityLog model --}}
                                <p class="log-user-name">{{ $log->user->name }}</p>
                                <p class="log-user-username">{{ $log->user->username }}</p>
                            @else
                                {{-- user_id nullable: aksi sistem / seeder / job --}}
                                <span class="log-user-system">Sistem</span>
                            @endif
                        </td>

                        <td>
                            {{--
                                Badge warna per kategori aksi.
                                Aksi yang direkam: tambah_*, edit_*, login, update_*
                            --}}
                            <span class="action-badge action-{{ \Illuminate\Support\Str::contains($log->action, 'tambah') ? 'tambah'
                                : (\Illuminate\Support\Str::contains($log->action, 'edit') ? 'edit'
                                : (\Illuminate\Support\Str::contains($log->action, 'hapus') ? 'hapus'
                                : (\Illuminate\Support\Str::contains($log->action, 'login') ? 'login'
                                : (\Illuminate\Support\Str::contains($log->action, 'update') ? 'update' : 'default')))) }}">
                                {{ str_replace('_', ' ', $log->action) }}
                            </span>
                        </td>

                        <td>
                            {{--
                                subject_type disimpan FQCN (App\Models\Asset).
                                class_basename() → nama pendek (Asset).
                            --}}
                            @if($log->subject_type)
                            <span class="subject-tag">
                                {{ class_basename($log->subject_type) }}
                                @if($log->subject_id)
                                <span class="text-muted">#{{ $log->subject_id }}</span>
                                @endif
                            </span>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td class="log-description">
                            <span class="log-description-clamp">
                                {{ $log->description ?? '-' }}
                            </span>
                        </td>

                        <td class="log-ip">
                            {{ $log->ip_address ?? '-' }}
                        </td>

                        <td>
                            {{-- Route: GET /activity-logs/{activityLog} → activity-logs.show --}}
                            <a href="{{ route('activity-logs.show', $log) }}"
                               class="btn btn-outline btn-sm btn-icon" title="Lihat detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list"></i>
                                <p>Tidak ada log aktivitas yang ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination + total --}}
        @if($logs->hasPages())
        <div class="card-footer">
            <div class="pagination">
                {{-- appends() agar filter terbawa saat pindah halaman --}}
                {{ $logs->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .filter-card-body { padding: 14px 18px; }

    .filter-date-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .filter-date-group label {
        font-size: 11.5px;
        color: var(--gray-400);
        font-weight: 600;
    }
    .filter-date-group .form-control { width: 145px; height: 38px; }

    .filter-actions { display: flex; gap: 8px; align-self: flex-end; }

    /* ── Kolom Waktu ── */
    .log-time-date  { font-size: 13px; font-weight: 500; color: var(--gray-700); }
    .log-time-clock { font-size: 11.5px; color: var(--gray-400); }

    /* ── Kolom Pengguna ── */
    .log-user-name     { font-weight: 600; font-size: 13px; color: var(--gray-700); }
    .log-user-username { font-size: 11.5px; color: var(--gray-400); }
    .log-user-system    { font-size: 12px; color: var(--gray-300); font-style: italic; }

    /* ── Badge Aksi ── */
    .action-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
    .action-badge.action-tambah  { background: #dcfce7; color: #15803d; }
    .action-badge.action-edit    { background: #dbeafe; color: #1d4ed8; }
    .action-badge.action-hapus   { background: #fee2e2; color: #dc2626; }
    .action-badge.action-login   { background: #f3e8ff; color: #7c3aed; }
    .action-badge.action-update  { background: #fef9c3; color: #a16207; }
    .action-badge.action-default { background: var(--gray-100); color: var(--gray-500); }

    /* ── Tag Entitas ── */
    .subject-tag {
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: 5px;
        padding: 2px 8px;
        font-size: 11.5px;
        color: var(--gray-600);
    }

    /* ── Deskripsi (clamp 2 baris) ── */
    .log-description { font-size: 13px; color: var(--gray-600); max-width: 280px; }
    .log-description-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* ── IP Address ── */
    .log-ip { font-size: 12px; color: var(--gray-400); font-family: monospace; }

    @media (max-width: 768px) {
        .filter-date-group .form-control { width: 100%; }
        .filter-actions { align-self: stretch; }
    }
</style>
@endpush

@endsection