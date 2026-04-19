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
        Schema::create('newpost_details', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Suraj');
            $table->string('title');
            $table->timestamp('created_date');
            $table->string('description');
            $table->string('category');
            $table->string('file_path')->nullable();
            $table->integer('active')->default(1);
            $table->rememberToken();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newpost_details');
    }
};
