<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perusahaans', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Relasi ke user yang memiliki akun perusahaan
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            // Data umum perusahaan
            $table->string('nama');
            $table->string('industri')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota')->nullable();

            // Dokumen & logo
            $table->string('logo')->nullable();
            $table->string('npwp')->nullable();
            $table->string('siup')->nullable();
            $table->string('dokumen_legal')->nullable();

            // Status verifikasi
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaans');
    }
};
