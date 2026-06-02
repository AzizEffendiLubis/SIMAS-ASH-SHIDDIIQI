<?php

namespace Database\Seeders;

// ============================================================================
// CATATAN AUTOLOAD
// ============================================================================
// Beberapa class di bawah didefinisikan dalam satu file model yang sama:
//
//   App\Models\Unit, FundingSource, UnitSatuan, WarehouseType
//     → semua ada di app/Models/Masterdata.php
//
//   App\Models\Asset, AssetPhoto, AssetConditionHistory
//     → semua ada di app/Models/Asset.php
//
//   App\Models\Repair, RepairPhoto
//     → semua ada di app/Models/Repair.php
//
//   App\Models\ActivityLog  → app/Models/ActivityLog.php
//   App\Models\User         → app/Models/User.php
//
// PHP menemukan class berdasarkan NAMESPACE, bukan nama file.
// Selama namespace-nya 'App\Models' dan file ada di app/Models/,
// semua use statement di bawah akan berfungsi normal.
//
// WAJIB jalankan setelah setup pertama:
//   composer dump-autoload
// ============================================================================

use App\Models\User;
use App\Models\Unit;                  // Masterdata.php
use App\Models\FundingSource;         // Masterdata.php
use App\Models\UnitSatuan;            // Masterdata.php
use App\Models\WarehouseType;         // Masterdata.php
use App\Models\Asset;                 // Asset.php
use App\Models\AssetConditionHistory; // Asset.php
use App\Models\Repair;                // Repair.php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedMasterData();
        $users = $this->seedUsers();
        $this->seedAssets($users);
        $this->seedRepairs($users);
        // Catatan: Procurement TIDAK ada — fitur dihapus dari sistem.
    }

    private function seedMasterData(): void
    {
        // ── Units ─────────────────────────────────────────────────────────────
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

        // ── Sumber Dana (dinamis) ─────────────────────────────────────────────
        // FundingSource didefinisikan di app/Models/Masterdata.php
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

        // ── Satuan Aset (tetap, diisi via seeder saja) ─────────────────────────
        // UnitSatuan didefinisikan di app/Models/Masterdata.php
        $satuanList = ['Unit', 'Set', 'Buah', 'Lembar', 'Rim', 'Pasang', 'Lusin', 'Pak'];

        foreach ($satuanList as $nama) {
            UnitSatuan::create(['nama_satuan' => $nama]);
        }
    }

    private function seedUsers(): array
    {
        // Unit sudah di-seed sebelumnya, aman untuk di-query di sini.
        // Unit didefinisikan di app/Models/Masterdata.php
        $unitYayasan = Unit::where('kode_unit', 'YYS')->firstOrFail();
        $unitSD      = Unit::where('kode_unit', 'SD')->firstOrFail();
        $unitSMP     = Unit::where('kode_unit', 'SMP')->firstOrFail();
        $unitSMA     = Unit::where('kode_unit', 'SMA')->firstOrFail();

        // must_change_password = false untuk kemudahan development/demo.
        // Ubah ke true saat production seeding.
        //
        // password di-hash otomatis via cast 'hashed' di User model (User.php).

        // ── Admin Utama ───────────────────────────────────────────────────────
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

        // ── Kepala Yayasan ────────────────────────────────────────────────────
        $kepalaYayasan = User::create([
            'username'             => 'kepalayayasan',
            'name'                 => 'Dr. H. Muhammad Rizki, M.Pd',
            'email'                => 'kepalayayasan@simas.sch.id',
            'phone'                => '082271839015',
            'jabatan'              => 'Kepala Yayasan',
            'unit_id'              => $unitYayasan->id,
            'role'                 => 'kepala_yayasan',
            'status'               => 'aktif',
            'menu_access'          => ['dashboard', 'daftar_aset', 'log_aktivitas'],
            'password'             => 'password',
            'must_change_password' => false,
        ]);

        // ── Admin Unit ────────────────────────────────────────────────────────
        $adminSD = User::create([
            'username'             => 'adminsd',
            'name'                 => 'Siti Aminah, S.Pd',
            'email'                => 'admin.sd@simas.sch.id',
            'phone'                => '081298765432',
            'jabatan'              => 'Admin Unit SD',
            'unit_id'              => $unitSD->id,
            'role'                 => 'admin_unit',
            'status'               => 'aktif',
            'menu_access'          => ['dashboard', 'daftar_aset', 'perbaikan_aset'],
            'password'             => 'password',
            'must_change_password' => false,
        ]);

        $adminSMP = User::create([
            'username'             => 'adminsmp',
            'name'                 => 'Ahmad Fauzi, S.Pd',
            'email'                => 'admin.smp@simas.sch.id',
            'phone'                => '081312345678',
            'jabatan'              => 'Admin Unit SMP',
            'unit_id'              => $unitSMP->id,
            'role'                 => 'admin_unit',
            'status'               => 'aktif',
            'menu_access'          => ['dashboard', 'daftar_aset', 'perbaikan_aset'],
            'password'             => 'password',
            'must_change_password' => false,
        ]);

        User::create([
            'username'             => 'adminsma',
            'name'                 => 'Dewi Rahayu, S.Pd',
            'email'                => 'admin.sma@simas.sch.id',
            'phone'                => '085612345678',
            'jabatan'              => 'Admin Unit SMA',
            'unit_id'              => $unitSMA->id,
            'role'                 => 'admin_unit',
            'status'               => 'aktif',
            'menu_access'          => ['dashboard', 'daftar_aset', 'perbaikan_aset'],
            'password'             => 'password',
            'must_change_password' => false,
        ]);

        // ── Teknisi ───────────────────────────────────────────────────────────
        // unit_id = null karena teknisi bisa lintas unit
        $teknisi = User::create([
            'username'             => 'teknisi',
            'name'                 => 'Joko Susilo',
            'email'                => 'teknisi@simas.sch.id',
            'phone'                => '089876543210',
            'jabatan'              => 'Teknisi',
            'unit_id'              => null,
            'role'                 => 'teknisi',
            'status'               => 'aktif',
            'menu_access'          => ['dashboard', 'perbaikan_aset'],
            'password'             => 'password',
            'must_change_password' => false,
        ]);

        // ── User Biasa ────────────────────────────────────────────────────────
        User::create([
            'username'             => 'guruipa',
            'name'                 => 'Budi Santoso, S.Pd',
            'email'                => 'budi.ipa@simas.sch.id',
            'phone'                => '085678901234',
            'jabatan'              => 'Guru IPA',
            'unit_id'              => $unitSMP->id,
            'role'                 => 'user',
            'status'               => 'aktif',
            'menu_access'          => ['dashboard', 'perbaikan_aset'],
            'password'             => 'password',
            'must_change_password' => false,
        ]);

        // ── Contoh akun nonaktif ──────────────────────────────────────────────
        User::create([
            'username'             => 'staff01',
            'name'                 => 'Rina Wulandari',
            'email'                => 'rina@simas.sch.id',
            'phone'                => '085123456789',
            'jabatan'              => 'Staff TU',
            'unit_id'              => $unitSD->id,
            'role'                 => 'user',
            'status'               => 'nonaktif',
            'menu_access'          => ['dashboard', 'perbaikan_aset'],
            'password'             => 'password',
            'must_change_password' => false,
        ]);

        return compact('admin', 'kepalaYayasan', 'adminSD', 'adminSMP', 'teknisi');
    }

    private function seedAssets(array $users): void
    {
        $admin = $users['admin'];

        // UnitSatuan & FundingSource didefinisikan di app/Models/Masterdata.php
        $satuanUnit  = UnitSatuan::where('nama_satuan', 'Unit')->firstOrFail();
        $satuanSet   = UnitSatuan::where('nama_satuan', 'Set')->firstOrFail();
        $danaYayasan = FundingSource::where('nama_sumber', 'Dana Yayasan')->firstOrFail();
        $danaBOS     = FundingSource::where('nama_sumber', 'Dana BOS')->firstOrFail();

        // Format: [nama_barang, kategori, jumlah_barang, harga_barang, satuan_id, spesifikasi]
        $items = [
            ['Laptop ASUS VivoBook',   'Komputer',   1,  4500000, $satuanUnit->id, 'Intel Core i5, RAM 8GB, SSD 512GB'],
            ['Komputer Desktop',       'Komputer',   5,  5000000, $satuanUnit->id, 'Intel Core i3, RAM 4GB, HDD 1TB'],
            ['Proyektor Epson',        'Elektronik', 1,  3800000, $satuanUnit->id, 'Resolusi XGA 1024x768, 3300 Lumen'],
            ['AC Ruangan Daikin',      'Elektronik', 1,  4200000, $satuanUnit->id, '1 PK, Inverter'],
            ['Kipas Angin Cosmos',     'Elektronik', 2,   280000, $satuanUnit->id, 'Stand fan, diameter 16 inch'],
            ['Meja Guru',              'Furnitur',   3,   480000, $satuanUnit->id, 'Kayu jati, ukuran 120x60cm'],
            ['Kursi Siswa',            'Furnitur',  30,    95000, $satuanUnit->id, 'Plastik PP, rangka besi'],
            ['Lemari Arsip',           'Furnitur',   2,   650000, $satuanUnit->id, '4 rak, kunci ganda'],
            ['Papan Tulis Whiteboard', 'Peralatan',  2,   220000, $satuanUnit->id, 'Magnetik, ukuran 120x240cm'],
            ['Printer Epson L3210',    'Komputer',   1,  1800000, $satuanUnit->id, 'Print, Scan, Copy, Infus'],
        ];

        // Unit didefinisikan di app/Models/Masterdata.php
        $units = Unit::all()->keyBy('kode_unit');

        foreach ($units as $kodeUnit => $unit) {
            $counter = 1;

            foreach ($items as [$nama, $kategori, $jumlah, $harga, $satuanId, $spesifikasi]) {
                // Generate kode_aset: [KODE_UNIT]-[YYYYMMDD]-[XXXX]
                // "Generate kode aset berdasarkan unit kerja, tanggal penambahan, dan nomor urut."
                $tglPengadaan = now()->subMonths(rand(1, 36));
                $kodeAset     = strtoupper($kodeUnit)
                    . '-' . $tglPengadaan->format('Ymd')
                    . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT);

                // Kondisi acak — mayoritas aktif.
                // nilai kondisi: aktif, rusak, hilang, habis_pakai.
                $kondisi = collect([
                    'aktif', 'aktif', 'aktif', 'aktif', 'aktif',
                    'rusak',
                    'habis_pakai',
                ])->random();

                $sumberDanaId = rand(0, 1) ? $danaYayasan->id : $danaBOS->id;

                $asset = Asset::create([
                    'kode_aset'         => $kodeAset,
                    'nama_barang'       => $nama,
                    'kategori'          => $kategori,
                    'spesifikasi'       => $spesifikasi,
                    'lokasi_barang'     => 'Ruang ' . $unit->nama_unit,
                    'unit_id'           => $unit->id,
                    'jumlah_barang'     => $jumlah,
                    'satuan_id'         => $satuanId,
                    'sumber_dana_id'    => $sumberDanaId,
                    'harga_barang'      => $harga,
                    'tanggal_pengadaan' => $tglPengadaan->format('Y-m-d'),
                    'kondisi_barang'    => $kondisi,
                    'keterangan_dasar'  => 'Pengadaan rutin berdasarkan rapat anggaran yayasan.',
                    'created_by'        => $admin->id,
                ]);

                // Catat riwayat kondisi awal sebagai baseline.
                // kondisi_lama = null menandakan ini pencatatan pertama (aset baru masuk).
                //
                // PENTING: AssetConditionHistory punya $timestamps = false dan
                // 'changed_at' TIDAK ada di $fillable (mass assignment protection).
                // Solusi: create() dulu, lalu update 'changed_at' via DB::table()
                // untuk menghindari memaksa mass assignment atau mengubah model.
                //
                // AssetConditionHistory didefinisikan di app/Models/Asset.php
                $history = AssetConditionHistory::create([
                    'asset_id'     => $asset->id,
                    'kondisi_lama' => null,
                    'kondisi_baru' => $kondisi,
                    'catatan'      => 'Aset pertama kali dicatat ke dalam sistem.',
                    'changed_by'   => $admin->id,
                    // 'changed_at' tidak di-pass ke create() karena tidak ada di $fillable
                ]);

                // Set changed_at secara eksplisit via DB::table() agar
                // tanggalnya sesuai tgl_pengadaan, bukan waktu seeding berjalan.
                DB::table('asset_condition_histories')
                    ->where('id', $history->id)
                    ->update(['changed_at' => $tglPengadaan]);

                $counter++;
            }
        }
    }

    private function seedRepairs(array $users): void
    {
        $adminSD  = $users['adminSD'];
        $adminSMP = $users['adminSMP'];
        $teknisi  = $users['teknisi'];

        // Ambil aset rusak terlebih dahulu, lalu tambah aset aktif sebagai pelengkap
        // agar data demo repair bervariasi (tidak semua dari aset yang sudah rusak).
        $asetRusak = Asset::where('kondisi_barang', 'rusak')->get();
        $asetAktif = Asset::where('kondisi_barang', 'aktif')
            ->inRandomOrder()
            ->limit(max(0, 12 - $asetRusak->count()))
            ->get();

        // Gabungkan dan batasi maksimal 12 laporan untuk data demo.
        $asetUntukRepair = $asetRusak->merge($asetAktif)->take(12);

        $deskripsiList = [
            'Layar mengalami flickering dan kadang mati mendadak.',
            'Tombol keyboard tidak merespon saat ditekan.',
            'Suara berisik saat dinyalakan, kipas tidak berputar normal.',
            'Remote tidak berfungsi, AC tidak merespon.',
            'Kaki meja patah sebelah, tidak bisa digunakan.',
            'Proyektor tidak mau menyala, lampu indikator merah.',
            'Printer tidak menarik kertas, paper jam terus-menerus.',
            'Monitor bergaris horizontal, gambar tidak jelas.',
            'Engsel pintu lemari rusak, tidak bisa dikunci.',
            'Speaker komputer tidak mengeluarkan suara.',
            'Papan tulis berjamur dan sulit dihapus.',
            'Baut kursi longgar, berbahaya untuk digunakan.',
        ];

        $lokasiList = [
            'Ruang Kelas 1A', 'Ruang Kelas 2B', 'Ruang Guru',
            'Lab Komputer',   'Ruang TU',        'Perpustakaan',
            'Ruang Kepala',   'Ruang Osis',      'Aula Utama',
        ];

        $counter = 1;

        foreach ($asetUntukRepair as $i => $asset) {
            // Status acak untuk variasi data demo.
            // nilai status: pending, sedang_diperbaiki, selesai
            $status = collect([
                'pending', 'pending',
                'sedang_diperbaiki',
                'selesai',
            ])->random();

            // tanggal_laporan = dasar urutan antrian FIFO.
            $tglLaporan = now()->subDays(rand(1, 45));

            // Generate kode laporan: LAP-YYYYMMDD-XXXX
            $kodePerbaikan = 'LAP-' . $tglLaporan->format('Ymd')
                . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT);

            $tanggalSelesai = $status === 'selesai'
                ? now()->subDays(rand(1, 10))->format('Y-m-d')
                : null;

            $repair = Repair::create([
                'kode_perbaikan'      => $kodePerbaikan,

                // Nama ditulis manual — bukan dari dropdown.
                // Dokumen: "Pilihan aset rusak tidak menggunakan dropdown."
                'nama_aset_laporan'   => $asset->nama_barang,

                // FK ke aset — nullable. Di data demo langsung dikaitkan.
                // Pada penggunaan nyata, dikaitkan admin setelah verifikasi.
                'asset_id'            => $asset->id,

                'deskripsi_kerusakan' => $deskripsiList[$i % count($deskripsiList)],

                //"Ditambahkan informasi lokasi kerusakan dalam bentuk deskripsi."
                'lokasi_kerusakan'    => $lokasiList[$i % count($lokasiList)],

                'status'              => $status,

                'tindakan_perbaikan'  => match ($status) {
                    'selesai'           => 'Komponen rusak telah diganti. Perangkat sudah berfungsi normal kembali.',
                    'sedang_diperbaiki' => 'Sedang dalam proses pengecekan dan perbaikan.',
                    default             => null,
                },

                'tanggal_laporan'     => $tglLaporan,
                'tanggal_selesai'     => $tanggalSelesai,

                'biaya_perbaikan'     => $status === 'selesai'
                    ? rand(50000, 750000)
                    : null,

                // Pelapor: Admin Unit atau User — keduanya boleh melapor.
                'dilaporkan_oleh'     => collect([$adminSD->id, $adminSMP->id])->random(),

                // Teknisi: ADA di database, TIDAK ditampilkan ke pelapor di UI.
                'ditangani_oleh'      => in_array($status, ['sedang_diperbaiki', 'selesai'])
                    ? $teknisi->id
                    : null,
            ]);

            // Sinkronisasi kondisi aset berdasarkan status repair.
            // Laporan selesai    → aset kembali aktif (disertai riwayat kondisi).
            // pending/diperbaiki → aset dikunci ke kondisi rusak.
            if ($repair->asset_id) {
                if ($status === 'selesai') {
                    $asset->update(['kondisi_barang' => 'aktif']);

                    // PENTING: 'changed_at' tidak ada di $fillable AssetConditionHistory.
                    // Sama seperti di seedAssets(), gunakan create() + DB::table()->update().
                    $history = AssetConditionHistory::create([
                        'asset_id'     => $asset->id,
                        'kondisi_lama' => 'rusak',
                        'kondisi_baru' => 'aktif',
                        'catatan'      => "Perbaikan selesai — {$kodePerbaikan}",
                        'changed_by'   => $teknisi->id,
                    ]);

                    DB::table('asset_condition_histories')
                        ->where('id', $history->id)
                        ->update(['changed_at' => $tanggalSelesai]);

                } elseif (in_array($status, ['pending', 'sedang_diperbaiki'])) {
                    if ($asset->kondisi_barang !== 'rusak') {
                        $kondisiSebelumnya = $asset->kondisi_barang;
                        $asset->update(['kondisi_barang' => 'rusak']);

                        $history = AssetConditionHistory::create([
                            'asset_id'     => $asset->id,
                            'kondisi_lama' => $kondisiSebelumnya,
                            'kondisi_baru' => 'rusak',
                            'catatan'      => "Dilaporkan rusak — {$kodePerbaikan}",
                            'changed_by'   => $repair->dilaporkan_oleh,
                        ]);

                        DB::table('asset_condition_histories')
                            ->where('id', $history->id)
                            ->update(['changed_at' => $tglLaporan]);
                    }
                }
            }

            $counter++;
        }
    }
}