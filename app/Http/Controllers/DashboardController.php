<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Repair;
use App\Models\Procurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Stats
        $totalAset = Asset::forUser($user)->count();
        $asetAktif = Asset::forUser($user)->where('kondisi_barang', 'Baik')->count();
        $perbaikan = Repair::when(!$user->isSuperAdmin() && !$user->iskepalayayasan(), function ($q) use ($user) {
            $q->whereHas('asset', fn($a) => $a->where('unit_kerja', $user->unit_kerja));
        })->whereIn('status', ['Pending', 'Sedang Diperbaiki'])->count();
        $komputer = Asset::forUser($user)->where('kategori', 'Komputer')->sum('jumlah_barang');
        $mejaKursi = Asset::forUser($user)->where('kategori', 'Furnitur')->sum('jumlah_barang');

        // Recent activities
        $recentRepairs = Repair::with(['asset', 'pelapor'])
            ->when(!$user->isSuperAdmin() && !$user->iskepalayayasan(), function ($q) use ($user) {
                $q->whereHas('asset', fn($a) => $a->where('unit_kerja', $user->unit_kerja));
            })
            ->latest()
            ->limit(5)
            ->get();

        $recentProcurements = Procurement::with('pengaju')
            ->forUser($user)
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalAset', 'asetAktif', 'perbaikan', 'komputer', 'mejaKursi',
            'recentRepairs', 'recentProcurements'
        ));
    }
}
