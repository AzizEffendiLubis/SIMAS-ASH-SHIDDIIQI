<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Repair;
use App\Models\RepairPhoto;
use App\Models\AssetConditionHistory;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RepairController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Repair::forUser($user)->with(['pelapor', 'asset', 'teknisi']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_perbaikan',       'like', "%{$search}%")
                  ->orWhere('deskripsi_kerusakan', 'like', "%{$search}%")
                  ->orWhere('nama_aset_laporan',   'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->status($request->status);
        }

        $sortDir = $request->get('sort') === 'terlama' ? 'asc' : 'desc';
        $query->orderBy('tanggal_laporan', $sortDir);

        $repairs = $query->paginate(15)->appends($request->query());

        return view('repairs.index', compact('repairs'));
    }

    public function create()
    {
        $user = Auth::user();

        // Aset untuk autocomplete — difilter per unit/role.
        // Admin Unit & User: hanya aset di unit mereka (unit_id = $user->unit_id).
        // Admin Utama & Kepala Yayasan: semua aset lintas unit.
        // Hanya kondisi aktif & rusak — tidak masuk akal lapor aset hilang/habis_pakai.
        $assetsQuery = Asset::orderBy('nama_barang')
            ->whereIn('kondisi_barang', ['aktif', 'rusak']);

        if ($user->unit_id && !$user->isAdminUtama() && !$user->isKepalaYayasan()) {
            $assetsQuery->where('unit_id', $user->unit_id);
        }

        $assets = $assetsQuery->get(['id', 'nama_barang', 'kode_aset', 'lokasi_barang', 'unit_id']);

        return view('repairs.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Selalu wajib — baik pilih dari autocomplete maupun tulis manual
            'nama_aset_laporan'   => 'required|string|max:255',

            // Opsional — diisi JS hanya jika pengguna pilih dari saran autocomplete.
            // Kosong = laporan manual tanpa keterkaitan ke aset terdaftar.
            'asset_id'            => 'nullable|exists:assets,id',

            'deskripsi_kerusakan' => 'required|string',
            'lokasi_kerusakan'    => 'nullable|string|max:255',
            'fotos'               => 'required|array|min:1|max:5',
            'fotos.*'             => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Double-check server-side: pastikan asset_id yang dikirim memang
        // dari unit pengguna (tidak bisa di-bypass dari luar autocomplete UI)
        if (!empty($validated['asset_id'])) {
            $user  = Auth::user();
            $asset = Asset::findOrFail($validated['asset_id']);

            if ($user->unit_id && !$user->isAdminUtama() && !$user->isKepalaYayasan()) {
                if ($asset->unit_id !== $user->unit_id) {
                    abort(403, 'Anda hanya dapat melaporkan aset dari unit Anda sendiri.');
                }
            }

            // Auto-isi lokasi dari aset jika tidak diisi manual
            if (empty($validated['lokasi_kerusakan'])) {
                $validated['lokasi_kerusakan'] = $asset->lokasi_barang;
            }
        }

        DB::transaction(function () use ($validated, $request) {
            $user = Auth::user();

            $today = now()->format('Ymd');
            $count = Repair::whereDate('tanggal_laporan', today())->count() + 1;

            $kodePerbaikan = 'LAP-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            while (Repair::where('kode_perbaikan', $kodePerbaikan)->exists()) {
                $count++;
                $kodePerbaikan = 'LAP-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }

            $repair = Repair::create([
                'kode_perbaikan'      => $kodePerbaikan,
                'nama_aset_laporan'   => $validated['nama_aset_laporan'],
                'asset_id'            => $validated['asset_id'] ?? null,
                'deskripsi_kerusakan' => $validated['deskripsi_kerusakan'],
                'lokasi_kerusakan'    => $validated['lokasi_kerusakan'] ?? null,
                'status'              => 'pending',
                'tanggal_laporan'     => now(),
                'dilaporkan_oleh'     => $user->id,
                'ditangani_oleh'      => null,
            ]);

            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $path = $foto->store('repairs', 'public');
                    RepairPhoto::create([
                        'repair_id' => $repair->id,
                        'file_path' => $path,
                    ]);
                }
            }

            ActivityLog::record(
                action:      'tambah_laporan_kerusakan',
                subject:     $repair,
                description: "Melaporkan kerusakan: {$repair->nama_aset_laporan} ({$repair->kode_perbaikan})",
                newData:     $repair->only([
                    'kode_perbaikan', 'nama_aset_laporan',
                    'deskripsi_kerusakan', 'status', 'dilaporkan_oleh',
                ]),
            );
        });

        return redirect()->route('repairs.index')
            ->with('success', 'Laporan kerusakan berhasil dikirim.');
    }

    public function show(Repair $repair)
    {
        $user = Auth::user();

        $repair->load(['pelapor.unit', 'asset.unit', 'photos']);

        // Teknisi hanya dimuat untuk Admin Utama dan Teknisi yang menangani
        $showTeknisi = $user->isAdminUtama() || $user->isTeknisi();
        if ($showTeknisi) {
            $repair->load('teknisi');
        }

        return view('repairs.show', compact('repair', 'showTeknisi'));
    }

    public function edit(Repair $repair)
    {
        $user = Auth::user();

        if ($user->isTeknisi() && $repair->ditangani_oleh !== $user->id) {
            abort(403);
        }

        if (!$user->isAdminUtama() && !$user->isTeknisi()) {
            abort(403);
        }

        $teknisiList = null;
        if ($user->isAdminUtama()) {
            $teknisiList = User::where('role', 'teknisi')
                ->where('status', 'aktif')
                ->orderBy('name')
                ->get();
        }

        // "FK opsional ke assets — bisa dikaitkan admin setelah verifikasi."
        $assets = null;
        if ($user->isAdminUtama()) {
            $assets = Asset::orderBy('nama_barang')->get(['id', 'nama_barang', 'kode_aset']);
        }

        $repair->load(['pelapor', 'photos', 'teknisi', 'asset']);

        return view('repairs.edit', compact('repair', 'teknisiList', 'assets'));
    }

    public function update(Request $request, Repair $repair)
    {
        $user = Auth::user();

        if (!$user->isAdminUtama() && !$user->isTeknisi()) {
            abort(403);
        }

        DB::transaction(function () use ($request, $repair, $user) {
            $statusLama = $repair->status;

            if ($user->isTeknisi()) {
                if ($repair->ditangani_oleh !== $user->id) abort(403);

                $validated = $request->validate([
                    'status'             => 'required|in:sedang_diperbaiki,selesai',
                    'tindakan_perbaikan' => 'required|string',
                    'biaya_perbaikan'    => 'nullable|numeric|min:0',
                ]);

                $updateData = [
                    'status'             => $validated['status'],
                    'tindakan_perbaikan' => $validated['tindakan_perbaikan'],
                    'biaya_perbaikan'    => $validated['biaya_perbaikan'] ?? null,
                ];

                if ($validated['status'] === 'selesai') {
                    $updateData['tanggal_selesai'] = today();

                    if ($repair->asset) {
                        $kondisiAsetLama = $repair->asset->kondisi_barang;
                        $repair->asset->update(['kondisi_barang' => 'aktif']);

                        AssetConditionHistory::create([
                            'asset_id'     => $repair->asset->id,
                            'kondisi_lama' => $kondisiAsetLama,
                            'kondisi_baru' => 'aktif',
                            'catatan'      => "Perbaikan selesai — {$repair->kode_perbaikan}",
                            'changed_by'   => $user->id,
                        ]);
                    }
                }

                $repair->update($updateData);

            } else {
                if (!$user->isAdminUtama()) abort(403);

                $validated = $request->validate([
                    'nama_aset_laporan'   => 'required|string|max:255',
                    'deskripsi_kerusakan' => 'required|string',
                    'lokasi_kerusakan'    => 'nullable|string|max:255',
                    'status'              => 'required|in:pending,sedang_diperbaiki,selesai',
                    'tindakan_perbaikan'  => 'nullable|string',
                    'biaya_perbaikan'     => 'nullable|numeric|min:0',
                    'ditangani_oleh'      => [
                        'nullable',
                        'exists:users,id',
                        function ($attribute, $value, $fail) {
                            if ($value) {
                                $teknisi = User::find($value);
                                if (!$teknisi || $teknisi->role !== 'teknisi' || $teknisi->status !== 'aktif') {
                                    $fail('Pengguna yang dipilih bukan teknisi aktif.');
                                }
                            }
                        },
                    ],
                    'asset_id' => 'nullable|exists:assets,id',
                ]);

                $updateData = $validated;

                if ($validated['status'] === 'selesai' && !$repair->tanggal_selesai) {
                    $updateData['tanggal_selesai'] = today();

                    $assetId = $validated['asset_id'] ?? $repair->asset_id;
                    $asset   = $assetId ? Asset::find($assetId) : null;

                    if ($asset) {
                        $kondisiAsetLama = $asset->kondisi_barang;
                        $asset->update(['kondisi_barang' => 'aktif']);

                        AssetConditionHistory::create([
                            'asset_id'     => $asset->id,
                            'kondisi_lama' => $kondisiAsetLama,
                            'kondisi_baru' => 'aktif',
                            'catatan'      => "Perbaikan selesai — {$repair->kode_perbaikan}",
                            'changed_by'   => $user->id,
                        ]);
                    }
                }

                $repair->update($updateData);
            }

            ActivityLog::record(
                action:      'update_progres_perbaikan',
                subject:     $repair,
                description: "Mengubah status laporan {$repair->kode_perbaikan}: {$statusLama} → {$repair->status}",
                oldData:     ['status' => $statusLama],
                newData:     ['status' => $repair->status],
            );
        });

        return redirect()->route('repairs.show', $repair)
            ->with('success', 'Laporan perbaikan berhasil diperbarui.');
    }

    public function destroy(Repair $repair)
    {
        abort(403, 'Laporan kerusakan tidak dapat dihapus.');
    }
}