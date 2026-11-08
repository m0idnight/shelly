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
    Schema::create('quiz_questions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('study_set_id')->constrained()->cascadeOnDelete();
        $table->text('question');
        $table->json('options');
        $table->unsignedTinyInteger('correct_index');
        $table->text('explanation');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
