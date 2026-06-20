<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Unit;
use App\Models\FundingSource;
use App\Models\UnitSatuan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedMasterData();
        $users = $this->seedUsers();
    }

    private function seedMasterData(): void
    {
        $unitsData = [
            ['nama_unit' => 'Yayasan',          'kode_unit' => 'YYS', 'is_yayasan' => true],
            ['nama_unit' => 'TK',               'kode_unit' => 'TK',  'is_yayasan' => false],
            ['nama_unit' => 'SD',               'kode_unit' => 'SD',  'is_yayasan' => false],
            ['nama_unit' => 'SMP',              'kode_unit' => 'SMP', 'is_yayasan' => false],
            ['nama_unit' => 'SMA',              'kode_unit' => 'SMA', 'is_yayasan' => false],
            ['nama_unit' => 'MA',               'kode_unit' => 'MA',  'is_yayasan' => false],
            ['nama_unit' => 'Pondok Pesantren', 'kode_unit' => 'PP',  'is_yayasan' => false],
        ];

        foreach ($unitsData as $data) {
            Unit::create(array_merge($data, ['is_active' => true]));
        }

        $fundingSources = [
            'Dana Yayasan',
            'Dana BOS',
            'Hibah',
            'Dana Mandiri Unit',
            'Lainnya',
        ];

        foreach ($fundingSources as $nama) {
            FundingSource::create(['nama_sumber' => $nama, 'is_active' => true]);
        }

        $satuanList = ['Unit', 'Set', 'Buah', 'Lembar', 'Rim', 'Pasang', 'Lusin', 'Pak'];

        foreach ($satuanList as $nama) {
            UnitSatuan::create(['nama_satuan' => $nama]);
        }
    }

    private function seedUsers(): array
    {
        $unitYayasan = Unit::where('kode_unit', 'YYS')->firstOrFail();

        $admin = User::create([
            'username'             => 'admin',
            'name'                 => 'Administrator Sistem',
            'email'                => 'admin@simas.sch.id',
            'phone'                => '081234567890',
            'jabatan'              => 'Administrator',
            'unit_id'              => $unitYayasan->id,
            'role'                 => 'admin_utama',
            'status'               => 'aktif',
            'menu_access'          => [
                'dashboard', 'daftar_aset', 'perbaikan_aset',
                'manajemen_pengguna', 'log_aktivitas', 'master_data',
            ],
            'password'             => 'password',
            'must_change_password' => false,
        ]);

        return compact('admin');
    }
}