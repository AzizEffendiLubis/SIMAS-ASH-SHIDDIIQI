<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_perbaikan')->unique();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->text('deskripsi_kerusakan');
            $table->text('tindakan_perbaikan')->nullable();
            $table->enum('status', ['Pending', 'Sedang Diperbaiki', 'Selesai'])->default('Pending');
            $table->date('tanggal_laporan');
            $table->date('tanggal_selesai')->nullable();
            $table->decimal('biaya_perbaikan', 15, 2)->nullable();
            $table->foreignId('dilaporkan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('ditangani_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->string('foto_kerusakan')->nullable();
            $table->timestamps();
        });

        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengadaan')->unique();
            $table->string('nama_barang');
            $table->string('kategori');
            $table->string('unit_kerja');
            $table->integer('jumlah')->default(1);
            $table->decimal('estimasi_harga', 15, 2)->default(0);
            $table->enum('sumber_dana', ['Dana Yayasan', 'Dana BOS', 'Hibah', 'Lainnya'])->default('Dana Yayasan');
            $table->text('alasan_pengadaan');
            $table->enum('status', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
            $table->text('catatan_approval')->nullable();
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_approval')->nullable();
            $table->foreignId('diajukan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurements');
        Schema::dropIfExists('repairs');
    }
};
