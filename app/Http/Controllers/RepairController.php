<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Repair;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RepairController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Repair::with(['asset', 'pelapor', 'teknisi']);

        if (!$user->isSuperAdmin() && !$user->iskepalayayasan()) {
            if ($user->isPetugasPerbaikan()) {
                $query->where('ditangani_oleh', $user->id);
            } else {
                $query->whereHas('asset', fn($a) => $a->where('unit_kerja', $user->unit_kerja));
            }
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_perbaikan', 'like', "%{$request->search}%")
                  ->orWhere('deskripsi_kerusakan', 'like', "%{$request->search}%")
                  ->orWhereHas('asset', fn($a) => $a->where('nama_barang', 'like', "%{$request->search}%"));
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $repairs = $query->latest()->paginate(10)->appends(request()->query());

        return view('repairs.index', compact('repairs'));
    }

    public function create()
    {
        $user = Auth::user();
        $assets = Asset::forUser($user)->where('kondisi_barang', '!=', 'Baik')->orWhere('kondisi_barang', 'Rusak Ringan')->get();
        $allAssets = Asset::forUser($user)->get();
        $teknisi = User::where('role', 'petugas_perbaikan')->where('status', 'aktif')->get();
        return view('repairs.create', compact('allAssets', 'teknisi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'deskripsi_kerusakan' => 'required|string',
            'ditangani_oleh' => 'nullable|exists:users,id',
            'foto_kerusakan' => 'nullable|image|max:2048',
        ]);

        $count = Repair::count() + 1;
        $validated['kode_perbaikan'] = 'PRB-' . date('Y') . str_pad($count, 3, '0', STR_PAD_LEFT);
        $validated['tanggal_laporan'] = today();
        $validated['dilaporkan_oleh'] = Auth::id();
        $validated['status'] = 'Pending';

        if ($request->hasFile('foto_kerusakan')) {
            $validated['foto_kerusakan'] = $request->file('foto_kerusakan')->store('repairs', 'public');
        }

        Repair::create($validated);

        // Update asset condition
        $asset = Asset::find($validated['asset_id']);
        $asset->update(['kondisi_barang' => 'Rusak Ringan']);

        return redirect()->route('repairs.index')
            ->with('success', 'Laporan perbaikan berhasil dibuat!');
    }

    public function show(Repair $repair)
    {
        $repair->load(['asset', 'pelapor', 'teknisi']);
        return view('repairs.show', compact('repair'));
    }

    public function edit(Repair $repair)
    {
        $user = Auth::user();
        $teknisi = User::where('role', 'petugas_perbaikan')->where('status', 'aktif')->get();
        $assets = Asset::forUser($user)->get();
        return view('repairs.edit', compact('repair', 'teknisi', 'assets'));
    }

    public function update(Request $request, Repair $repair)
    {
        $user = Auth::user();

        if ($user->isPetugasPerbaikan()) {
            // Teknisi can only update status and action
            $validated = $request->validate([
                'status' => 'required|in:Pending,Sedang Diperbaiki,Selesai',
                'tindakan_perbaikan' => 'required|string',
                'biaya_perbaikan' => 'nullable|numeric|min:0',
            ]);

            if ($validated['status'] === 'Selesai') {
                $validated['tanggal_selesai'] = today();
                // Update asset condition back to good
                $repair->asset->update(['kondisi_barang' => 'Baik']);
            }
        } else {
            $validated = $request->validate([
                'asset_id' => 'required|exists:assets,id',
                'deskripsi_kerusakan' => 'required|string',
                'status' => 'required|in:Pending,Sedang Diperbaiki,Selesai',
                'tindakan_perbaikan' => 'nullable|string',
                'ditangani_oleh' => 'nullable|exists:users,id',
                'biaya_perbaikan' => 'nullable|numeric|min:0',
            ]);

            if ($validated['status'] === 'Selesai' && !$repair->tanggal_selesai) {
                $validated['tanggal_selesai'] = today();
                $repair->asset->update(['kondisi_barang' => 'Baik']);
            }
        }

        $repair->update($validated);

        return redirect()->route('repairs.index')
            ->with('success', 'Data perbaikan berhasil diperbarui!');
    }

    public function destroy(Repair $repair)
    {
        if ($repair->foto_kerusakan) {
            Storage::disk('public')->delete($repair->foto_kerusakan);
        }
        $repair->delete();
        return redirect()->route('repairs.index')
            ->with('success', 'Data perbaikan berhasil dihapus!');
    }
}
