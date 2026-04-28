<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    protected $fillable = [
        'kode_perbaikan', 'asset_id', 'deskripsi_kerusakan', 'tindakan_perbaikan',
        'status', 'tanggal_laporan', 'tanggal_selesai', 'biaya_perbaikan',
        'dilaporkan_oleh', 'ditangani_oleh', 'foto_kerusakan',
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
        'tanggal_selesai' => 'date',
        'biaya_perbaikan' => 'decimal:2',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'dilaporkan_oleh');
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'ditangani_oleh');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'Pending' => 'badge-warning',
            'Sedang Diperbaiki' => 'badge-info',
            'Selesai' => 'badge-success',
            default => 'badge-default',
        };
    }
}
