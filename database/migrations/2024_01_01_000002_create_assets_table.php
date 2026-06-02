<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        // TABEL: assets
        // 
        // Kondisi barang (versi final):
        //   aktif       : barang baik, sedang digunakan
        //   rusak       : barang rusak (ringan maupun berat)
        //   hilang      : barang tidak ditemukan
        //   habis_pakai : barang sudah habis masa pakainya
        // Catatan: 'dipindahkan' TIDAK ada — fitur mutasi dihapus.
        //
        // Kolom yang TIDAK BISA diubah setelah disimpan (app layer):
        //   nama_barang, kategori, spesifikasi, harga_barang,
        //   tanggal_pengadaan, unit_id, jumlah_barang, satuan_id,
        //   sumber_dana_id.
        //
        // Kolom yang BOLEH diubah admin:
        //   kondisi_barang (via asset_condition_histories)
        //   foto (via asset_photos)
        //   lokasi_barang — HANYA untuk aset unit Yayasan (is_yayasan=true)
        //
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
 
            // Kode aset di-generate otomatis: [kode_unit]-[YYYYMMDD]-[urut]
            // Dokumen UC-AUT-01: "Generate kode aset berdasarkan unit kerja,
            //                     tanggal penambahan, dan nomor urut."
            $table->string('kode_aset')->unique();
 
            // --- Informasi utama (TIDAK BISA diubah setelah disimpan) ---
            $table->string('nama_barang');
            $table->string('kategori');                    // Elektronik, Furnitur, dll.
            $table->string('spesifikasi')->nullable();
            $table->decimal('harga_barang', 15, 2)->default(0);
            $table->date('tanggal_pengadaan')->nullable();
 
            $table->foreignId('unit_id')                   // Termasuk unit "Yayasan"
                  ->constrained('units')
                  ->restrictOnDelete();
 
            $table->integer('jumlah_barang')->default(1);
 
            $table->foreignId('satuan_id')                 // Master satuan (tetap)
                  ->nullable()
                  ->constrained('units_satuan')
                  ->nullOnDelete();
 
            $table->foreignId('sumber_dana_id')            // Master sumber dana (dinamis)
                  ->nullable()
                  ->constrained('funding_sources')
                  ->nullOnDelete();
 
            // --- Informasi yang boleh diubah admin ---
 
            // Lokasi aset: untuk unit biasa tidak bisa diubah setelah input.
            // Untuk unit Yayasan (is_yayasan=true) bisa diubah tanpa mutasi.
            // Enforcement dilakukan di application layer berdasarkan unit_id.
            $table->string('lokasi_barang')->nullable();
 
            // Kondisi aset — nilai final sesuai dokumen terbaru.
            $table->enum('kondisi_barang', [
                'aktif',
                'rusak',
                'hilang',
                'habis_pakai',
            ])->default('aktif');
 
            // --- Metadata ---
 
            // Dasar/keterangan persetujuan penambahan aset.
            $table->text('keterangan_dasar')->nullable();
            $table->text('keterangan')->nullable();        // Catatan tambahan bebas
 
            // Siapa yang menginput — wajib ada di log aktivitas.
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
 
            $table->timestamps();
        });
 
        // TABEL: asset_photos
        // 
        // Multi-foto per aset. Foto bisa ditambah/hapus oleh admin.
        Schema::create('asset_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')
                  ->constrained('assets')
                  ->cascadeOnDelete();
            $table->string('file_path');
            $table->boolean('is_primary')->default(false); // Foto utama
            $table->timestamps();
        });
 
        // TABEL: asset_condition_histories
        // 
        // Setiap perubahan kondisi atau lokasi aset dicatat di sini.
        // Mendukung 3 jenis laporan (selain kerusakan):
        //   - Laporan kehilangan  → kondisi_baru = 'hilang'
        //   - Laporan habis pakai → kondisi_baru = 'habis_pakai'
        //   - Perubahan lokasi    → lokasi_lama & lokasi_baru diisi (khusus unit Yayasan)
        Schema::create('asset_condition_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')
                  ->constrained('assets')
                  ->cascadeOnDelete();
 
            $table->enum('kondisi_lama', [
                'aktif', 'rusak', 'hilang', 'habis_pakai',
            ])->nullable();
            $table->enum('kondisi_baru', [
                'aktif', 'rusak', 'hilang', 'habis_pakai',
            ])->nullable();
 
            // Riwayat perubahan lokasi (khusus aset unit Yayasan)
            $table->string('lokasi_lama')->nullable();
            $table->string('lokasi_baru')->nullable();
 
            $table->text('catatan')->nullable();
 
            $table->foreignId('changed_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
 
            $table->timestamp('changed_at')->useCurrent();
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('asset_condition_histories');
        Schema::dropIfExists('asset_photos');
        Schema::dropIfExists('assets');
    }
};
