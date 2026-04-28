<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    protected $fillable = [
        'kode_pengadaan', 'nama_barang', 'kategori', 'unit_kerja', 'jumlah',
        'estimasi_harga', 'sumber_dana', 'alasan_pengadaan', 'status',
        'catatan_approval', 'tanggal_pengajuan', 'tanggal_approval',
        'diajukan_oleh', 'disetujui_oleh',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_approval' => 'date',
        'estimasi_harga' => 'decimal:2',
    ];

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'Pending' => 'badge-warning',
            'Disetujui' => 'badge-success',
            'Ditolak' => 'badge-danger',
            default => 'badge-default',
        };
    }

    public function scopeForUser($query, User $user)
    {
        if ($user->isSuperAdmin() || $user->iskepalayayasan()) {
            return $query;
        }
        return $query->where('unit_kerja', $user->unit_kerja);
    }
}
