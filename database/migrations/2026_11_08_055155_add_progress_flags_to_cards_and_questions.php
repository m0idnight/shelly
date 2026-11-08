<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flashcards', function (Blueprint $table) {
            $table->boolean('known')->default(false);
        });

        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->boolean('missed')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('flashcards', function (Blueprint $table) {
            $table->dropColumn('known');
        });

        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->dropColumn('missed');
        });
    }
};