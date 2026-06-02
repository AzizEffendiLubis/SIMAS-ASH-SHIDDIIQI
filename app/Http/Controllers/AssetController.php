<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetPhoto;
use App\Models\AssetConditionHistory;
use App\Models\ActivityLog;
use App\Models\Unit;
use App\Models\FundingSource;
use App\Models\UnitSatuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Asset::forUser($user)
            ->with(['unit', 'fundingSource', 'satuan', 'photos'])
            ->withCount('photos');

        // Search: kode_aset, nama_barang, lokasi_barang
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang',     'like', "%{$search}%")
                  ->orWhere('kode_aset',     'like', "%{$search}%")
                  ->orWhere('lokasi_barang', 'like', "%{$search}%");
            });
        }

        // Filter: kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter: unit — hanya untuk Admin Utama & Kepala Yayasan
        if ($request->filled('unit_id') && ($user->isAdminUtama() || $user->isKepalaYayasan())) {
            $query->where('unit_id', $request->unit_id);
        }

        // Filter: kondisi
        if ($request->filled('kondisi')) {
            $query->kondisi($request->kondisi);
        }

        // Sorting
        $allowedSorts = ['nama_barang', 'kode_aset', 'kondisi_barang', 'created_at', 'harga_barang'];
        $sortBy  = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'created_at';
        $sortDir = $request->get('dir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $assets = $query->paginate(15)->appends($request->query());

        // Kategori hanya dari aset yang visible oleh user (tidak bocor lintas unit)
        $categories = Asset::forUser($user)->distinct()->pluck('kategori')->filter()->sort()->values();
        $units      = Unit::where('is_active', true)->orderBy('nama_unit')->get();

        return view('assets.index', compact('assets', 'categories', 'units'));
    }

    public function create()
    {
        $user = Auth::user();

        // "Hanya Admin Utama dan Admin Unit yang bisa menambah aset."
        if (!$user->canEditAset()) abort(403);

        // Admin Unit hanya boleh memilih unitnya sendiri
        $units = $user->isAdminUtama()
            ? Unit::where('is_active', true)->orderBy('nama_unit')->get()
            : Unit::where('id', $user->unit_id)->where('is_active', true)->get();

        $fundingSources = FundingSource::where('is_active', true)->orderBy('nama_sumber')->get();
        $satuanList     = UnitSatuan::orderBy('nama_satuan')->get();
        $categories     = ['Elektronik', 'Furnitur', 'Komputer', 'Peralatan', 'Kendaraan', 'Lainnya'];

        return view('assets.create', compact('units', 'fundingSources', 'satuanList', 'categories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // "Hanya Admin Utama dan Admin Unit yang bisa menambah aset."
        if (!$user->canEditAset()) abort(403);

        $validated = $request->validate([
            'nama_barang'       => 'required|string|max:255',
            'kategori'          => 'required|string|max:100',
            'spesifikasi'       => 'nullable|string|max:500',
            'harga_barang'      => 'required|numeric|min:0',
            'tanggal_pengadaan' => 'nullable|date',
            'unit_id'           => 'required|exists:units,id',
            'jumlah_barang'     => 'required|integer|min:1',
            'satuan_id'         => 'nullable|exists:units_satuan,id',
            'sumber_dana_id'    => 'nullable|exists:funding_sources,id',
            'lokasi_barang'     => 'nullable|string|max:255',

            // "Setiap penambahan aset harus memiliki dasar penambahan."
            'keterangan_dasar'  => 'required|string',
            'keterangan'        => 'nullable|string',

            // Multi-foto: maks 5 file
            'fotos'             => 'nullable|array|max:5',
            'fotos.*'           => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Admin Unit hanya boleh menambah aset ke unitnya sendiri
        if ($user->isAdminUnit() && (int) $validated['unit_id'] !== (int) $user->unit_id) {
            abort(403, 'Anda hanya dapat menambah aset untuk unit Anda sendiri.');
        }

        DB::transaction(function () use ($validated, $request, $user) {
            // Generate kode_aset: [KODE_UNIT]-[YYYYMMDD]-[XXXX]
            // lockForUpdate() mencegah race condition saat dua request masuk bersamaan
            $unit  = Unit::lockForUpdate()->find($validated['unit_id']);
            $today = now()->format('Ymd');

            $count = Asset::where('unit_id', $unit->id)
                         ->whereDate('created_at', today())
                         ->count() + 1;

            $kodeAset = strtoupper($unit->kode_unit) . '-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            // Fallback: pastikan kode_aset unik (antisipasi race condition)
            while (Asset::where('kode_aset', $kodeAset)->exists()) {
                $count++;
                $kodeAset = strtoupper($unit->kode_unit) . '-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }

            $asset = Asset::create(array_merge($validated, [
                'kode_aset'  => $kodeAset,
                'created_by' => $user->id,
            ]));

            // Simpan foto-foto aset (multi-foto)
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $index => $foto) {
                    $path = $foto->store('assets', 'public');
                    AssetPhoto::create([
                        'asset_id'   => $asset->id,
                        'file_path'  => $path,
                        'is_primary' => $index === 0,
                    ]);
                }
            }

            // Catat riwayat kondisi awal
            AssetConditionHistory::create([
                'asset_id'     => $asset->id,
                'kondisi_lama' => null,
                'kondisi_baru' => $asset->kondisi_barang,
                'catatan'      => 'Aset pertama kali ditambahkan ke sistem.',
                'changed_by'   => $user->id,
            ]);

            // "Seluruh aktivitas penambahan aset wajib tercatat."
            ActivityLog::record(
                action:      'tambah_aset',
                subject:     $asset,
                description: "Menambahkan aset {$asset->nama_barang} ({$asset->kode_aset}) ke unit {$unit->nama_unit}",
                newData:     $asset->only([
                    'kode_aset', 'nama_barang', 'kategori',
                    'kondisi_barang', 'unit_id', 'harga_barang', 'jumlah_barang',
                ]),
            );
        });

        return redirect()->route('assets.index')
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    public function show(Asset $asset)
    {
        $this->authorizeAssetAccess($asset);

        $asset->load([
            'unit',
            'fundingSource',
            'satuan',
            'creator',
            'photos',
            'conditionHistories.changedBy',
            'repairs.photos',
        ]);

        return view('assets.show', compact('asset'));
    }

    /**
     * "Hak edit data aset dibatasi hanya untuk perubahan foto aset dan kondisi aset."
     * "Informasi utama aset tidak dapat diubah setelah disimpan."
     * Pengecualian lokasi: hanya aset unit Yayasan (is_unit_yayasan) yang boleh diubah lokasinya.
     */
    public function edit(Asset $asset)
    {
        $this->authorizeAssetEdit($asset);

        $asset->load(['unit', 'photos']);

        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $this->authorizeAssetEdit($asset);

        $user = Auth::user();

        // "Aset unit Yayasan dapat diubah lokasi penempatannya."
        // Accessor is_unit_yayasan didefinisikan di Asset model.
        $lokasiEditable = $asset->is_unit_yayasan;

        $rules = [
            // Kondisi: enum aktif|rusak|hilang|habis_pakai
            // 'dipindahkan' TIDAK ada — fitur mutasi dihapus
            'kondisi_barang' => 'required|in:aktif,rusak,hilang,habis_pakai',
            'keterangan'     => 'nullable|string',

            // Foto baru
            'fotos_baru'     => 'nullable|array|max:5',
            'fotos_baru.*'   => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            // ID foto yang ingin dihapus
            'hapus_foto'     => 'nullable|array',
            'hapus_foto.*'   => 'integer',

            // ID foto yang dijadikan foto utama
            'foto_utama_id'  => 'nullable|integer',
        ];

        if ($lokasiEditable) {
            $rules['lokasi_barang'] = 'nullable|string|max:255';
        }

        $validated = $request->validate($rules);

        // Validasi manual kepemilikan foto — mencegah manipulasi foto milik aset lain
        $ownPhotoIds = $asset->photos()->pluck('id')->toArray();

        if (!empty($validated['hapus_foto'])) {
            $invalid = array_diff($validated['hapus_foto'], $ownPhotoIds);
            if (!empty($invalid)) abort(422, 'Foto yang ingin dihapus tidak ditemukan pada aset ini.');
        }

        if (!empty($validated['foto_utama_id']) && !in_array((int) $validated['foto_utama_id'], $ownPhotoIds)) {
            abort(422, 'Foto utama yang dipilih tidak ditemukan pada aset ini.');
        }

        DB::transaction(function () use ($asset, $validated, $request, $user, $lokasiEditable) {
            $kondisiLama = $asset->kondisi_barang;
            $lokasiLama  = $asset->lokasi_barang;

            $updateData = ['kondisi_barang' => $validated['kondisi_barang']];

            if (array_key_exists('keterangan', $validated)) {
                $updateData['keterangan'] = $validated['keterangan'];
            }

            $lokasiBaru = $lokasiLama; // default tidak berubah
            if ($lokasiEditable && array_key_exists('lokasi_barang', $validated)) {
                $updateData['lokasi_barang'] = $validated['lokasi_barang'];
                $lokasiBaru = $validated['lokasi_barang'];
            }

            $asset->update($updateData);

            // Catat riwayat jika kondisi atau lokasi berubah
            $kondisiChanged = $kondisiLama !== $validated['kondisi_barang'];
            $lokasiChanged  = $lokasiEditable && ($lokasiLama !== $lokasiBaru);

            if ($kondisiChanged || $lokasiChanged) {
                AssetConditionHistory::create([
                    'asset_id'     => $asset->id,
                    'kondisi_lama' => $kondisiChanged ? $kondisiLama                    : null,
                    'kondisi_baru' => $kondisiChanged ? $validated['kondisi_barang']    : null,
                    'lokasi_lama'  => $lokasiChanged  ? $lokasiLama                    : null,
                    'lokasi_baru'  => $lokasiChanged  ? $lokasiBaru                    : null,
                    'changed_by'   => $user->id,
                ]);
            }

            // Hapus foto yang diminta
            if (!empty($validated['hapus_foto'])) {
                $toDelete = AssetPhoto::where('asset_id', $asset->id)
                    ->whereIn('id', $validated['hapus_foto'])
                    ->get();

                foreach ($toDelete as $foto) {
                    Storage::disk('public')->delete($foto->file_path);
                    $foto->delete();
                }

                // Jika foto utama ikut dihapus, tunjuk foto pertama yang tersisa
                $hasPrimary = $asset->photos()->where('is_primary', true)->exists();
                if (!$hasPrimary) {
                    $asset->photos()->oldest()->limit(1)->update(['is_primary' => true]);
                }
            }

            // Tambah foto baru
            if ($request->hasFile('fotos_baru')) {
                $existingCount = $asset->photos()->count();
                foreach ($request->file('fotos_baru') as $foto) {
                    $path = $foto->store('assets', 'public');
                    AssetPhoto::create([
                        'asset_id'   => $asset->id,
                        'file_path'  => $path,
                        'is_primary' => $existingCount === 0,
                    ]);
                    $existingCount++;
                }
            }

            // Set foto utama baru
            if (!empty($validated['foto_utama_id'])) {
                $asset->photos()->update(['is_primary' => false]);
                $asset->photos()->where('id', $validated['foto_utama_id'])->update(['is_primary' => true]);
            }

            ActivityLog::record(
                action:      'edit_kondisi_aset',
                subject:     $asset,
                description: "Memperbarui aset {$asset->nama_barang} ({$asset->kode_aset})"
                           . ($kondisiChanged ? " — kondisi: {$kondisiLama} → {$validated['kondisi_barang']}" : '')
                           . ($lokasiChanged  ? " — lokasi: {$lokasiLama} → {$lokasiBaru}" : ''),
                oldData:     ['kondisi_barang' => $kondisiLama, 'lokasi_barang' => $lokasiLama],
                newData:     ['kondisi_barang' => $asset->kondisi_barang, 'lokasi_barang' => $asset->lokasi_barang],
            );
        });

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Data aset berhasil diperbarui.');
    }

    /**
     * "Data aset tidak dapat dihapus secara permanen agar riwayat aset tetap tersimpan di dalam sistem."
     * Untuk menonaktifkan aset, ubah kondisi ke 'hilang' atau 'habis_pakai'.
     */
    public function destroy(Asset $asset)
    {
        abort(403, 'Aset tidak dapat dihapus. Ubah kondisi aset menjadi "hilang" atau "habis pakai".');
    }

    /**
     * Admin Utama & Kepala Yayasan bisa lihat semua.
     * Lainnya hanya aset unitnya sendiri.
     */
    private function authorizeAssetAccess(Asset $asset): void
    {
        $user = Auth::user();

        if ($user->isAdminUtama() || $user->isKepalaYayasan()) return;

        if ((int) $asset->unit_id !== (int) $user->unit_id) abort(403);
    }

    /**
     * Lebih ketat dari authorizeAssetAccess — Kepala Yayasan tidak bisa edit.
     * "Kepala Yayasan hanya berperan sebagai pihak monitoring."
     */
    private function authorizeAssetEdit(Asset $asset): void
    {
        $user = Auth::user();

        if (!$user->canEditAset()) abort(403);

        // Admin Unit hanya boleh edit aset unitnya sendiri
        if ($user->isAdminUnit() && (int) $asset->unit_id !== (int) $user->unit_id) {
            abort(403);
        }
    }
}