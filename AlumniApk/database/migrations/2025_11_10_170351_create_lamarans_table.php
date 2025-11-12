<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lamarans', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('lowongan_id');
            $table->uuid('alumni_id');

            $table->foreign('lowongan_id')
                  ->references('id')->on('lowongans')
                  ->cascadeOnDelete();

            $table->foreign('alumni_id')
                  ->references('id')->on('alumnis')
                  ->cascadeOnDelete();

            // Info lamaran
            $table->enum('status', [
                'submitted',
                'review',
                'shortlist',
                'interview',
                'accepted',
                'rejected',
                'withdrawn',
            ])->default('submitted');

            $table->string('cv_path')->nullable();           // storage path file CV
            $table->string('surat_lamaran_path')->nullable();// storage path surat lamaran
            $table->string('portfolio_url')->nullable();     // link portofolio (opsional)
            $table->text('catatan')->nullable();             // catatan dari pelamar atau HR

            $table->dateTime('jadwal_interview')->nullable();

            // Satu alumni hanya boleh melamar 1 kali pada lowongan yang sama
            $table->unique(['lowongan_id', 'alumni_id']);

            // indeks bantu
            $table->index('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lamarans');
    }
};
