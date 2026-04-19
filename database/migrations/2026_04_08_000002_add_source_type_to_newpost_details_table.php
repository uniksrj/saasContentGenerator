<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('newpost_details', 'source_type')) {
            Schema::table('newpost_details', function (Blueprint $table) {
                $table->string('source_type', 20)->nullable()->after('source_url');
            });
        }

        if (Schema::hasColumn('newpost_details', 'source_type')) {
            DB::table('newpost_details')
                ->whereNull('source_type')
                ->update([
                    'source_type' => DB::raw("CASE WHEN source_url IS NULL OR source_url = '' THEN 'manual' ELSE 'rss' END"),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('newpost_details', 'source_type')) {
            Schema::table('newpost_details', function (Blueprint $table) {
                $table->dropColumn('source_type');
            });
        }
    }
};
