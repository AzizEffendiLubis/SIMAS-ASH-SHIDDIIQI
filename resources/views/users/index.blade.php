@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
        <h1>Manajemen Pengguna</h1>
        <p>Kelola akun pengguna dan atur hak akses sistem</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addUserModal')">
        <i class="fas fa-user-plus"></i> Tambah Pengguna
    </button>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:14px 20px;">
        <form method="GET" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
            <div class="search-bar" style="flex:1;min-width:200px;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="form-control" placeholder="Cari pengguna (username, email, nama lengkap)..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary" style="height:42px;"><i class="fas fa-search"></i></button>
            @if(request('search'))
            <a href="{{ route('users.index') }}" class="btn btn-outline" style="height:42px;">Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th style="width:90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:9px;">
                                <div style="width:30px;height:30px;border-radius:7px;background:{{ ['super_admin'=>'#7c3aed','kepala_yayasan'=>'#c2410c','admin_unit'=>'#2563eb','petugas_perbaikan'=>'#15803d','user'=>'#475569'][$user->role] ?? '#475569' }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">
                                    {{ strtoupper(substr($user->name,0,1)) }}
                                </div>
                                <span style="font-weight:600;font-size:13.5px;">{{ $user->username }}</span>
                            </div>
                        </td>
                        <td style="font-size:13.5px;">{{ $user->name }}</td>
                        <td style="font-size:13px;color:#64748b;">{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->role_badge }}">{{ $user->role_label }}</span>
                        </td>
                        <td style="font-size:13px;">{{ $user->unit_kerja }}</td>
                        <td>
                            <span class="badge badge-{{ $user->status }}">{{ ucfirst($user->status) }}</span>
                        </td>
                        <td style="font-size:12.5px;color:#94a3b8;">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <button class="btn btn-outline btn-sm btn-icon" title="Edit" onclick='editUser(@json($user), @json($allMenus))'>
                                    <i class="fas fa-pen"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <button class="btn btn-outline btn-sm btn-icon" style="color:#dc2626;" title="Hapus" onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:40px;color:#94a3b8;">
                            <i class="fas fa-users" style="font-size:32px;display:block;margin-bottom:10px;opacity:.3;"></i>
                            Tidak ada data pengguna
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:12px 20px;border-top:1px solid #f1f5f9;font-size:13px;color:#94a3b8;">
            Total: {{ $users->total() }} pengguna
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal-backdrop" id="addUserModal">
    <div class="modal" style="max-width:560px;">
        <div class="modal-header">
            <h3>Tambah Pengguna Baru</h3>
            <button class="modal-close" onclick="closeModal('addUserModal')"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Username <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="username" class="form-control" placeholder="contoh: adminsd" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email <span style="color:#dc2626;">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="email@simas.sch.id" required>
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Nama Lengkap <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Nama lengkap" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role <span style="color:#dc2626;">*</span></label>
                        <select name="role" class="form-control" id="addRole" onchange="updateMenuDefaults(this.value,'add')" required>
                            <option value="admin_unit">Admin Unit</option>
                            <option value="petugas_perbaikan">Petugas Perbaikan</option>
                            <option value="kepala_yayasan">Kepala Yayasan</option>
                            <option value="super_admin">Super Admin</option>
                            <option value="user">User Biasa</option>
                        </select>
                        <p class="form-hint" id="addRoleDesc">Dapat mengelola aset unit sendiri</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <span style="color:#dc2626;">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non-Aktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jabatan <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="jabatan" class="form-control" placeholder="Jabatan" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Unit Kerja <span style="color:#dc2626;">*</span></label>
                        <select name="unit_kerja" class="form-control" required>
                            @foreach(['TK','PAUD','SD','SMP','SMA','MA','Pondok Pesantren','Yayasan'] as $unit)
                            <option value="{{ $unit }}">{{ $unit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password <span style="color:#dc2626;">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password <span style="color:#dc2626;">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                    </div>
                </div>

                <div style="border-top:1px solid #f1f5f9;padding-top:14px;margin-top:4px;">
                    <label class="form-label">Hak Akses Menu</label>
                    <div id="addMenuAccess" style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:8px;">
                        @foreach($allMenus as $key => $label)
                        <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
                            <input type="checkbox" name="menu_access[]" value="{{ $key }}" id="add_{{ $key }}"> {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('addUserModal')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Tambah Pengguna</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal-backdrop" id="editUserModal">
    <div class="modal" style="max-width:560px;">
        <div class="modal-header">
            <h3>Edit Pengguna</h3>
            <button class="modal-close" onclick="closeModal('editUserModal')"><i class="fas fa-times"></i></button>
        </div>
        <form id="editUserForm" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Username <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="username" id="editUsername" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email <span style="color:#dc2626;">*</span></label>
                        <input type="email" name="email" id="editEmail" class="form-control" required>
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Nama Lengkap <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role <span style="color:#dc2626;">*</span></label>
                        <select name="role" id="editRole" class="form-control" required>
                            <option value="admin_unit">Admin Unit</option>
                            <option value="petugas_perbaikan">Petugas Perbaikan</option>
                            <option value="kepala_yayasan">Kepala Yayasan</option>
                            <option value="super_admin">Super Admin</option>
                            <option value="user">User Biasa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <span style="color:#dc2626;">*</span></label>
                        <select name="status" id="editStatus" class="form-control" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non-Aktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jabatan <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="jabatan" id="editJabatan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Unit Kerja <span style="color:#dc2626;">*</span></label>
                        <select name="unit_kerja" id="editUnitKerja" class="form-control" required>
                            @foreach(['TK','PAUD','SD','SMP','SMA','MA','Pondok Pesantren','Yayasan'] as $unit)
                            <option value="{{ $unit }}">{{ $unit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-control" placeholder="Password baru">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>
                </div>

                <div style="border-top:1px solid #f1f5f9;padding-top:14px;margin-top:4px;">
                    <label class="form-label">Hak Akses Menu</label>
                    <div id="editMenuAccess" style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:8px;">
                        @foreach($allMenus as $key => $label)
                        <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
                            <input type="checkbox" name="menu_access[]" value="{{ $key }}" id="edit_{{ $key }}"> {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('editUserModal')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal-backdrop" id="deleteModal">
    <div class="modal confirm-modal">
        <div class="modal-body" style="padding:28px 24px;text-align:center;">
            <div class="icon"><i class="fas fa-user-slash"></i></div>
            <h3>Hapus Pengguna</h3>
            <p>Yakin ingin menghapus akun <strong id="deleteUserName"></strong>?</p>
            <p style="font-size:12px;color:#94a3b8;margin-top:6px;">Tindakan ini tidak dapat dibatalkan.</p>
            <div style="display:flex;gap:10px;justify-content:center;margin-top:20px;">
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
                <button class="btn btn-outline" onclick="closeModal('deleteModal')">Batal</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const roleDescriptions = {
    'super_admin': 'Akses penuh ke seluruh sistem',
    'kepala_yayasan': 'Dapat melihat analytics, riwayat, dan menyetujui pengadaan',
    'admin_unit': 'Dapat mengelola aset unit sendiri',
    'petugas_perbaikan': 'Dapat melihat dan update laporan perbaikan',
    'user': 'User biasa – hanya dapat melaporkan kerusakan aset'
};

const roleDefaultMenus = {
    'super_admin': ['dashboard','daftar_aset','pengadaan_aset','persetujuan_pengadaan','perbaikan_aset','manajemen_pengguna'],
    'kepala_yayasan': ['dashboard','daftar_aset','persetujuan_pengadaan','perbaikan_aset'],
    'admin_unit': ['dashboard','daftar_aset','pengadaan_aset','perbaikan_aset'],
    'petugas_perbaikan': ['dashboard','perbaikan_aset'],
    'user': ['dashboard','perbaikan_aset']
};

function updateMenuDefaults(role, prefix) {
    const defaults = roleDefaultMenus[role] || [];
    document.querySelectorAll(`#${prefix}MenuAccess input[type=checkbox]`).forEach(cb => {
        cb.checked = defaults.includes(cb.value);
    });
    if (prefix === 'add') {
        document.getElementById('addRoleDesc').textContent = roleDescriptions[role] || '';
    }
}

function editUser(user, allMenus) {
    document.getElementById('editUserForm').action = '/users/' + user.id;
    document.getElementById('editUsername').value = user.username;
    document.getElementById('editEmail').value = user.email;
    document.getElementById('editName').value = user.name;
    document.getElementById('editRole').value = user.role;
    document.getElementById('editStatus').value = user.status;
    document.getElementById('editJabatan').value = user.jabatan || '';
    document.getElementById('editUnitKerja').value = user.unit_kerja || '';

    // Set menu access checkboxes
    const menuAccess = user.menu_access || [];
    document.querySelectorAll('#editMenuAccess input[type=checkbox]').forEach(cb => {
        cb.checked = menuAccess.includes(cb.value);
    });

    openModal('editUserModal');
}

function confirmDelete(id, name) {
    document.getElementById('deleteUserName').textContent = name;
    document.getElementById('deleteForm').action = '/users/' + id;
    openModal('deleteModal');
}

// Set defaults on role change for add form
document.getElementById('addRole').addEventListener('change', function() {
    updateMenuDefaults(this.value, 'add');
    document.getElementById('addRoleDesc').textContent = roleDescriptions[this.value] || '';
});

// Initialize defaults
updateMenuDefaults('admin_unit', 'add');
</script>
@endpush
@endsection
