<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('flashcards', 'reviewed')) {
            Schema::table('flashcards', function (Blueprint $table) {
                $table->boolean('reviewed')->default(false);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('flashcards', 'reviewed')) {
            Schema::table('flashcards', function (Blueprint $table) {
                $table->dropColumn('reviewed');
            });
        }
    }
};