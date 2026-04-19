<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'plan_id')) {
                $table->foreignId('plan_id')->nullable()->after('password')->constrained('plans')->nullOnDelete();
            }

            if (!Schema::hasColumn('users', 'current_project_id')) {
                $table->foreignId('current_project_id')->nullable()->after('plan_id')->constrained('projects')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'current_project_id')) {
                $table->dropConstrainedForeignId('current_project_id');
            }

            if (Schema::hasColumn('users', 'plan_id')) {
                $table->dropConstrainedForeignId('plan_id');
            }
        });
    }
};
