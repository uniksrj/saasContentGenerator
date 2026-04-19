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
        Schema::table('newpost_details', function (Blueprint $table) {
        $table->json('table_of_contents')->nullable()->after('description');
        $table->string('reading_time')->nullable()->after('table_of_contents');
        $table->integer('word_count')->nullable()->after('reading_time');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newpost_details', function (Blueprint $table) {
            //
        });
    }
};
