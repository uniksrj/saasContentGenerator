<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('source_type', 50)->default('rss');
            $table->string('source_url', 2048)->nullable();
            $table->unsignedInteger('engagement_score')->default(0);
            $table->integer('score')->default(0);
            $table->json('score_breakdown')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('status', 50)->default('fetched');
            $table->json('raw_payload')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'score']);
            $table->index(['project_id', 'status']);
            $table->index(['project_id', 'source_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
