<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 100);
            $table->unsignedInteger('units')->default(1);
            $table->json('metadata')->nullable();
            $table->date('used_on');
            $table->timestamps();

            $table->index(['user_id', 'action', 'used_on']);
            $table->index(['project_id', 'action', 'used_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_logs');
    }
};
