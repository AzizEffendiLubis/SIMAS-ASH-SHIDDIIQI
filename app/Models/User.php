<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int         $id
 * @property string      $username
 * @property string      $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $jabatan
 * @property int|null    $unit_id
 * @property string      $role                 kepala_yayasan|admin_utama|admin_unit|teknisi|user
 * @property string      $status               aktif|nonaktif
 * @property array|null  $menu_access
 * @property string      $password
 * @property bool        $must_change_password
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'name',
        'email',
        'phone',
        'jabatan',
        'unit_id',
        'role',
        'status',
        'menu_access',
        'password',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'menu_access'          => 'array',
        'password'             => 'hashed',
        'must_change_password' => 'boolean',
    ];

    public function isKepalaYayasan(): bool { return $this->role === 'kepala_yayasan'; }
    public function isAdminUtama(): bool    { return $this->role === 'admin_utama'; }
    public function isAdminUnit(): bool     { return $this->role === 'admin_unit'; }
    public function isTeknisi(): bool       { return $this->role === 'teknisi'; }
    public function isUser(): bool          { return $this->role === 'user'; }

    public function canAccess(string $menu): bool
    {
        if ($this->isAdminUtama()) return true;
        return in_array($menu, $this->menu_access ?? []);
    }

    /**
     * hanya Admin Utama dan Admin Unit yang boleh mengedit aset.
     */
    public function canEditAset(): bool
    {
        return $this->isAdminUtama() || $this->isAdminUnit();
    }

    /**
     * "Kepala Yayasan hanya berperan sebagai pihak monitoring."
     */
    public function isMonitoringOnly(): bool
    {
        return $this->isKepalaYayasan();
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'kepala_yayasan' => 'Kepala Yayasan',
            'admin_utama'    => 'Admin Utama',
            'admin_unit'     => 'Admin Unit',
            'teknisi'        => 'Teknisi',
            'user'           => 'User',
            default          => ucfirst($this->role),
        };
    }

    public function getRoleBadgeAttribute(): string
    {
        return match ($this->role) {
            'kepala_yayasan' => 'badge-kepala',
            'admin_utama'    => 'badge-admin',
            'admin_unit'     => 'badge-unit',
            'teknisi'        => 'badge-teknisi',
            'user'           => 'badge-user',
            default          => 'badge-user',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'aktif' ? 'Aktif' : 'Nonaktif';
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'created_by');
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class, 'dilaporkan_oleh');
    }

    public function repairsHandled()
    {
        return $this->hasMany(Repair::class, 'ditangani_oleh');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}