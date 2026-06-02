@extends('layouts.app')
@section('title', 'Tambah Pengguna')
@section('page-title', 'Manajemen Pengguna')
@section('page-parent', 'Tambah Pengguna')

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header-row">
    <div class="ph-left">
        <h1>Tambah Pengguna Baru</h1>
        <p>Buat akun pengguna baru untuk mengakses sistem</p>
    </div>
    <div class="ph-right">
        <a href="{{ route('users.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- Error summary --}}
@if($errors->any())
<div class="alert alert-error">
    <i class="fas fa-triangle-exclamation"></i>
    <div>
        <p style="font-weight:700;margin-bottom:4px;">Terdapat kesalahan:</p>
        <ul style="margin:0;padding-left:16px;">
            @foreach($errors->all() as $error)
                <li style="font-size:13px;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form action="{{ route('users.store') }}" method="POST">
    @csrf
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:18px;align-items:start;" class="dash-two-col">

        {{-- ══════════════════ KOLOM KIRI ══════════════════ --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Informasi Akun --}}
            <div class="card">
                <div class="card-header">
                    <h2>Informasi Akun</h2>
                </div>
                <div class="card-body">
                    <div class="form-grid">

                        {{-- username: NIS/NIP — terkunci dari sisi pengguna --}}
                        <div class="form-group">
                            <label class="form-label">
                                Username (NIS/NIP) <span class="required">*</span>
                            </label>
                            <input type="text" name="username"
                                class="form-control @error('username') is-invalid @enderror"
                                placeholder="Contoh: 2024001 atau admin_sd"
                                value="{{ old('username') }}" autofocus>
                            <p class="form-hint">Hanya huruf, angka, dan underscore. Tidak dapat diubah pengguna.</p>
                            @error('username') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="email@sekolah.sch.id"
                                value="{{ old('email') }}">
                            @error('email') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </div>

                        {{-- name: nama lengkap — terkunci dari sisi pengguna --}}
                        <div class="form-group col-span-2">
                            <label class="form-label">
                                Nama Lengkap <span class="required">*</span>
                            </label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Nama lengkap sesuai dokumen"
                                value="{{ old('name') }}">
                            @error('name') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan"
                                class="form-control @error('jabatan') is-invalid @enderror"
                                placeholder="Contoh: Waka Sarpras"
                                value="{{ old('jabatan') }}">
                            @error('jabatan') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="08xx-xxxx-xxxx"
                                value="{{ old('phone') }}">
                            @error('phone') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Password --}}
            <div class="card">
                <div class="card-header">
                    <h2>Password</h2>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" style="margin-bottom:16px;">
                        <i class="fas fa-circle-info"></i>
                        <span>Pengguna wajib mengganti password saat pertama kali login.</span>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                Password <span class="required">*</span>
                            </label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Min. 8 karakter">
                            @error('password') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                Konfirmasi Password <span class="required">*</span>
                            </label>
                            <input type="password" name="password_confirmation"
                                class="form-control"
                                placeholder="Ulangi password">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ══════════════════ KOLOM KANAN ══════════════════ --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Role & Unit --}}
            <div class="card">
                <div class="card-header">
                    <h2>Role &amp; Unit</h2>
                </div>
                <div class="card-body">

                    {{-- role: enum sesuai migration & User model --}}
                    <div class="form-group">
                        <label class="form-label">
                            Role <span class="required">*</span>
                        </label>
                        <select name="role" id="roleSelect"
                            class="form-control @error('role') is-invalid @enderror"
                            onchange="handleRoleChange(this.value)">
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('role', 'admin_unit') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <p class="form-hint" id="roleDesc"></p>
                        @error('role') <p class="invalid-feedback">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Status <span class="required">*</span>
                        </label>
                        <select name="status"
                            class="form-control @error('status') is-invalid @enderror">
                            <option value="aktif"    {{ old('status', 'aktif') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        @error('status') <p class="invalid-feedback">{{ $message }}</p> @enderror
                    </div>

                    {{--
                        unit_id: wajib untuk role user & admin_unit
                        opsional untuk kepala_yayasan, admin_utama, teknisi.
                        Validasi server-side ada di UserController::unitRule().
                        Tanda (*) dan hint diatur JS handleRoleChange().
                    --}}
                    <div class="form-group" id="unitFieldWrap">
                        <label class="form-label" id="unitLabel">
                            Unit Kerja
                            <span class="required" id="unitRequiredMark" style="display:none;">*</span>
                        </label>
                        <select name="unit_id" id="unitSelect"
                            class="form-control @error('unit_id') is-invalid @enderror">
                            <option value="">— Pilih Unit —</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}"
                                    {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama_unit }}
                                    @if($unit->kode_unit) ({{ $unit->kode_unit }}) @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="form-hint" id="unitHint">Opsional untuk role ini.</p>
                        @error('unit_id') <p class="invalid-feedback">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>

            {{-- Hak Akses Menu --}}
            <div class="card">
                <div class="card-header">
                    <h2>Hak Akses Menu</h2>
                </div>
                <div class="card-body">
                    <p style="font-size:12px;color:var(--gray-400);margin-bottom:12px;">
                        Admin Utama otomatis mendapat akses penuh.
                    </p>
                    {{--
                        menu_access: JSON array, cast 'array' di User model.
                        $allMenus dari UserController — 6 menu aktif.
                    --}}
                    <div id="menuAccessList" style="display:flex;flex-direction:column;gap:10px;">
                        @foreach($allMenus as $key => $label)
                        <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;
                               color:var(--gray-700);">
                            <input type="checkbox" name="menu_access[]"
                                value="{{ $key }}"
                                style="width:15px;height:15px;accent-color:var(--primary);cursor:pointer;"
                                {{ in_array($key, old('menu_access', [])) ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Aksi --}}
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <a href="{{ route('users.index') }}" class="btn btn-outline">
                    <i class="fas fa-xmark"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Tambah Pengguna
                </button>
            </div>

        </div>
    </div>
