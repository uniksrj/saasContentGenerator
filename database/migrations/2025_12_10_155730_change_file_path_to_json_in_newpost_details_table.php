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
            // Change from VARCHAR to JSON or TEXT
            $table->json('file_path')->nullable()->change();
            
            // OR if your MySQL version doesn't support JSON change:
            // $table->text('file_path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('newpost_details', function (Blueprint $table) {
            // Change back to VARCHAR
            $table->string('file_path', 255)->nullable()->change();
        });
    }
};
