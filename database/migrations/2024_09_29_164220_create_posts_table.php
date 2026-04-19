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
        if (!Schema::hasTable('posts')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'views_count')) {
                $table->integer('views_count')->default(0)->after('description');
            }

            if (!Schema::hasColumn('posts', 'comments_count')) {
                $table->integer('comments_count')->default(0)->after('views_count');
            }

            if (!Schema::hasColumn('posts', 'slug')) {
                $table->string('slug')->unique()->nullable()->after('title');
            }

            if (!Schema::hasColumn('posts', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('slug');
            }

            if (!Schema::hasColumn('posts', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }

            if (!Schema::hasColumn('posts', 'meta_keywords')) {
                $table->string('meta_keywords')->nullable()->after('meta_description');
            }

            if (!Schema::hasColumn('posts', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('meta_keywords');
            }

            if (!Schema::hasColumn('posts', 'is_published')) {
                $table->boolean('is_published')->default(true)->after('is_featured');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('posts')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $columns = [
                'views_count',
                'comments_count',
                'slug',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'is_featured',
                'is_published',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('posts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