</form>

@push('scripts')
<script>
// Sinkron dengan UserController::unitRule()
const ROLES_REQUIRE_UNIT = ['user', 'admin_unit'];

const ROLE_DESC = {
    'admin_utama':    'Akses penuh ke seluruh sistem',
    'kepala_yayasan': 'Monitoring — lihat dashboard, aset, dan log aktivitas',
    'admin_unit':     'Mengelola aset unit sendiri',
    'teknisi':        'Melihat dan memperbarui laporan perbaikan',
    'user':           'Hanya dapat melaporkan kerusakan aset',
};

const ROLE_DEFAULT_MENUS = {
    'admin_utama':    ['dashboard','daftar_aset','perbaikan_aset','manajemen_pengguna','log_aktivitas','master_data'],
    'kepala_yayasan': ['dashboard','daftar_aset','perbaikan_aset','log_aktivitas'],
    'admin_unit':     ['dashboard','daftar_aset','perbaikan_aset'],
    'teknisi':        ['dashboard','perbaikan_aset'],
    'user':           ['dashboard','perbaikan_aset'],
};

function handleRoleChange(role) {
    // ── Unit field ──
    const unitSelect = document.getElementById('unitSelect');
    const unitMark   = document.getElementById('unitRequiredMark');
    const unitHint   = document.getElementById('unitHint');
    const required   = ROLES_REQUIRE_UNIT.includes(role);

    unitSelect.required      = required;
    unitMark.style.display   = required ? 'inline' : 'none';
    unitHint.textContent     = required
        ? 'Wajib dipilih untuk role ini.'
        : 'Opsional untuk role ini.';

    if (!required) unitSelect.value = '';

    // ── Role description ──
    document.getElementById('roleDesc').textContent = ROLE_DESC[role] || '';

    // ── Default menu checkboxes ──
    const defaults = ROLE_DEFAULT_MENUS[role] || [];
    document.querySelectorAll('#menuAccessList input[type=checkbox]').forEach(cb => {
        cb.checked = defaults.includes(cb.value);
    });
}

// Init saat halaman load
document.addEventListener('DOMContentLoaded', () => {
    handleRoleChange(document.getElementById('roleSelect').value);
});
</script>
@endpush

@endsection