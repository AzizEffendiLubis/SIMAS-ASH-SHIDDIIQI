<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->string('kategori'); // Elektronik, Furnitur, Komputer, dll
            $table->string('lokasi_barang');
            $table->string('unit_kerja');
            $table->integer('jumlah_barang')->default(1);
            $table->enum('kondisi_barang', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik');
            $table->enum('sumber_dana', ['Dana Yayasan', 'Dana BOS', 'Hibah', 'Lainnya'])->default('Dana Yayasan');
            $table->decimal('harga_barang', 15, 2)->default(0);
            $table->date('tanggal_pengadaan')->nullable();
            $table->string('foto')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
