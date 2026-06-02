<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Log aktivitas seluruh sistem.
 *
 * @property int         $id
 * @property int|null    $user_id
 * @property string      $action
 * @property string|null $subject_type
 * @property int|null    $subject_id
 * @property string|null $description
 * @property array|null  $old_data
 * @property array|null  $new_data
 * @property string|null $ip_address
 * @property \Carbon\Carbon $created_at
 */
class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'old_data',
        'new_data',
        'ip_address',
    ];

    protected $casts = [
        'old_data'   => 'array',
        'new_data'   => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        if (!$this->subject_type || !$this->subject_id) return null;
        return $this->subject_type::find($this->subject_id);
    }

    public static function record(
        string  $action,
        ?Model  $subject     = null,
        ?string $description = null,
        ?array  $oldData     = null,
        ?array  $newData     = null,
    ): self {
        return self::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'   => $subject?->getKey(),
            'description'  => $description,
            'old_data'     => $oldData,
            'new_data'     => $newData,
            'ip_address'   => request()->ip(),
        ]);
    }
}