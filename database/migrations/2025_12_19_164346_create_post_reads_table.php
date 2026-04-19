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
        Schema::create('post_reads', function ($table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->string('category_name');   // ✅ text
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('time_spent'); // seconds
            $table->integer('scroll_percent');
            $table->timestamps();

            $table->index('category_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_reads');
    }
};
