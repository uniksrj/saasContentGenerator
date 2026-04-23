<?php

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('status')->default(Project::STATUS_ACTIVE)->after('description');
            $table->index(['user_id', 'status']);
        });

        DB::table('projects')
            ->where('is_active', true)
            ->update(['status' => Project::STATUS_ACTIVE]);

        DB::table('projects')
            ->where('is_active', false)
            ->update(['status' => Project::STATUS_DISABLED]);
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropColumn('status');
        });
    }
};
