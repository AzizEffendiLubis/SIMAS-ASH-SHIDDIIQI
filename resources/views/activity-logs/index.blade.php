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
    <div class="card-body" style="padding:14px 18px;">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="filter-row">

            {{-- Search: di kolom description --}}
            <div class="search-wrap" style="flex:1;min-width:200px;">
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
            <select name="subject_type" class="form-control" style="min-width:155px;width:auto;">
                <option value="">Semua Entitas</option>
                <option value="Asset"         {{ request('subject_type') === 'Asset'         ? 'selected' : '' }}>Aset</option>
                <option value="Repair"        {{ request('subject_type') === 'Repair'        ? 'selected' : '' }}>Perbaikan</option>
                <option value="User"          {{ request('subject_type') === 'User'          ? 'selected' : '' }}>Pengguna</option>
                <option value="Unit"          {{ request('subject_type') === 'Unit'          ? 'selected' : '' }}>Unit</option>
                <option value="FundingSource" {{ request('subject_type') === 'FundingSource' ? 'selected' : '' }}>Sumber Dana</option>
            </select>

            {{-- Dari Tanggal --}}
            <div style="display:flex;flex-direction:column;gap:4px;">
                <label style="font-size:11.5px;color:var(--gray-400);font-weight:600;">Dari</label>
                <input type="date" name="dari_tanggal" class="form-control" style="width:145px;"
                    value="{{ request('dari_tanggal') }}">
            </div>

            {{-- Sampai Tanggal --}}
            <div style="display:flex;flex-direction:column;gap:4px;">
                <label style="font-size:11.5px;color:var(--gray-400);font-weight:600;">Sampai</label>
                <input type="date" name="sampai_tanggal" class="form-control" style="width:145px;"
                    value="{{ request('sampai_tanggal') }}">
            </div>

            <div style="display:flex;gap:8px;align-self:flex-end;">
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
        <span style="font-size:12px;color:var(--gray-400);">{{ $logs->total() }} log ditemukan</span>
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
                            <p style="font-size:13px;font-weight:500;color:var(--gray-700);">
                                {{ $log->created_at->format('d M Y') }}
                            </p>
                            <p style="font-size:11.5px;color:var(--gray-400);">
                                {{ $log->created_at->format('H:i:s') }}
                            </p>
                        </td>

                        <td>
                            @if($log->user)
                                {{-- relasi belongsTo User di ActivityLog model --}}
                                <p style="font-weight:600;font-size:13px;color:var(--gray-700);">{{ $log->user->name }}</p>
                                <p style="font-size:11.5px;color:var(--gray-400);">{{ $log->user->username }}</p>
                            @else
                                {{-- user_id nullable: aksi sistem / seeder / job --}}
                                <span style="font-size:12px;color:var(--gray-300);font-style:italic;">Sistem</span>
                            @endif
                        </td>

                        <td>
                            {{--
                                Badge warna per kategori aksi.
                                Aksi yang direkam: tambah_*, edit_*, login, update_*
                            --}}
                            @php
                                $ac = match(true) {
                                    str_contains($log->action, 'tambah') => ['bg'=>'#dcfce7','text'=>'#15803d'],
                                    str_contains($log->action, 'edit')   => ['bg'=>'#dbeafe','text'=>'#1d4ed8'],
                                    str_contains($log->action, 'hapus')  => ['bg'=>'#fee2e2','text'=>'#dc2626'],
                                    str_contains($log->action, 'login')  => ['bg'=>'#f3e8ff','text'=>'#7c3aed'],
                                    str_contains($log->action, 'update') => ['bg'=>'#fef9c3','text'=>'#a16207'],
                                    default                              => ['bg'=>'var(--gray-100)','text'=>'var(--gray-500)'],
                                };
                            @endphp
                            <span style="display:inline-block;padding:3px 10px;border-radius:6px;
                                font-size:12px;font-weight:600;
                                background:{{ $ac['bg'] }};color:{{ $ac['text'] }};">
                                {{ str_replace('_', ' ', $log->action) }}
                            </span>
                        </td>

                        <td>
                            {{--
                                subject_type disimpan FQCN (App\Models\Asset).
                                class_basename() → nama pendek (Asset).
                            --}}
                            @if($log->subject_type)
                            <span style="background:var(--gray-50);border:1px solid var(--gray-200);
                                border-radius:5px;padding:2px 8px;font-size:11.5px;color:var(--gray-600);">
                                {{ class_basename($log->subject_type) }}
                                @if($log->subject_id)
                                <span style="color:var(--gray-400);">#{{ $log->subject_id }}</span>
                                @endif
                            </span>
                            @else
                            <span style="color:var(--gray-300);">—</span>
                            @endif
                        </td>

                        <td style="font-size:13px;color:var(--gray-600);max-width:280px;">
                            <span style="display:-webkit-box;-webkit-line-clamp:2;
                                -webkit-box-orient:vertical;overflow:hidden;">
                                {{ $log->description ?? '-' }}
                            </span>
                        </td>

                        <td style="font-size:12px;color:var(--gray-400);font-family:monospace;">
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
                        <td colspan="7" class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <p>Tidak ada log aktivitas yang ditemukan</p>
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

@endsection