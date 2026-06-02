<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        // TABEL: activity_logs
        // 
        // Log audit seluruh aktivitas sistem.
        //
        // Desain polimorfik (subject_type + subject_id) agar satu tabel
        // bisa mencatat log untuk semua entitas (assets, users, repairs)
        // tanpa FK yang kaku.
        //
        // Contoh action: 'tambah_aset', 'edit_kondisi_aset',
        //   'tambah_pengguna', 'nonaktifkan_pengguna',
        //   'update_progres_perbaikan', 'ubah_lokasi_aset_yayasan'
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
 
            // Pengguna yang melakukan aksi. Nullable untuk aksi sistem otomatis.
            // nullOnDelete: jika user dinonaktifkan, log tetap terjaga.
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
 
            // Jenis aksi yang dilakukan.
            $table->string('action');
 
            // Entitas target aksi (polimorfik).
            // Contoh: subject_type='App\Models\Asset', subject_id=5
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
 
            // Deskripsi dalam bahasa manusia untuk Kepala Yayasan.
            // Contoh: "Menambahkan aset Laptop Dell (YYS-20240115-001) ke unit IT"
            $table->text('description')->nullable();
 
            // Snapshot data sebelum & sesudah perubahan (JSON audit trail).
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
 
            // IP address untuk keperluan keamanan & audit.
            $table->string('ip_address', 45)->nullable();
 
            $table->timestamp('created_at')->useCurrent();
 
            // Index untuk query cepat di halaman log Kepala Yayasan.
            $table->index(['subject_type', 'subject_id']);
            $table->index('user_id');
            $table->index('created_at');
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
