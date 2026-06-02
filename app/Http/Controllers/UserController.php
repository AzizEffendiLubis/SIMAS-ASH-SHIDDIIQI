<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Unit;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Daftar menu yang tersedia di sistem.
     * menu_access disimpan sebagai JSON di kolom users.menu_access (cast array di User model).
     */
    private array $allMenus = [
        'dashboard'          => 'Dashboard',
        'daftar_aset'        => 'Daftar Aset',
        'perbaikan_aset'     => 'Perbaikan Aset',
        'manajemen_pengguna' => 'Manajemen Pengguna',
        'log_aktivitas'      => 'Log Aktivitas',
        'master_data'        => 'Master Data',
    ];

    /**
     * Rule unit_id kondisional — wajib hanya untuk role user & admin_unit.
     * Dipanggil dari store() dan update() agar tidak duplikasi logika.
     */
    private function unitRule(string $role): string
    {
        return in_array($role, ['user', 'admin_unit'])
            ? 'required|exists:units,id'
            : 'nullable|exists:units,id';
    }

    public function index(Request $request)
    {
        if (!Auth::user()->isAdminUtama()) abort(403);

        $query = User::with('unit');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('name',   'like', "%{$search}%")
                  ->orWhere('email',  'like', "%{$search}%");
            });
        }

        if ($request->filled('role'))   $query->where('role',   $request->role);
        if ($request->filled('status')) $query->where('status', $request->status);

        $users    = $query->latest()->paginate(15)->appends($request->query());
        $units    = Unit::where('is_active', true)->orderBy('nama_unit')->get();
        $allMenus = $this->allMenus;

        return view('users.index', compact('users', 'units', 'allMenus'));
    }

    public function create()
    {
        if (!Auth::user()->isAdminUtama()) abort(403);

        $units    = Unit::where('is_active', true)->orderBy('nama_unit')->get();
        $allMenus = $this->allMenus;
        $roles    = [
            'kepala_yayasan' => 'Kepala Yayasan',
            'admin_utama'    => 'Admin Utama',
            'admin_unit'     => 'Admin Unit',
            'teknisi'        => 'Teknisi',
            'user'           => 'User',
        ];

        return view('users.create', compact('units', 'allMenus', 'roles'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdminUtama()) abort(403);

        // Ambil role lebih awal agar bisa dipakai di unitRule()
        $role = $request->input('role', '');

        $request->validate([
            'username'      => 'required|string|unique:users,username|regex:/^[a-zA-Z0-9_]+$/',
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|unique:users,email',
            'phone'         => 'nullable|string|max:20',
            'jabatan'       => 'nullable|string|max:100',
            'role'          => 'required|in:kepala_yayasan,admin_utama,admin_unit,teknisi,user',

            // Wajib untuk role user & admin_unit; opsional untuk yang lain
            'unit_id'       => $this->unitRule($role),

            'status'        => 'required|in:aktif,nonaktif',
            'menu_access'   => 'nullable|array',
            'menu_access.*' => 'string|in:dashboard,daftar_aset,perbaikan_aset,manajemen_pengguna,log_aktivitas,master_data',
            'password'      => 'required|string|min:8|confirmed',
        ], [
            'username.unique'  => 'Username sudah digunakan.',
            'username.regex'   => 'Username hanya boleh berisi huruf, angka, dan underscore.',
            'email.unique'     => 'Email sudah digunakan.',
            'password.min'     => 'Password minimal 8 karakter.',
            'unit_id.required' => 'Unit wajib dipilih untuk role User dan Admin Unit.',
            'unit_id.exists'   => 'Unit yang dipilih tidak valid.',
        ]);

        $user = User::create([
            'username'             => $request->username,
            'name'                 => $request->name,
            'email'                => $request->email,
            'phone'                => $request->phone,
            'jabatan'              => $request->jabatan,
            'unit_id'              => $request->unit_id,
            'role'                 => $request->role,
            'status'               => $request->status,
            'menu_access'          => $request->menu_access ?? [],
            'password'             => $request->password,
            'must_change_password' => true,
        ]);

        ActivityLog::record(
            action:      'tambah_pengguna',
            subject:     $user,
            description: "Menambahkan pengguna {$user->name} ({$user->username}) dengan role {$user->role_label}",
            newData:     $user->only(['username', 'name', 'role', 'status', 'unit_id']),
        );

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        if (!Auth::user()->isAdminUtama()) abort(403);

        $units    = Unit::where('is_active', true)->orderBy('nama_unit')->get();
        $allMenus = $this->allMenus;
        $roles    = [
            'kepala_yayasan' => 'Kepala Yayasan',
            'admin_utama'    => 'Admin Utama',
            'admin_unit'     => 'Admin Unit',
            'teknisi'        => 'Teknisi',
            'user'           => 'User',
        ];

        return view('users.edit', compact('user', 'units', 'allMenus', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if (!Auth::user()->isAdminUtama()) abort(403);

        $role = $request->input('role', $user->role);

        $rules = [
            'username'      => 'required|string|unique:users,username,' . $user->id . '|regex:/^[a-zA-Z0-9_]+$/',
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|unique:users,email,' . $user->id,
            'phone'         => 'nullable|string|max:20',
            'jabatan'       => 'nullable|string|max:100',
            'role'          => 'required|in:kepala_yayasan,admin_utama,admin_unit,teknisi,user',

            // Wajib untuk role user & admin_unit; opsional untuk yang lain
            'unit_id'       => $this->unitRule($role),

            'status'        => 'required|in:aktif,nonaktif',
            'menu_access'   => 'nullable|array',
            'menu_access.*' => 'string|in:dashboard,daftar_aset,perbaikan_aset,manajemen_pengguna,log_aktivitas,master_data',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules, [
            'unit_id.required' => 'Unit wajib dipilih untuk role User dan Admin Unit.',
            'unit_id.exists'   => 'Unit yang dipilih tidak valid.',
        ]);

        $oldData = $user->only(['username', 'name', 'role', 'status', 'unit_id']);

        $updateData = [
            'username'    => $validated['username'],
            'name'        => $validated['name'],
            'email'       => $validated['email']   ?? null,
            'phone'       => $validated['phone']   ?? null,
            'jabatan'     => $validated['jabatan'] ?? null,
            'role'        => $validated['role'],
            'status'      => $validated['status'],
            'menu_access' => $request->menu_access ?? [],

            // Jika role tidak butuh unit, null-kan meski sebelumnya ada nilai
            // (misal: admin_unit di-ubah ke teknisi)
            'unit_id'     => in_array($validated['role'], ['user', 'admin_unit'])
                                ? ($validated['unit_id'] ?? null)
                                : null,
        ];

        if ($request->filled('password')) {
            $updateData['password']             = $request->password;
            $updateData['must_change_password'] = true;
        }

        $user->update($updateData);

        ActivityLog::record(
            action:      'edit_pengguna',
            subject:     $user,
            description: "Memperbarui data pengguna {$user->name} ({$user->username})",
            oldData:     $oldData,
            newData:     $user->fresh()->only(['username', 'name', 'role', 'status', 'unit_id']),
        );

        return redirect()->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function show(User $user)
    {
        if (!Auth::user()->isAdminUtama()) abort(403);

        $user->load('unit');
        $allMenus = $this->allMenus;

        return view('users.show', compact('user', 'allMenus'));
    }

    /**
     * Pengguna tidak dihapus — hanya dinonaktifkan via update() dengan status = 'nonaktif'.
     */
    public function destroy(User $user)
    {
        abort(403, 'Pengguna tidak dapat dihapus. Gunakan fitur nonaktifkan akun.');
    }

    public function getAllMenus(): array
    {
        return $this->allMenus;
    }
}