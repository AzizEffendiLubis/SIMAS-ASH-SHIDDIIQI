<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    private array $allMenus = [
        'dashboard' => 'Dashboard',
        'daftar_aset' => 'Daftar Aset',
        'pengadaan_aset' => 'Pengadaan Aset',
        'persetujuan_pengadaan' => 'Persetujuan Pengadaan',
        'perbaikan_aset' => 'Perbaikan Aset',
        'manajemen_pengguna' => 'Manajemen Pengguna',
    ];

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', "%{$request->search}%")
                  ->orWhere('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->latest()->paginate(10)->appends(request()->query());
        $allMenus = $this->allMenus;

        return view('users.index', compact('users', 'allMenus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username|regex:/^[a-zA-Z0-9_]+$/',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'jabatan' => 'required|string',
            'unit_kerja' => 'required|string',
            'role' => 'required|in:super_admin,kepala_yayasan,admin_unit,petugas_perbaikan,user',
            'status' => 'required|in:aktif,nonaktif',
            'menu_access' => 'nullable|array',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'unit_kerja' => $request->unit_kerja,
            'role' => $request->role,
            'status' => $request->status,
            'menu_access' => $request->menu_access ?? [],
            'password' => $request->password,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        \Log::info('Update user request', [
            'user_id' => $user->id,
            'auth_user_id' => auth()->id(),
            'auth_role' => auth()->user()->role,
            'old_status' => $user->status,
            'new_status' => $request->status,
            'status_changed' => $request->status !== $user->status,
            'request_data' => $request->all(),
        ]);

        // Only super_admin can change status and role
        if ($request->filled('status') && $request->status !== $user->status) {
            if (!auth()->user()->isSuperAdmin()) {
                \Log::warning('Unauthorized status change attempt', ['user_id' => auth()->id(), 'role' => auth()->user()->role]);
                return back()->with('error', 'Hanya super admin yang dapat mengubah status pengguna.');
            }
        }

        if ($request->filled('role') && $request->role !== $user->role) {
            if (!auth()->user()->isSuperAdmin()) {
                \Log::warning('Unauthorized role change attempt', ['user_id' => auth()->id(), 'role' => auth()->user()->role]);
                return back()->with('error', 'Hanya super admin yang dapat mengubah role pengguna.');
            }
        }

        try {
            $rules = [
                'username' => 'required|string|unique:users,username,' . $user->id . '|regex:/^[a-zA-Z0-9_]+$/',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'jabatan' => 'required|string',
                'unit_kerja' => 'required|string',
                'role' => 'required|in:super_admin,kepala_yayasan,admin_unit,petugas_perbaikan,user',
                'status' => 'required|in:aktif,nonaktif',
                'menu_access' => 'nullable|array',
            ];

            // Only validate password if it's being changed
            if ($request->filled('password')) {
                $rules['password'] = 'required|string|min:8|confirmed';
                $rules['password_confirmation'] = 'required|string';
            }

            $validated = $request->validate($rules);

            \Log::info('Validation passed', ['validated_data' => $validated]);

            // Manually set the fields
            $user->username = $validated['username'];
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->jabatan = $validated['jabatan'];
            $user->unit_kerja = $validated['unit_kerja'];
            $user->role = $validated['role'];
            $user->status = $validated['status'];
            $user->menu_access = $request->menu_access ?? [];

            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            $saved = $user->save();
            
            \Log::info('User update completed', [
                'user_id' => $user->id,
                'save_result' => $saved,
                'new_status' => $user->fresh()->status,
            ]);

            return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Error updating user', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
            throw $e;
        }
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus!');
    }

    public function getAllMenus(): array
    {
        return $this->allMenus;
    }
}
