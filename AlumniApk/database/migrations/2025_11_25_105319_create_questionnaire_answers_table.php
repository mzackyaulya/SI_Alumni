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
        Schema::create('questionnaire_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->constrained('questionnaire_questions')
                ->cascadeOnDelete();

            // alumni_id mengikuti UUID di tabel alumnis
            $table->uuid('alumni_id');

            $table->text('answer')->nullable(); // isi jawaban
            $table->timestamps();

            $table->foreign('alumni_id')
                ->references('id')
                ->on('alumnis')
                ->cascadeOnDelete();

            // 1 alumni hanya 1 jawaban per pertanyaan dalam 1 kuesioner
            $table->unique(['question_id', 'alumni_id'], 'qa_question_alumni_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_answers');
    }
};
