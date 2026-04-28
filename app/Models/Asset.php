<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'kode_barang', 'nama_barang', 'kategori', 'lokasi_barang', 'unit_kerja',
        'jumlah_barang', 'kondisi_barang', 'sumber_dana', 'harga_barang',
        'tanggal_pengadaan', 'foto', 'keterangan', 'created_by',
    ];

    protected $casts = [
        'tanggal_pengadaan' => 'date',
        'harga_barang' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }

    public function scopeForUser($query, User $user)
    {
        if ($user->isSuperAdmin() || $user->iskepalayayasan()) {
            return $query;
        }
        return $query->where('unit_kerja', $user->unit_kerja);
    }
}
