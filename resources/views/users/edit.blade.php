@extends('layouts.app')
@section('title', 'Edit Pengguna – ' . $user->username)
@section('page-title', 'Manajemen Pengguna')
@section('page-parent', 'Edit Pengguna')

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header-row">
    <div class="ph-left">
        <h1>Edit Pengguna</h1>
        <p>Memperbarui data akun <strong style="color:var(--gray-700);">{{ $user->username }}</strong></p>
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

<form action="{{ route('users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')
    {{-- Catatan: cukup pakai class "dash-two-col" (sudah punya rule responsive
         di layout: 2 kolom desktop, 1 kolom HP). Inline grid-template-columns
         dihapus karena selalu menang melawan @media dan menghalangi stack
         di mobile. --}}
    <div class="dash-two-col">

        {{-- ══════════════════ KOLOM KIRI ══════════════════ --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Informasi Akun --}}
            <div class="card">
                <div class="card-header">
                    <h2>Informasi Akun</h2>
                </div>
                <div class="card-body">
                    <div class="form-grid">

                        {{-- Admin Utama boleh ubah username --}}
                        <div class="form-group">
                            <label class="form-label">
                                Username (NIS/NIP) <span class="required">*</span>
                            </label>
                            <input type="text" name="username"
                                class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username', $user->username) }}">
                            <p class="form-hint">Hanya huruf, angka, dan underscore.</p>
                            @error('username') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}">
                            @error('email') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group col-span-2">
                            <label class="form-label">
                                Nama Lengkap <span class="required">*</span>
                            </label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}">
                            @error('name') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan"
                                class="form-control @error('jabatan') is-invalid @enderror"
                                placeholder="Contoh: Waka Sarpras"
                                value="{{ old('jabatan', $user->jabatan) }}">
                            @error('jabatan') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="08xx-xxxx-xxxx"
                                value="{{ old('phone', $user->phone) }}">
                            @error('phone') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Reset Password --}}
            <div class="card">
                <div class="card-header">
                    <h2>Reset Password</h2>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" style="margin-bottom:16px;">
                        <i class="fas fa-circle-info"></i>
                        <span>Kosongkan jika tidak ingin mereset password. Jika diisi,
                            pengguna wajib mengganti password saat login berikutnya.</span>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Kosongkan jika tidak direset">
                            @error('password') <p class="invalid-feedback">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation"
                                class="form-control"
                                placeholder="Ulangi password baru">
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

                    <div class="form-group">
                        <label class="form-label">
                            Role <span class="required">*</span>
                        </label>
                        <select name="role" id="roleSelect"
                            class="form-control @error('role') is-invalid @enderror"
                            onchange="handleRoleChange(this.value)">
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('role', $user->role) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <p class="invalid-feedback">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Status <span class="required">*</span>
                        </label>
                        <select name="status"
                            class="form-control @error('status') is-invalid @enderror">
                            <option value="aktif"    {{ old('status', $user->status) === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $user->status) === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        @error('status') <p class="invalid-feedback">{{ $message }}</p> @enderror
                    </div>

                    {{--
                        unit_id: wajib untuk user & admin_unit, opsional untuk yang lain.
                        Jika role diubah ke non-unit, UserController::update() akan null-kan unit_id.
                        Tanda (*) dan hint diatur JS handleRoleChange().
                    --}}
                    <div class="form-group">
                        <label class="form-label">
                            Unit Kerja
                            <span class="required" id="unitRequiredMark" style="display:none;">*</span>
                        </label>
                        <select name="unit_id" id="unitSelect"
                            class="form-control @error('unit_id') is-invalid @enderror">
                            <option value="">— Pilih Unit —</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}"
                                    {{ old('unit_id', $user->unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama_unit }}
                                    @if($unit->kode_unit) ({{ $unit->kode_unit }}) @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="form-hint" id="unitHint"></p>
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
                    <div id="menuAccessList" class="menu-access-list">
                        @foreach($allMenus as $key => $label)
                        @php $checked = in_array($key, old('menu_access', $user->menu_access ?? [])); @endphp
                        <label class="menu-access-item">
                            <input type="checkbox" name="menu_access[]"
                                value="{{ $key }}"
                                {{ $checked ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Aksi --}}
            <div class="form-actions">
                <a href="{{ route('users.show', $user) }}" class="btn btn-outline">
                    <i class="fas fa-xmark"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>

        </div>
    </div>
</form>

@push('styles')
<style>
    .menu-access-list { display: flex; flex-direction: column; gap: 10px; }
    .menu-access-item {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; cursor: pointer; color: var(--gray-700);
    }
    .menu-access-item input[type="checkbox"] {
        width: 15px; height: 15px; accent-color: var(--primary); cursor: pointer;
        flex-shrink: 0;
    }

    .form-actions { display: flex; gap: 10px; justify-content: flex-end; }

    @media (max-width: 768px) {
        .form-actions { flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@push('scripts')
<script>
// Sinkron dengan UserController::unitRule()
const ROLES_REQUIRE_UNIT = ['user', 'admin_unit'];

function handleRoleChange(role) {
    const unitSelect = document.getElementById('unitSelect');
    const unitMark   = document.getElementById('unitRequiredMark');
    const unitHint   = document.getElementById('unitHint');
    const required   = ROLES_REQUIRE_UNIT.includes(role);

    unitSelect.required    = required;
    unitMark.style.display = required ? 'inline' : 'none';
    unitHint.textContent   = required
        ? 'Wajib dipilih untuk role ini.'
        : 'Opsional untuk role ini.';
}

document.addEventListener('DOMContentLoaded', () => {
    handleRoleChange(document.getElementById('roleSelect').value);
});
</script>
@endpush

@endsection