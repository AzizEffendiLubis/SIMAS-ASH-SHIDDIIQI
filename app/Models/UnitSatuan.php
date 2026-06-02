<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Master satuan aset — bersifat TETAP, diisi via seeder.
 *
 * @property int    $id
 * @property string $nama_satuan
 */
class UnitSatuan extends Model
{
    protected $table = 'units_satuan';

    protected $fillable = ['nama_satuan'];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'satuan_id');
    }
}