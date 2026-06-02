<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Repair;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ── Statistik aset ────────────────────────────────────────────────
        // scopeForUser() di Asset model:
        //   - Admin Utama & Kepala Yayasan → semua aset
        //   - Admin Unit, Teknisi, User    → hanya aset unit sendiri
        $totalAset  = Asset::forUser($user)->count();
        $asetAktif  = Asset::forUser($user)->where('kondisi_barang', 'aktif')->count();
        $asetRusak  = Asset::forUser($user)->where('kondisi_barang', 'rusak')->count();
        $asetHilang = Asset::forUser($user)->where('kondisi_barang', 'hilang')->count();

        // ── Statistik perbaikan (laporan aktif) ───────────────────────────
        // scopeForUser() di Repair model:
        //   - Admin Utama, Teknisi, Kepala Yayasan → semua laporan
        //   - Admin Unit, User                     → hanya laporan milik sendiri
        $perbaikanAktif = Repair::forUser($user)
            ->whereIn('status', ['pending', 'sedang_diperbaiki'])
            ->count();

        // ── Statistik cepat per kategori ──────────────────────────────────
        // sum('jumlah_barang') karena satu record aset bisa mewakili lebih dari 1 barang
        $totalKomputer = Asset::forUser($user)->where('kategori', 'Komputer')->sum('jumlah_barang');
        $totalFurnitur = Asset::forUser($user)->where('kategori', 'Furnitur')->sum('jumlah_barang');

        // ── Laporan perbaikan terbaru ─────────────────────────────────────
        // Relasi pelapor → belongsTo User (didefinisikan di Repair model)
        $recentRepairs = Repair::forUser($user)
            ->with(['pelapor'])
            ->orderBy('tanggal_laporan', 'desc')
            ->limit(5)
            ->get();

        // ── Log aktivitas terbaru ─────────────────────────────────────────
        // "Akses Kepala Yayasan meliputi dashboard aset, laporan aset,
        //           dan log aktivitas sistem."
        // Hanya Admin Utama & Kepala Yayasan yang bisa melihat log aktivitas
        $recentLogs = null;
        if ($user->isAdminUtama() || $user->isKepalaYayasan()) {
            $recentLogs = ActivityLog::with('user')
                ->latest('created_at')
                ->limit(10)
                ->get();
        }

        return view('dashboard.index', compact(
            'totalAset',
            'asetAktif',
            'asetRusak',
            'asetHilang',
            'perbaikanAktif',
            'totalKomputer',
            'totalFurnitur',
            'recentRepairs',
            'recentLogs',
        ));
    }
}