<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Riwayat perubahan kondisi dan/atau lokasi aset.
 *
 * Digunakan untuk:
 *   - Perubahan kondisi barang (aktif → rusak, dsb)
 *   - Laporan kehilangan (kondisi_baru = 'hilang')
 *   - Laporan habis pakai (kondisi_baru = 'habis_pakai')
 *   - Perubahan lokasi aset unit Yayasan (lokasi_lama & lokasi_baru)
 *
 * @property int         $id
 * @property int         $asset_id
 * @property string|null $kondisi_lama
 * @property string|null $kondisi_baru
 * @property string|null $lokasi_lama
 * @property string|null $lokasi_baru
 * @property string|null $catatan
 * @property int|null    $changed_by
 * @property \Carbon\Carbon $changed_at
 */
class AssetConditionHistory extends Model
{
    protected $table = 'asset_condition_histories';

    public $timestamps = false;

    protected $fillable = [
        'asset_id',
        'kondisi_lama',
        'kondisi_baru',
        'lokasi_lama',
        'lokasi_baru',
        'catatan',
        'changed_by',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Label perubahan kondisi untuk ditampilkan di riwayat.
     * Contoh: "Aktif → Rusak"
     */
    public function getKondisiChangeLabel(): string
    {
        $labelMap = [
            'aktif'       => 'Aktif',
            'rusak'       => 'Rusak',
            'hilang'      => 'Hilang',
            'habis_pakai' => 'Habis Pakai',
        ];

        $lama = $labelMap[$this->kondisi_lama] ?? $this->kondisi_lama ?? '-';
        $baru = $labelMap[$this->kondisi_baru] ?? $this->kondisi_baru ?? '-';

        return "{$lama} → {$baru}";
    }
}