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
        Schema::create('lowongans', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Relasi ke perusahaan yang memasang lowongan
            $table->uuid('perusahaan_id');
            $table->foreign('perusahaan_id')
                  ->references('id')->on('perusahaans')
                  ->cascadeOnDelete();

            // Informasi inti lowongan
            $table->string('judul');
            $table->string('slug', 180)->unique(); // URL cantik/SEO
            $table->string('tipe')->nullable();    // fulltime/parttime/intern/contract
            $table->string('level')->nullable();   // junior/middle/senior
            $table->string('lokasi')->nullable();  // kota / remote / hybrid

            // Rentang gaji (opsional)
            $table->unsignedInteger('gaji_min')->nullable();
            $table->unsignedInteger('gaji_max')->nullable();

            // Konten
            $table->text('deskripsi')->nullable();
            $table->text('kualifikasi')->nullable();

            // Tag skill (opsional) disimpan sebagai JSON array
            $table->json('tag')->nullable(); // contoh: ["Laravel","SQL","Komunikasi"]

            // Status & tenggat
            $table->date('deadline')->nullable();
            $table->boolean('aktif')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lowongans');
    }
};
