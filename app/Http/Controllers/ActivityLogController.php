<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Hanya Admin Utama & Kepala Yayasan yang bisa mengakses halaman ini.
     * Kepala Yayasan: bisa lihat semua log, tapi tidak bisa menghapus.
     * Admin Utama  : bisa lihat semua log.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // "Akses Kepala Yayasan hanya meliputi monitoring."
        // isMonitoringOnly() didefinisikan di User model
        if (!$user->isAdminUtama() && !$user->isKepalaYayasan()) {
            abort(403);
        }

        $query = ActivityLog::with('user')->latest('created_at');

        // Filter: berdasarkan jenis aksi
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter: berdasarkan pengguna yang melakukan aksi
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter: berdasarkan entitas target (subject_type).
        // subject_type disimpan sebagai FQCN (misal "App\Models\Asset"),
        // sehingga filter 'like' cukup dengan nama class pendek (misal "Asset").
        if ($request->filled('subject_type')) {
            $query->where('subject_type', 'like', '%' . $request->subject_type . '%');
        }

        // Filter: rentang tanggal
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('created_at', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('created_at', '<=', $request->sampai_tanggal);
        }

        // Search: di kolom description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('description', 'like', "%{$search}%");
        }

        $logs = $query->paginate(20)->appends($request->query());

        // Daftar semua aksi yang pernah tercatat — untuk dropdown filter
        $availableActions = ActivityLog::distinct()
            ->orderBy('action')
            ->pluck('action');

        // Daftar user yang pernah tercatat di log — untuk dropdown filter.
        // Hanya user yang punya log, bukan semua user.
        $usersWithLog = User::whereIn('id',
                ActivityLog::whereNotNull('user_id')->distinct()->pluck('user_id')
            )
            ->orderBy('name')
            ->get(['id', 'name', 'username']);

        return view('activity-logs.index', compact(
            'logs',
            'availableActions',
            'usersWithLog',
        ));
    }

    /**
     * Menampilkan detail log termasuk old_data & new_data (JSON diff).
     * Berguna untuk audit mendalam oleh Admin Utama atau Kepala Yayasan.
     *
     * Navigasi prev/next dihitung di sini agar blade bebas dari logika query.
     *   $prevLog = log yang lebih LAMA (id lebih kecil)
     *   $nextLog = log yang lebih BARU (id lebih besar)
     */
    public function show(ActivityLog $activityLog)
    {
        $user = Auth::user();

        if (!$user->isAdminUtama() && !$user->isKepalaYayasan()) {
            abort(403);
        }

        $activityLog->load('user');

        // Log lebih lama: id lebih kecil dari log saat ini
        $prevLog = ActivityLog::where('id', '<', $activityLog->id)
            ->latest('id')
            ->first();

        // Log lebih baru: id lebih besar dari log saat ini
        $nextLog = ActivityLog::where('id', '>', $activityLog->id)
            ->oldest('id')
            ->first();

        return view('activity-logs.show', compact('activityLog', 'prevLog', 'nextLog'));
    }

    /**
     * Log aktivitas tidak boleh dihapus — merupakan audit trail sistem.
     * Konsisten dengan prinsip tidak menghapus riwayat apapun di sistem ini.
     */
    public function destroy(ActivityLog $activityLog)
    {
        abort(403, 'Log aktivitas tidak dapat dihapus.');
    }
}