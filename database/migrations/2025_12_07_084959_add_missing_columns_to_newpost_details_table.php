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
        // First change description from string to text if needed
        if (Schema::hasColumn('newpost_details', 'description')) {
            $table->text('description')->change();
        }
        
        // Add the missing columns
        $table->integer('views_count')->default(0)->after('description');
        $table->integer('comments_count')->default(0)->after('views_count');
        $table->string('slug')->unique()->nullable()->after('title');
        $table->string('meta_title')->nullable()->after('slug');
        $table->text('meta_description')->nullable()->after('meta_title');
        $table->string('meta_keywords')->nullable()->after('meta_description');
        $table->boolean('is_featured')->default(false)->after('meta_keywords');
        $table->boolean('is_published')->default(true)->after('is_featured');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('newpost_details', function (Blueprint $table) {
        $table->dropColumn([
            'views_count',
            'comments_count',
            'slug',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'is_featured',
            'is_published'
        ]);
        
        $table->string('description')->change();
    });
    }
};
