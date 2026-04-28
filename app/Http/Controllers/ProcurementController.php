<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Procurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcurementController extends Controller
{
    private array $units      = ['TK', 'SD', 'SMP', 'SMA', 'MA', 'Pondok Pesantren'];
    private array $categories = ['Elektronik', 'Furnitur', 'Komputer', 'Peralatan', 'Kendaraan', 'Lainnya'];

    // ─── Pengadaan Aset (Admin Unit — buat & lihat pengajuan miliknya) ──────────
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Procurement::forUser($user)->with(['pengaju', 'approver']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang',    'like', "%{$request->search}%")
                  ->orWhere('kode_pengadaan','like', "%{$request->search}%");
            });
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $procurements = $query->latest()->paginate(10)->appends(request()->query());

        return view('procurements.index', compact('procurements'));
    }

    public function create()
    {
        return view('procurements.create', [
            'units'      => $this->units,
            'categories' => $this->categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang'      => 'required|string|max:255',
            'kategori'         => 'required|string',
            'unit_kerja'       => 'required|string',
            'jumlah'           => 'required|integer|min:1',
            'estimasi_harga'   => 'required|numeric|min:0',
            'sumber_dana'      => 'required|string',
            'alasan_pengadaan' => 'required|string',
        ]);

        $count = Procurement::count() + 1;
        $validated['kode_pengadaan']    = 'PGD-' . date('Y') . str_pad($count, 3, '0', STR_PAD_LEFT);
        $validated['tanggal_pengajuan'] = today();
        $validated['diajukan_oleh']     = Auth::id();
        $validated['status']            = 'Pending';

        Procurement::create($validated);

        return redirect()->route('procurements.index')
            ->with('success', 'Pengajuan pengadaan berhasil dibuat!');
    }

    public function show(Procurement $procurement)
    {
        $procurement->load(['pengaju', 'approver']);
        return view('procurements.show', compact('procurement'));
    }

    public function destroy(Procurement $procurement)
    {
        if ($procurement->status !== 'Pending') {
            return back()->with('error', 'Hanya pengadaan berstatus Pending yang dapat dihapus.');
        }
        $procurement->delete();
        return redirect()->route('procurements.index')
            ->with('success', 'Pengadaan berhasil dihapus!');
    }

    // ─── Persetujuan Pengadaan (Super Admin & Kepala Yayasan) ───────────────────
    // Halaman khusus approval — menampilkan SEMUA pengadaan dari seluruh unit
    public function approvalIndex(Request $request)
    {
        $query = Procurement::with(['pengaju', 'approver']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang',    'like', "%{$request->search}%")
                  ->orWhere('kode_pengadaan','like', "%{$request->search}%")
                  ->orWhere('unit_kerja',    'like', "%{$request->search}%");
            });
        }

        // Default tampilkan Pending dulu di halaman approval
        $statusFilter = $request->status ?? '';
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $procurements = $query->latest()->paginate(10)->appends(request()->query());

        return view('procurements.approval', compact('procurements'));
    }

    public function approve(Request $request, Procurement $procurement)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && !$user->isKepalayayasan()) {
            abort(403);
        }

        $request->validate([
            'action'           => 'required|in:Disetujui,Ditolak',
            'catatan_approval' => 'nullable|string|max:500',
            // Field tambahan jika disetujui — untuk buat record aset
            'harga_realisasi'  => 'nullable|numeric|min:0',
            'tanggal_realisasi'=> 'nullable|date',
        ]);

        $procurement->update([
            'status'           => $request->action,
            'catatan_approval' => $request->catatan_approval,
            'tanggal_approval' => today(),
            'disetujui_oleh'   => $user->id,
        ]);

        // ── FIX MASALAH 3: Jika DISETUJUI → otomatis buat record aset ────────
        if ($request->action === 'Disetujui') {
            $this->createAssetFromProcurement($procurement, $request, $user);
            $message = 'Pengadaan disetujui dan aset berhasil ditambahkan ke Daftar Aset!';
        } else {
            $message = 'Pengadaan berhasil ditolak.';
        }

        return redirect()->route('approvals.index')->with('success', $message);
    }

    // Buat record aset secara otomatis dari data pengadaan yang disetujui
    private function createAssetFromProcurement(Procurement $procurement, Request $request, $user): void
    {
        $unitPrefix = strtoupper(substr(str_replace(' ', '', $procurement->unit_kerja), 0, 3));
        $count      = Asset::where('unit_kerja', $procurement->unit_kerja)->count() + 1;
        $kode       = $unitPrefix . '-' . date('Y') . str_pad($count, 3, '0', STR_PAD_LEFT);

        Asset::create([
            'kode_barang'       => $kode,
            'nama_barang'       => $procurement->nama_barang,
            'kategori'          => $procurement->kategori,
            'lokasi_barang'     => $procurement->unit_kerja,
            'unit_kerja'        => $procurement->unit_kerja,
            'jumlah_barang'     => $procurement->jumlah,
            'kondisi_barang'    => 'Baik',
            'sumber_dana'       => $procurement->sumber_dana,
            // Gunakan harga realisasi jika diisi, fallback ke estimasi
            'harga_barang'      => $request->filled('harga_realisasi')
                                    ? $request->harga_realisasi
                                    : $procurement->estimasi_harga,
            'tanggal_pengadaan' => $request->filled('tanggal_realisasi')
                                    ? $request->tanggal_realisasi
                                    : today(),
            'keterangan'        => 'Dari pengadaan ' . $procurement->kode_pengadaan
                                    . '. ' . ($procurement->alasan_pengadaan ?? ''),
            'created_by'        => $user->id,
        ]);
    }
}
