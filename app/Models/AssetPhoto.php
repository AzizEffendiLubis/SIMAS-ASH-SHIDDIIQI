<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Foto aset — mendukung multi-foto per aset.
 *
 * @property int    $id
 * @property int    $asset_id
 * @property string $file_path
 * @property bool   $is_primary
 */
class AssetPhoto extends Model
{
    protected $table = 'asset_photos';

    protected $fillable = ['asset_id', 'file_path', 'is_primary'];

    protected $casts = ['is_primary' => 'boolean'];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}