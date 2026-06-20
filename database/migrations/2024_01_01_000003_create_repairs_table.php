<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        // TABEL: repairs
        // 
        // Khusus untuk alur laporan KERUSAKAN yang melibatkan teknisi.
        //
        // Arsitektur laporan (versi final):
        //   1. Kerusakan aset  → tabel ini (repairs + repair_photos)
        //   2. Kehilangan aset → ubah kondisi_barang = 'hilang'
        //                        + catat di asset_condition_histories
        //   3. Habis pakai     → ubah kondisi_barang = 'habis_pakai'
        //                        + catat di asset_condition_histories
        //
        // Laporan kehilangan & habis pakai TIDAK butuh tabel tersendiri
        // karena hanya mengubah kondisi aset + dicatat di histories.
        //
        // Ketentuan laporan kerusakan:
        // - nama_aset_laporan: ditulis manual, BUKAN dropdown
        // - asset_id: nullable FK, dikaitkan admin setelah verifikasi
        // - foto: tabel repair_photos (bisa lebih dari satu)
        // - lokasi_kerusakan: deskripsi teks bebas
        // - ditangani_oleh: ada di DB, TIDAK tampil ke pelapor di UI
        // - Urutan antrian FIFO berdasarkan tanggal_laporan
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
 
            // Kode laporan unik, di-generate otomatis. Contoh: LAP-20240115-0001
            $table->string('kode_perbaikan')->unique();
 
            // Nama aset ditulis manual oleh pelapor — BUKAN dropdown.
            $table->string('nama_aset_laporan');
 
            // FK opsional ke assets — bisa dikaitkan admin setelah verifikasi.
            $table->foreignId('asset_id')
                  ->nullable()
                  ->constrained('assets')
                  ->nullOnDelete();
 
            $table->text('deskripsi_kerusakan');
 
            // Lokasi kerusakan dalam bentuk deskripsi teks.
            $table->string('lokasi_kerusakan')->nullable();
 
            // Status progres perbaikan.
            $table->enum('status', [
                'pending',
                'sedang_diperbaiki',
                'selesai',
                'tidak_dapat_diperbaiki'
            ])->default('pending');
 
            // Catatan tindakan teknisi — diisi saat update progres.
            $table->text('tindakan_perbaikan')->nullable();
 
            // Tanggal laporan masuk — dasar urutan antrian FIFO.
            $table->timestamp('tanggal_laporan')->useCurrent();
            $table->date('tanggal_selesai')->nullable();
            $table->decimal('biaya_perbaikan', 15, 2)->nullable();
 
            // Pelapor: Admin Utama, Admin Unit, atau User.
            $table->foreignId('dilaporkan_oleh')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
 
            // Teknisi penanganan: ADA di DB, TIDAK tampil ke pelapor di UI.
            $table->foreignId('ditangani_oleh')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
 
            $table->timestamps();
        });
 
        // TABEL: repair_photos
        //
        // Multi-foto per laporan kerusakan.
        Schema::create('repair_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_id')
                  ->constrained('repairs')
                  ->cascadeOnDelete();
            $table->string('file_path');
            $table->timestamps();
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('repair_photos');
        Schema::dropIfExists('repairs');
    }
};
