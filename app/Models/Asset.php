<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int         $id
 * @property string      $kode_aset
 * @property string      $nama_barang
 * @property string      $kategori
 * @property string|null $spesifikasi
 * @property string|null $lokasi_barang
 * @property int         $unit_id
 * @property int         $jumlah_barang
 * @property int|null    $satuan_id
 * @property int|null    $sumber_dana_id
 * @property float       $harga_barang
 * @property string|null $tanggal_pengadaan
 * @property string      $kondisi_barang    aktif|rusak|hilang|habis_pakai
 * @property string|null $keterangan_dasar
 * @property string|null $keterangan
 * @property int|null    $created_by
 */
class Asset extends Model
{
    protected $fillable = [
        'kode_aset',
        'nama_barang',
        'kategori',
        'spesifikasi',
        'harga_barang',
        'tanggal_pengadaan',
        'unit_id',
        'jumlah_barang',
        'satuan_id',
        'sumber_dana_id',
        'lokasi_barang',
        'kondisi_barang',
        'keterangan_dasar',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_pengadaan' => 'date',
        'harga_barang'      => 'decimal:2',
    ];

    public function getKondisiLabelAttribute(): string
    {
        return match ($this->kondisi_barang) {
            'aktif'       => 'Aktif',
            'rusak'       => 'Rusak',
            'hilang'      => 'Hilang',
            'habis_pakai' => 'Habis Pakai',
            default       => ucfirst($this->kondisi_barang),
        };
    }

    public function getKondisiBadgeAttribute(): string
    {
        return match ($this->kondisi_barang) {
            'aktif'       => 'badge-success',
            'rusak'       => 'badge-danger',
            'hilang'      => 'badge-warning',
            'habis_pakai' => 'badge-secondary',
            default       => 'badge-default',
        };
    }

    /**
     * Aset unit Yayasan boleh diubah lokasinya tanpa mutasi.
     */
    public function getIsUnitYayasanAttribute(): bool
    {
        return $this->unit?->is_yayasan ?? false;
    }

    public function getFotoUtamaAttribute(): ?AssetPhoto
    {
        return $this->photos->firstWhere('is_primary', true)
            ?? $this->photos->first();
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function satuan()
    {
        return $this->belongsTo(UnitSatuan::class, 'satuan_id');
    }

    public function fundingSource()
    {
        return $this->belongsTo(FundingSource::class, 'sumber_dana_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function photos()
    {
        return $this->hasMany(AssetPhoto::class);
    }

    public function conditionHistories()
    {
        return $this->hasMany(AssetConditionHistory::class)->orderByDesc('changed_at');
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }

    public function scopeForUser($query, User $user)
    {
        if ($user->isAdminUtama() || $user->isKepalaYayasan()) {
            return $query;
        }
        return $query->where('unit_id', $user->unit_id);
    }

    public function scopeKondisi($query, string $kondisi)
    {
        return $query->where('kondisi_barang', $kondisi);
    }

    public function scopeAktif($query)
    {
        return $query->where('kondisi_barang', 'aktif');
    }
}