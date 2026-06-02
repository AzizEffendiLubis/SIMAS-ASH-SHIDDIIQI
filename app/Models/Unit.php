<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $nama_unit
 * @property string $kode_unit
 * @property bool   $is_yayasan
 * @property bool   $is_active
 */
class Unit extends Model
{
    protected $fillable = ['nama_unit', 'kode_unit', 'deskripsi', 'is_yayasan', 'is_active'];

    protected $casts = [
        'is_yayasan' => 'boolean',
        'is_active'  => 'boolean',
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}