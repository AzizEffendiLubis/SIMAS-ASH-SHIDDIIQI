<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $jabatan
 * @property string $unit_kerja
 * @property string $role
 * @property string $status
 * @property array $menu_access
 * @property string $password
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username', 'name', 'email', 'phone', 'jabatan', 'unit_kerja',
        'role', 'status', 'menu_access', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'menu_access' => 'array',
        'password' => 'hashed',
    ];

    public function isSuperAdmin(): bool        { return $this->role === 'super_admin'; }
    public function iskepalayayasan(): bool     { return $this->role === 'kepala_yayasan'; }
    public function isAdminUnit(): bool         { return $this->role === 'admin_unit'; }
    public function isPetugasPerbaikan(): bool  { return $this->role === 'petugas_perbaikan'; }
    public function isUser(): bool              { return $this->role === 'user'; }

    public function canAccess(string $menu): bool
    {
        if ($this->isSuperAdmin()) return true;
        return in_array($menu, $this->menu_access ?? []);
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'Administrator',
            'kepala_yayasan' => 'Kepala Yayasan',
            'admin_unit' => 'Admin Unit',
            'petugas_perbaikan' => 'Petugas Perbaikan',
            'user'              => 'User',
            default             => ucfirst($this->role),
        };
    }

    public function getRoleBadgeAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'badge-admin',
            'kepala_yayasan' => 'badge-kepala',
            'admin_unit' => 'badge-unit',
            'petugas_perbaikan' => 'badge-teknisi',
            'user'              => 'badge-user',
            default             => 'badge-user',
        };
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'created_by');
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class, 'dilaporkan_oleh');
    }

    public function procurements()
    {
        return $this->hasMany(Procurement::class, 'diajukan_oleh');
    }
}
