<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        // Tabel master unit/yayasan.
        // Flag is_yayasan untuk unit khusus "Yayasan" (aset tanpa unit tertentu).
        // Aset unit Yayasan boleh diubah lokasi penempatannya tanpa mutasi antar unit.
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('nama_unit')->unique();
            $table->string('kode_unit')->unique(); // Bagian dari kode aset
            $table->text('deskripsi')->nullable();
            $table->boolean('is_yayasan')->default(false); // Flag unit "Yayasan"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
 
        // Tabel master sumber dana — dinamis, bisa ditambah admin.
        Schema::create('funding_sources', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sumber')->unique();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
 
        // Tabel master satuan aset — bersifat TETAP (diisi via seeder).
        Schema::create('units_satuan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_satuan')->unique();
            $table->timestamps();
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('units_satuan');
        Schema::dropIfExists('funding_sources');
        Schema::dropIfExists('units');
    }
};
