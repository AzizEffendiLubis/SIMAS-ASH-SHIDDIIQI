<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('unit_kerja')->nullable();
           $table->enum('role', ['super_admin', 'kepala_yayasan', 'admin_unit', 'petugas_perbaikan', 'user'])->default('user');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->json('menu_access')->nullable(); // custom menu access per user
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
