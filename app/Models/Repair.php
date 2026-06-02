<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int         $id
 * @property string      $kode_perbaikan
 * @property string      $nama_aset_laporan
 * @property int|null    $asset_id
 * @property string      $deskripsi_kerusakan
 * @property string|null $lokasi_kerusakan
 * @property string      $status              pending|sedang_diperbaiki|selesai
 * @property string|null $tindakan_perbaikan
 * @property \Carbon\Carbon $tanggal_laporan
 * @property string|null $tanggal_selesai
 * @property float|null  $biaya_perbaikan
 * @property int|null    $dilaporkan_oleh
 * @property int|null    $ditangani_oleh
 */
class Repair extends Model
{
    protected $fillable = [
        'kode_perbaikan',
        'nama_aset_laporan',
        'asset_id',
        'deskripsi_kerusakan',
        'lokasi_kerusakan',
        'status',
        'tindakan_perbaikan',
        'tanggal_laporan',
        'tanggal_selesai',
        'biaya_perbaikan',
        'dilaporkan_oleh',
        'ditangani_oleh',
    ];

    protected $casts = [
        'tanggal_laporan' => 'datetime',
        'tanggal_selesai' => 'date',
        'biaya_perbaikan' => 'decimal:2',
    ];

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'           => 'Menunggu',
            'sedang_diperbaiki' => 'Sedang Diperbaiki',
            'selesai'           => 'Selesai',
            default             => ucfirst($this->status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'           => 'badge-warning',
            'sedang_diperbaiki' => 'badge-info',
            'selesai'           => 'badge-success',
            default             => 'badge-default',
        };
    }

    public function getIsSelesaiAttribute(): bool
    {
        return $this->status === 'selesai';
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'dilaporkan_oleh');
    }

    /**
     * Teknisi penanganan.
     * ADA di database, TIDAK ditampilkan ke pelapor di UI.
     */
    public function teknisi()
    {
        return $this->belongsTo(User::class, 'ditangani_oleh');
    }

    public function photos()
    {
        return $this->hasMany(RepairPhoto::class);
    }

    /**
     * Antrian aktif diurutkan FIFO berdasarkan tanggal_laporan.
     */
    public function scopeAntrian($query)
    {
        return $query->whereIn('status', ['pending', 'sedang_diperbaiki'])
                     ->orderBy('tanggal_laporan', 'asc');
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Filter laporan berdasarkan hak akses pengguna.
     * - Admin Utama, Teknisi, Kepala Yayasan: lihat semua
     * - Admin Unit, User: hanya laporan milik sendiri
     */
    public function scopeForUser($query, User $user)
    {
        if ($user->isAdminUtama() || $user->isTeknisi() || $user->isKepalaYayasan()) {
            return $query;
        }
        return $query->where('dilaporkan_oleh', $user->id);
    }
}