<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        // Tabel pengguna sistem.
        //
        // Aturan penting:
        // - Akun dibuat admin utama (tidak ada registrasi mandiri)
        // - username = NIS/NIP, tidak bisa diubah pengguna
        // - name (nama lengkap), tidak bisa diubah pengguna
        // - must_change_password: wajib ganti saat login pertama
        // - Pengguna tidak dihapus, hanya dinonaktifkan (status nonaktif)
        // - Seluruh data & riwayat tetap terjaga meski akun nonaktif
        //
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();          // NIS/NIP, terkunci
            $table->string('name');                        // Nama lengkap, terkunci
            $table->string('email')->unique()->nullable(); // Opsional
            $table->string('phone')->nullable();
            $table->string('jabatan')->nullable();
 
            $table->foreignId('unit_id')                   // FK ke tabel units
                  ->nullable()
                  ->constrained('units')
                  ->nullOnDelete();
 
            // Role sesuai aktor di dokumen Use Case:
            //   kepala_yayasan : monitoring (dashboard, laporan, log) — tidak bisa edit
            //   admin_utama    : kelola semua aset & pengguna
            //   admin_unit     : kelola aset unit, laporkan kerusakan
            //   teknisi        : lihat & update progres perbaikan
            //   user           : laporkan kerusakan, lihat ringkasan aset unit
            $table->enum('role', [
                'kepala_yayasan',
                'admin_utama',
                'admin_unit',
                'teknisi',
                'user',
            ])->default('user');
 
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
 
            // Hak akses menu kustom per pengguna (JSON).
            // Nullable: kepala_yayasan & teknisi punya akses tetap.
            $table->json('menu_access')->nullable();
 
            $table->string('password');
 
            // Wajib ganti password saat login pertama.
            // Default true agar berlaku untuk setiap akun baru.
            $table->boolean('must_change_password')->default(true);
 
            $table->rememberToken();
            $table->timestamps();
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
