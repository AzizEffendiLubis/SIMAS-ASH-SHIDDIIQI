<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Asset::forUser($user)->with('creator');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', "%{$request->search}%")
                  ->orWhere('kode_barang', 'like', "%{$request->search}%")
                  ->orWhere('lokasi_barang', 'like', "%{$request->search}%");
            });
        }

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->unit_kerja) {
            $query->where('unit_kerja', $request->unit_kerja);
        }

        if ($request->kondisi) {
            $query->where('kondisi_barang', $request->kondisi);
        }

        $assets = $query->latest()->paginate(10)->appends(request()->query());

        $categories = Asset::distinct()->pluck('kategori');
        $units = Asset::distinct()->pluck('unit_kerja');

        return view('assets.index', compact('assets', 'categories', 'units'));
    }

    public function create()
    {
        $units = ['TK', 'SD', 'SMP', 'SMA', 'MA', 'Pondok Pesantren'];
        $categories = ['Elektronik', 'Furnitur', 'Komputer', 'Peralatan', 'Kendaraan', 'Lainnya'];
        return view('assets.create', compact('units', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string',
            'lokasi_barang' => 'required|string|max:255',
            'unit_kerja' => 'required|string',
            'jumlah_barang' => 'required|integer|min:1',
            'kondisi_barang' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'sumber_dana' => 'required|string',
            'harga_barang' => 'required|numeric|min:0',
            'tanggal_pengadaan' => 'required|date',
            'foto' => 'nullable|image|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        // Generate kode barang
        $unitPrefix = strtoupper(substr($validated['unit_kerja'], 0, 3));
        $count = Asset::where('unit_kerja', $validated['unit_kerja'])->count() + 1;
        $validated['kode_barang'] = $unitPrefix . '-' . date('Y') . str_pad($count, 3, '0', STR_PAD_LEFT);
        $validated['created_by'] = Auth::id();

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('assets', 'public');
        }

        Asset::create($validated);

        return redirect()->route('assets.index')
            ->with('success', 'Aset berhasil ditambahkan!');
    }

    public function show(Asset $asset)
    {
        $this->authorizeAsset($asset);
        $repairs = $asset->repairs()->with('teknisi')->latest()->get();
        return view('assets.show', compact('asset', 'repairs'));
    }

    public function edit(Asset $asset)
    {
        $this->authorizeAsset($asset);
        $units = ['TK', 'PAUD', 'SD', 'SMP', 'SMA', 'MA', 'Pondok Pesantren'];
        $categories = ['Elektronik', 'Furnitur', 'Komputer', 'Peralatan', 'Kendaraan', 'Lainnya'];
        return view('assets.edit', compact('asset', 'units', 'categories'));
    }

    public function update(Request $request, Asset $asset)
    {
        $this->authorizeAsset($asset);

        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string',
            'lokasi_barang' => 'required|string|max:255',
            'unit_kerja' => 'required|string',
            'jumlah_barang' => 'required|integer|min:1',
            'kondisi_barang' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'sumber_dana' => 'required|string',
            'harga_barang' => 'required|numeric|min:0',
            'tanggal_pengadaan' => 'required|date',
            'foto' => 'nullable|image|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            if ($asset->foto) Storage::disk('public')->delete($asset->foto);
            $validated['foto'] = $request->file('foto')->store('assets', 'public');
        }

        $asset->update($validated);

        return redirect()->route('assets.index')
            ->with('success', 'Aset berhasil diperbarui!');
    }

    public function destroy(Asset $asset)
    {
        $this->authorizeAsset($asset);

        if ($asset->foto) {
            Storage::disk('public')->delete($asset->foto);
        }

        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', 'Aset berhasil dihapus!');
    }

    private function authorizeAsset(Asset $asset)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && !$user->iskepalayayasan()) {
            if ($asset->unit_kerja !== $user->unit_kerja) {
                abort(403);
            }
        }
    }
}
