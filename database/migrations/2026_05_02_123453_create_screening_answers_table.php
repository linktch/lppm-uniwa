<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('screening_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table
                ->foreignId('periode_id')
                ->references('id')
                ->on('periode')
                ->cascadeOnDelete();
            $table
                ->foreignId('kegiatan_id')
                ->references('id')
                ->on('kegiatan')
                ->cascadeOnDelete();

            $table->foreignId('question_id')->constrained('screening_questions')->cascadeOnDelete();

            $table->boolean('jawaban');  // true = ya, false = tidak
            $table->text('keterangan')->nullable();  // tambahan

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screening_answers');
    }
};
