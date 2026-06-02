<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Foto kerusakan — mendukung multi-foto per laporan.
 *
 * @property int    $id
 * @property int    $repair_id
 * @property string $file_path
 */
class RepairPhoto extends Model
{
    protected $table = 'repair_photos';

    protected $fillable = ['repair_id', 'file_path'];

    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }
}