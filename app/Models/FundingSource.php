<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Master sumber dana — dinamis, bisa ditambah admin.
 *
 * @property int    $id
 * @property string $nama_sumber
 * @property bool   $is_active
 */
class FundingSource extends Model
{
    protected $fillable = ['nama_sumber', 'deskripsi', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'sumber_dana_id');
    }
}