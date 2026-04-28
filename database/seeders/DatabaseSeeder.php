<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Asset;
use App\Models\Repair;
use App\Models\Procurement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $admin = User::create([
            'username' => 'admin', 'name' => 'Administrator Sistem',
            'email' => 'admin@simas.sch.id', 'phone' => '081234567890',
            'jabatan' => 'Administrator', 'unit_kerja' => 'Yayasan',
            'role' => 'super_admin', 'status' => 'aktif',
            'menu_access' => ['dashboard','daftar_aset','pengadaan_aset','persetujuan_pengadaan','perbaikan_aset','manajemen_pengguna'],
            'password' => Hash::make('password'),
        ]);

        // Kepala Yayasan
        $kepalayayasan = User::create([
            'username' => 'kepalayayasan', 'name' => 'Dr. H. Muhammad Rizki, M.Pd',
            'email' => 'kepalayayasan@simas.sch.id', 'phone' => '082271839015',
            'jabatan' => 'Kepala Yayasan', 'unit_kerja' => 'Yayasan',
            'role' => 'kepala_yayasan', 'status' => 'aktif',
            'menu_access' => ['dashboard','daftar_aset','persetujuan_pengadaan','pengadaan_aset','perbaikan_aset'],
            'password' => Hash::make('password'),
        ]);

        // Admin Unit
        $adminsd = User::create([
            'username' => 'adminsd', 'name' => 'Siti Aminah, S.Pd',
            'email' => 'admin.sd@simas.sch.id', 'phone' => '081298765432',
            'jabatan' => 'Admin Unit', 'unit_kerja' => 'SD',
            'role' => 'admin_unit', 'status' => 'aktif',
            'menu_access' => ['dashboard','daftar_aset','pengadaan_aset','perbaikan_aset'],
            'password' => Hash::make('password'),
        ]);
        $adminsmp = User::create([
            'username' => 'adminsmp', 'name' => 'Ahmad Fauzi, S.Pd',
            'email' => 'admin.smp@simas.sch.id', 'phone' => '081312345678',
            'jabatan' => 'Admin Unit', 'unit_kerja' => 'SMP',
            'role' => 'admin_unit', 'status' => 'aktif',
            'menu_access' => ['dashboard','daftar_aset','pengadaan_aset','perbaikan_aset'],
            'password' => Hash::make('password'),
        ]);
        User::create([
            'username' => 'adminsma', 'name' => 'Dewi Rahayu, S.Pd',
            'email' => 'admin.sma@simas.sch.id', 'phone' => '085612345678',
            'jabatan' => 'Admin Unit', 'unit_kerja' => 'SMA',
            'role' => 'admin_unit', 'status' => 'aktif',
            'menu_access' => ['dashboard','daftar_aset','pengadaan_aset','perbaikan_aset'],
            'password' => Hash::make('password'),
        ]);

        // Petugas Perbaikan
        $teknisi = User::create([
            'username' => 'teknisi', 'name' => 'Joko Susilo',
            'email' => 'teknisi.sd@simas.sch.id', 'phone' => '089876543210',
            'jabatan' => 'Petugas Perbaikan', 'unit_kerja' => 'SD',
            'role' => 'petugas_perbaikan', 'status' => 'aktif',
            'menu_access' => ['dashboard','perbaikan_aset'],
            'password' => Hash::make('password'),
        ]);

        // User biasa (guru/santri daftar sendiri)
        User::create([
            'username' => 'guruipa', 'name' => 'Budi Santoso, S.Pd',
            'email' => 'budi.ipa@simas.sch.id', 'phone' => '08567890123',
            'jabatan' => 'Guru', 'unit_kerja' => 'SMP',
            'role' => 'user', 'status' => 'aktif',
            'menu_access' => ['dashboard','perbaikan_aset'],
            'password' => Hash::make('password'),
        ]);
        User::create([
            'username' => 'staff01', 'name' => 'Rina Wulandari',
            'email' => 'rina@simas.sch.id', 'phone' => '08512345678',
            'jabatan' => 'Staff', 'unit_kerja' => 'SD',
            'role' => 'user', 'status' => 'nonaktif',
            'menu_access' => ['dashboard','perbaikan_aset'],
            'password' => Hash::make('password'),
        ]);

        // Sample Assets
        $units = ['TK','SD','SMP','SMA','MA','Pondok Pesantren'];
        $items = [
            ['Tong Sampah','Furnitur',5,20000],['Laptop ASUS','Komputer',1,4000000],
            ['Kipas Angin','Elektronik',2,250000],['Meja Guru','Furnitur',3,450000],
            ['Kursi Siswa','Furnitur',30,85000],['Proyektor','Elektronik',1,3500000],
            ['Papan Tulis','Peralatan',2,200000],['AC Ruangan','Elektronik',1,3200000],
            ['Stop Kontak','Peralatan',5,50000],['Komputer Desktop','Komputer',5,5000000],
        ];
        $count = 1;
        foreach ($units as $unit) {
            foreach ($items as [$name, $cat, $qty, $price]) {
                $kode = strtoupper(substr(str_replace(' ','',$unit),0,3)).'-'.date('Y').str_pad($count,3,'0',STR_PAD_LEFT);
                Asset::create([
                    'kode_barang'=>$kode,'nama_barang'=>$name,'kategori'=>$cat,
                    'lokasi_barang'=>'Ruang '.$unit,'unit_kerja'=>$unit,
                    'jumlah_barang'=>$qty,'kondisi_barang'=>collect(['Baik','Baik','Baik','Rusak Ringan'])->random(),
                    'sumber_dana'=>'Dana Yayasan','harga_barang'=>$price,
                    'tanggal_pengadaan'=>now()->subMonths(rand(1,36)),'created_by'=>$admin->id,
                ]);
                $count++;
            }
        }

        // Sample Repairs
        $assets = Asset::inRandomOrder()->limit(12)->get();
        foreach ($assets as $i => $asset) {
            $status = collect(['Pending','Pending','Sedang Diperbaiki','Selesai'])->random();
            Repair::create([
                'kode_perbaikan'=>'PRB-'.date('Y').str_pad($i+1,3,'0',STR_PAD_LEFT),
                'asset_id'=>$asset->id,
                'deskripsi_kerusakan'=>'Kerusakan pada '.$asset->nama_barang.' – perlu perbaikan segera',
                'tindakan_perbaikan'=>$status==='Selesai'?'Sudah diperbaiki dan berfungsi normal':null,
                'status'=>$status,'tanggal_laporan'=>now()->subDays(rand(1,30)),
                'tanggal_selesai'=>$status==='Selesai'?now()->subDays(rand(1,5)):null,
                'biaya_perbaikan'=>$status==='Selesai'?rand(50000,500000):null,
                'dilaporkan_oleh'=>collect([$adminsd->id,$adminsmp->id])->random(),
                'ditangani_oleh'=>$teknisi->id,
            ]);
        }

        // Sample Procurements
        $procItems = [
            ['Meja Baru','Furnitur',5,750000,'SD'],
            ['Laptop Siswa','Komputer',10,5000000,'SMA'],
            ['Printer Laser','Komputer',2,2500000,'SMP'],
            ['AC Ruangan','Elektronik',3,3500000,'SMP'],
            ['Kursi Ergonomis','Furnitur',20,450000,'SD'],
        ];
        foreach ($procItems as $i => [$name,$cat,$qty,$price,$unit]) {
            $status = collect(['Pending','Disetujui','Ditolak'])->random();
            Procurement::create([
                'kode_pengadaan'=>'PGD-'.date('Y').str_pad($i+1,3,'0',STR_PAD_LEFT),
                'nama_barang'=>$name,'kategori'=>$cat,'unit_kerja'=>$unit,
                'jumlah'=>$qty,'estimasi_harga'=>$price,'sumber_dana'=>'Dana Yayasan',
                'alasan_pengadaan'=>'Kebutuhan '.$unit.' untuk mendukung kegiatan belajar mengajar.',
                'status'=>$status,
                'catatan_approval'=>$status!=='Pending'?'Telah diproses oleh kepala yayasan.':null,
                'tanggal_pengajuan'=>now()->subDays(rand(1,20)),
                'tanggal_approval'=>$status!=='Pending'?now()->subDays(rand(1,10)):null,
                'diajukan_oleh'=>collect([$adminsd->id,$adminsmp->id])->random(),
                'disetujui_oleh'=>$status!=='Pending'?$kepalayayasan->id:null,
            ]);
        }
    }
}
