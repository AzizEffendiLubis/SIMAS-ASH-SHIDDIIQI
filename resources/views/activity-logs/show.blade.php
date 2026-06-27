@extends('layouts.app')
@section('title', 'Detail Log Aktivitas')
@section('page-title', 'Log Aktivitas')

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header-row">
    <div class="ph-left">
        <h1>Detail Log Aktivitas</h1>
        <p>ID Log: <strong style="color:var(--gray-700);">#{{ $activityLog->id }}</strong></p>
    </div>
    <div class="ph-right">
        <a href="{{ route('activity-logs.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="dash-two-col">

    {{-- ── Kolom Kiri ── --}}
    {{-- min-width:0 wajib di sini: tanpa ini, grid item ini akan melebar
         mengikuti min-content width tabel diff (min-width:580px, dari rule
         global `table { min-width:580px }` di layout.blade.php), sehingga
         card "Informasi Aktivitas" ikut terdorong dan batas kanannya tidak
         sejajar dengan tombol "Kembali" di atas. overflow-x:auto pada
         pembungkus tabel diff tidak akan berfungsi tanpa ini. --}}
    <div style="display:flex;flex-direction:column;gap:16px;min-width:0;">

        {{-- Info Utama --}}
        <div class="card">
            <div class="card-header">
                <h2>Informasi Aktivitas</h2>
            </div>
            <div class="card-body">

                <div class="form-grid" style="gap:14px;">

                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Waktu</p>
                        {{-- created_at: cast 'datetime' di ActivityLog model. $timestamps = false — tidak ada updated_at. --}}
                        <p style="font-weight:700;font-size:15px;color:var(--gray-800);">
                            {{ $activityLog->created_at->format('d M Y') }}
                        </p>
                        <p style="font-size:12.5px;color:var(--gray-400);margin-top:1px;">
                            {{ $activityLog->created_at->format('H:i:s') }}
                        </p>
                    </div>

                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">IP Address</p>
                        <p style="font-weight:500;font-family:monospace;color:var(--gray-700);">
                            {{ $activityLog->ip_address ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Pengguna</p>
                        {{-- user: relasi belongsTo User di ActivityLog model. Bisa null jika user_id null (aksi sistem). --}}
                        @if($activityLog->user)
                            <p style="font-weight:600;color:var(--gray-700);">{{ $activityLog->user->name }}</p>
                            <p style="font-size:12px;color:var(--gray-400);margin-top:1px;">
                                {{ $activityLog->user->username }}
                                {{-- role_label: accessor getRoleLabelAttribute() di User model --}}
                                &middot; {{ $activityLog->user->role_label }}
                            </p>
                        @else
                            <p style="font-size:13px;color:var(--gray-300);font-style:italic;">Sistem</p>
                        @endif
                    </div>

                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Aksi</p>
                        @php
                            // Badge warna berdasarkan prefix nama aksi, mengikuti palet .badge-* di layout.
                            // Aksi yang tercatat: tambah_aset, edit_kondisi_aset,
                            // tambah_laporan_kerusakan, update_progres_perbaikan,
                            // tambah_pengguna, edit_pengguna, tambah_unit, edit_unit,
                            // tambah_sumber_dana, edit_sumber_dana,
                            // tambah_jenis_gudang, edit_jenis_gudang
                            $actionBadge = match(true) {
                                str_contains($activityLog->action, 'tambah') => 'badge-success',
                                str_contains($activityLog->action, 'edit')   => 'badge-unit',
                                str_contains($activityLog->action, 'hapus')  => 'badge-danger',
                                str_contains($activityLog->action, 'login')  => 'badge-admin',
                                str_contains($activityLog->action, 'update') => 'badge-warning',
                                default                                      => 'badge-secondary',
                            };
                        @endphp
                        <span class="badge {{ $actionBadge }}" style="font-size:13px;padding:4px 12px;">
                            {{ str_replace('_', ' ', $activityLog->action) }}
                        </span>
                    </div>

                </div>

                @if($activityLog->description)
                <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--gray-100);">
                    <p style="font-size:12px;color:var(--gray-400);font-weight:600;margin-bottom:6px;">Deskripsi</p>
                    <p class="note-box note-box-info">
                        {{ $activityLog->description }}
                    </p>
                </div>
                @endif

            </div>
        </div>

        {{-- JSON Diff: old_data vs new_data --}}
        @if($activityLog->old_data || $activityLog->new_data)
        <div class="card">
            <div class="card-header">
                <h2>Perubahan Data</h2>
            </div>
            <div class="card-body">

                {{--
                    old_data & new_data: cast 'array' di ActivityLog model.
                    array_merge digunakan (bukan operator +) agar semua key dari kedua
                    array terkumpul tanpa ada yang tertimpa.
                --}}
                @php
                    $oldData = $activityLog->old_data ?? [];
                    $newData = $activityLog->new_data ?? [];
                    $allKeys = collect(array_keys(array_merge($oldData, $newData)))
                        ->unique()
                        ->sort()
                        ->values();
                @endphp

                @if($allKeys->isNotEmpty())
                <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;border-radius:var(--radius);max-width:100%;">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:160px;">Field</th>
                                <th>Nilai Lama</th>
                                <th>Nilai Baru</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allKeys as $key)
                                @php
                                    // Gunakan array_key_exists agar nilai null pun terdeteksi dengan benar
                                    $old     = array_key_exists($key, $oldData) ? $oldData[$key] : null;
                                    $new     = array_key_exists($key, $newData) ? $newData[$key] : null;
                                    $changed = $old !== $new;
                                @endphp
                                <tr style="{{ $changed ? 'background:var(--warning-light);' : '' }}">
                                    <td style="font-family:monospace;font-size:12.5px;font-weight:600;color:var(--gray-700);">
                                        {{ $key }}
                                        @if($changed)
                                            <span style="display:inline-block;width:6px;height:6px;border-radius:50%;
                                                background:var(--warning);margin-left:5px;vertical-align:middle;"></span>
                                        @endif
                                    </td>
                                    <td style="font-size:13px;">
                                        @if(is_null($old))
                                            <span style="color:var(--gray-300);font-style:italic;">—</span>
                                        @elseif(is_bool($old))
                                            <span style="color:{{ $old ? '#16a34a' : 'var(--danger)' }};font-weight:600;">
                                                {{ $old ? 'true' : 'false' }}
                                            </span>
                                        @elseif(is_array($old))
                                            <code style="font-size:11.5px;background:var(--gray-100);padding:2px 6px;border-radius:4px;">
                                                {{ json_encode($old, JSON_UNESCAPED_UNICODE) }}
                                            </code>
                                        @else
                                            {{ $old }}
                                        @endif
                                    </td>
                                    <td style="font-size:13px;">
                                        @if(is_null($new))
                                            <span style="color:var(--gray-300);font-style:italic;">—</span>
                                        @elseif(is_bool($new))
                                            <span style="color:{{ $new ? '#16a34a' : 'var(--danger)' }};font-weight:600;">
                                                {{ $new ? 'true' : 'false' }}
                                            </span>
                                        @elseif(is_array($new))
                                            <code style="font-size:11.5px;background:var(--gray-100);padding:2px 6px;border-radius:4px;">
                                                {{ json_encode($new, JSON_UNESCAPED_UNICODE) }}
                                            </code>
                                        @else
                                            <span style="{{ $changed ? 'font-weight:600;color:var(--primary);' : '' }}">
                                                {{ $new }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="empty-state" style="padding:28px 16px;">
                        <i class="fas fa-file-circle-question"></i>
                        <p>Tidak ada data perubahan yang tercatat</p>
                    </div>
                @endif

            </div>
        </div>
        @endif

    </div>

    {{-- ── Kolom Kanan ── --}}
    {{-- min-width:0 juga ditambahkan di sini untuk konsistensi/jaga-jaga,
         walau saat ini belum ada elemen lebar tetap di kolom kanan. --}}
    <div style="display:flex;flex-direction:column;gap:16px;min-width:0;">

        {{-- Entitas Terkait --}}
        <div class="card">
            <div class="card-header">
                <h2>Entitas Terkait</h2>
            </div>
            <div class="card-body">

                @if($activityLog->subject_type && $activityLog->subject_id)
                    @php
                        // subject_type disimpan sebagai FQCN di DB (misal "App\Models\Asset").
                        // class_basename() mengambil nama class saja → "Asset".
                        $subjectClass = class_basename($activityLog->subject_type);

                        // Resolusi route detail per jenis entitas.
                        // Unit, FundingSource, WarehouseType tidak punya route show tersendiri
                        // (dikelola di master-data.index), maka default => null.
                        $subjectRoute = match($subjectClass) {
                            'Asset'  => route('assets.show',  $activityLog->subject_id),
                            'Repair' => route('repairs.show', $activityLog->subject_id),
                            'User'   => route('users.show',   $activityLog->subject_id),
                            default  => null,
                        };

                        // Ikon Font Awesome per jenis entitas
                        $subjectIcon = match($subjectClass) {
                            'Asset'         => 'box',
                            'Repair'        => 'screwdriver-wrench',
                            'User'          => 'user',
                            'Unit'          => 'building',
                            'FundingSource' => 'money-bill',
                            'WarehouseType' => 'warehouse',
                            default         => 'database',
                        };

                        // Label bahasa Indonesia per jenis entitas
                        $subjectLabel = match($subjectClass) {
                            'Asset'         => 'Aset',
                            'Repair'        => 'Laporan Perbaikan',
                            'User'          => 'Pengguna',
                            'Unit'          => 'Unit',
                            'FundingSource' => 'Sumber Dana',
                            'WarehouseType' => 'Jenis Gudang',
                            default         => $subjectClass,
                        };
                    @endphp

                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                        <div class="stat-icon blue" style="width:40px;height:40px;font-size:16px;border-radius:10px;">
                            <i class="fas fa-{{ $subjectIcon }}"></i>
                        </div>
                        <div style="min-width:0;">
                            <p style="font-weight:600;font-size:14px;color:var(--gray-700);">{{ $subjectLabel }}</p>
                            <p style="font-size:12px;color:var(--gray-400);">ID #{{ $activityLog->subject_id }}</p>
                        </div>
                    </div>

                    @if($subjectRoute)
                        <a href="{{ $subjectRoute }}" class="btn btn-outline" style="width:100%;justify-content:center;">
                            <i class="fas fa-arrow-up-right-from-square"></i> Lihat {{ $subjectLabel }}
                        </a>
                    @else
                        {{--
                            Unit, FundingSource, WarehouseType tidak punya route show tersendiri.
                            Arahkan ke master-data.index sebagai gantinya.
                            Named route: 'master-data.index' → GET /masterdata
                        --}}
                        <a href="{{ route('master-data.index') }}" class="btn btn-outline" style="width:100%;justify-content:center;">
                            <i class="fas fa-arrow-up-right-from-square"></i> Lihat Master Data
                        </a>
                    @endif

                @else
                    <div class="empty-state" style="padding:28px 16px;">
                        <i class="fas fa-link-slash"></i>
                        <p>Tidak ada entitas terkait pada log ini</p>
                    </div>
                @endif
            </div>
        </div>

        {{--
            Navigasi prev/next.
            $prevLog dan $nextLog di-pass dari ActivityLogController::show()
            via compact('activityLog', 'prevLog', 'nextLog').
            $prevLog = log lebih LAMA (id lebih kecil)
            $nextLog = log lebih BARU (id lebih besar)
        --}}
        @if(isset($prevLog) || isset($nextLog))
        <div class="card">
            <div class="card-header">
                <h2>Navigasi</h2>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px;">

                @if(isset($nextLog))
                    <a href="{{ route('activity-logs.show', $nextLog) }}"
                        class="btn btn-outline" style="width:100%;justify-content:space-between;">
                        <span><i class="fas fa-arrow-left"></i> Log Lebih Baru</span>
                        <span style="font-size:11.5px;color:var(--gray-400);">#{{ $nextLog->id }}</span>
                    </a>
                @endif

                @if(isset($prevLog))
                    <a href="{{ route('activity-logs.show', $prevLog) }}"
                        class="btn btn-outline" style="width:100%;justify-content:space-between;">
                        <span>Log Lebih Lama <i class="fas fa-arrow-right"></i></span>
                        <span style="font-size:11.5px;color:var(--gray-400);">#{{ $prevLog->id }}</span>
                    </a>
                @endif

            </div>
        </div>
        @endif

    </div>
</div>

@push('styles')
<style>
    /* ── Kotak deskripsi log, mengikuti pola note-box di repair/show ── */
    .note-box {
        font-size: 13.5px;
        color: var(--gray-700);
        border-radius: var(--radius-sm);
        padding: 12px 14px;
        line-height: 1.7;
        word-break: break-word;
    }
    .note-box-info { background: var(--info-light); border: 1px solid #a5f3fc; }

    /* Cegah tabel diff (min-width 580px dari layout) mendorong lebar seluruh
       halaman di mobile — scroll dibatasi hanya di dalam card ini. */
    @media (max-width: 768px) {
        table { font-size: 12.5px; }
    }
</style>
@endpush

@endsection