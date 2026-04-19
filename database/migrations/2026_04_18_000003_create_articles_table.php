<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();
            $table->foreignId('legacy_post_id')->nullable()->constrained('newpost_details')->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->longText('content');
            $table->string('category', 120)->nullable();
            $table->string('source_type', 50)->nullable();
            $table->string('source_url', 2048)->nullable();
            $table->string('status', 50)->default('draft');
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->json('tags')->nullable();
            $table->json('table_of_contents')->nullable();
            $table->unsignedInteger('word_count')->default(0);
            $table->unsignedInteger('reading_time')->default(1);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'slug']);
            $table->index(['project_id', 'status']);
            $table->index(['project_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
