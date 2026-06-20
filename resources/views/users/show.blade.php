@extends('layouts.app')
@section('title', 'Detail Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Detail Pengguna</h1>
        <p>Username: <strong>{{ $user->username }}</strong></p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="{{ route('users.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start;">

    {{-- ── Kolom Kiri ── --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Identitas --}}
        <div class="card">
            <div class="card-body">
                <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;
                    color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">
                    Informasi Pengguna
                </p>

                <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
                    @php
                        $avatarColor = match($user->role) {
                            'admin_utama'    => '#7c3aed',
                            'kepala_yayasan' => '#c2410c',
                            'admin_unit'     => '#2563eb',
                            'teknisi'        => '#15803d',
                            default          => '#475569',
                        };
                    @endphp
                    <div style="width:56px;height:56px;border-radius:14px;
                        background:{{ $avatarColor }};
                        display:flex;align-items:center;justify-content:center;
                        color:#fff;font-size:22px;font-weight:700;flex-shrink:0;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p style="font-weight:700;font-size:17px;margin-bottom:3px;">{{ $user->name }}</p>
                        <p style="font-size:13px;color:#64748b;">{{ $user->jabatan ?? '-' }}</p>
                    </div>
                    <div style="margin-left:auto;">
                        {{-- status_label: accessor di User model --}}
                        <span class="badge badge-{{ $user->status }}" style="font-size:13px;padding:5px 14px;">
                            {{ $user->status_label }}
                        </span>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Username</p>
                        <p style="font-weight:600;">{{ $user->username }}</p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Email</p>
                        <p style="font-weight:500;">{{ $user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">No. Telepon</p>
                        <p style="font-weight:500;">{{ $user->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Unit Kerja</p>
                        {{-- unit: relasi belongsTo Unit di User model --}}
                        <p style="font-weight:500;">{{ $user->unit->nama_unit ?? '-' }}</p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Dibuat</p>
                        <p style="font-weight:500;">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Terakhir Diperbarui</p>
                        <p style="font-weight:500;">{{ $user->updated_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hak Akses Menu --}}
        <div class="card">
            <div class="card-body">
                <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;
                    color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">
                    Hak Akses Menu
                </p>

                @if($user->isAdminUtama())
                    <p style="font-size:13px;color:#16a34a;">
                        <i class="fas fa-circle-check"></i>
                        Admin Utama memiliki akses penuh ke seluruh menu secara otomatis.
                    </p>
                @else
                    {{--
                        $allMenus di-pass dari controller show() — perlu ditambahkan ke controller.
                        menu_access: JSON array, cast 'array' di User model.
                        canAccess(): method di User model untuk cek akses per menu.
                    --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                        @foreach($allMenus as $key => $label)
                            @php $hasAccess = $user->canAccess($key); @endphp
                            <div style="display:flex;align-items:center;gap:8px;font-size:13px;
                                color:{{ $hasAccess ? '#15803d' : '#94a3b8' }};">
                                <i class="fas fa-{{ $hasAccess ? 'circle-check' : 'circle-xmark' }}"
                                   style="font-size:14px;"></i>
                                {{ $label }}
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ── Kolom Kanan ── --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Role Card --}}
        <div class="card">
            <div class="card-body">
                <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;
                    color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">
                    Role
                </p>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:44px;height:44px;border-radius:10px;
                        background:{{ $avatarColor }};
                        display:flex;align-items:center;justify-content:center;
                        color:#fff;font-size:18px;">
                        <i class="fas fa-{{ match($user->role) {
                            'admin_utama'    => 'shield-halved',
                            'kepala_yayasan' => 'building-columns',
                            'admin_unit'     => 'user-gear',
                            'teknisi'        => 'screwdriver-wrench',
                            default          => 'user',
                        } }}"></i>
                    </div>
                    <div>
                        {{-- role_label: accessor getRoleLabelAttribute() di User model --}}
                        <p style="font-weight:700;font-size:15px;">{{ $user->role_label }}</p>
                        <p style="font-size:12px;color:#64748b;">
                            @switch($user->role)
                                @case('admin_utama')    Akses penuh ke seluruh sistem @break
                                @case('kepala_yayasan') Monitoring — tidak dapat mengedit data @break
                                @case('admin_unit')     Mengelola aset unit sendiri @break
                                @case('teknisi')        Memperbarui laporan perbaikan @break
                                @default                Melaporkan kerusakan aset
                            @endswitch
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Keamanan --}}
        <div class="card">
            <div class="card-body">
                <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;
                    color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">
                    Keamanan
                </p>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <p style="font-size:13px;color:#64748b;">Wajib ganti password</p>
                        @if($user->must_change_password)
                            <span class="badge badge-warning" style="font-size:12px;">Ya</span>
                        @else
                            <span class="badge badge-success" style="font-size:12px;">Tidak</span>
                        @endif
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <p style="font-size:13px;color:#64748b;">Status akun</p>
                        <span class="badge badge-{{ $user->status }}" style="font-size:12px;">
                            {{ $user->status_label }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal nonaktifkan — sama seperti di index --}}
<div class="modal-backdrop" id="nonaktifModal">
    <div class="modal confirm-modal">
        <div class="modal-body" style="padding:28px 24px;text-align:center;">
            <div class="icon"><i class="fas fa-user-slash"></i></div>
            <h3>Nonaktifkan Pengguna</h3>
            <p>Yakin ingin menonaktifkan akun <strong id="nonaktifUserName"></strong>?</p>
            <p style="font-size:12px;color:#94a3b8;margin-top:6px;">
                Akun tidak dihapus — dapat diaktifkan kembali melalui menu edit.
            </p>
            <div style="display:flex;gap:10px;justify-content:center;margin-top:20px;">
                <form id="nonaktifForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="username" id="nonaktifUsername">
                    <input type="hidden" name="name"     id="nonaktifName">
                    <input type="hidden" name="role"     id="nonaktifRole">
                    <input type="hidden" name="status"   value="nonaktif">
                    <input type="hidden" name="unit_id"  id="nonaktifUnitId">
                    <button type="submit" class="btn btn-danger">Ya, Nonaktifkan</button>
                </form>
                <button class="btn btn-outline" onclick="closeModal('nonaktifModal')">Batal</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmNonaktif(id, name, username, role, unitId, menuAccess) {
    document.getElementById('nonaktifUserName').textContent = name;
    document.getElementById('nonaktifForm').action          = '/users/' + id;
    document.getElementById('nonaktifUsername').value       = username;
    document.getElementById('nonaktifName').value           = name;
    document.getElementById('nonaktifRole').value           = role;
    document.getElementById('nonaktifUnitId').value         = unitId || '';

    const form = document.getElementById('nonaktifForm');
    form.querySelectorAll('input[name="menu_access[]"]').forEach(el => el.remove());
    (menuAccess || []).forEach(menu => {
        const inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = 'menu_access[]';
        inp.value = menu;
        form.appendChild(inp);
    });

    openModal('nonaktifModal');
}
</script>
@endpush
@endsection