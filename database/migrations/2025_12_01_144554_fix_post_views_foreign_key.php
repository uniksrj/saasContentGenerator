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
        if (!Schema::hasTable('post_views') || !Schema::hasColumn('post_views', 'post_id')) {
            return;
        }

        Schema::table('post_views', function (Blueprint $table) {
            // First, drop the existing foreign key
            $table->dropForeign(['post_id']);
        });

        // Add the correct foreign key
        Schema::table('post_views', function (Blueprint $table) {
            $table->foreign('post_id')
                  ->references('id')
                  ->on('newpost_details')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('post_views') || !Schema::hasColumn('post_views', 'post_id')) {
            return;
        }

        Schema::table('post_views', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
        });

        if (!Schema::hasTable('posts')) {
            return;
        }

        Schema::table('post_views', function (Blueprint $table) {
            $table->foreign('post_id')
                  ->references('id')
                  ->on('posts')
                  ->cascadeOnDelete();
        });
    }
};
