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
        if (!Schema::hasColumn('newpost_details', 'status')) {
            Schema::table('newpost_details', function (Blueprint $table) {
                $table->string('status', 20)->default('published')->after('is_published');
            });
        }

        if (!Schema::hasColumn('newpost_details', 'source_url')) {
            Schema::table('newpost_details', function (Blueprint $table) {
                $table->text('source_url')->nullable()->after('category');
            });
        }

        if (Schema::hasColumn('newpost_details', 'status')) {
            DB::table('newpost_details')
                ->whereNull('status')
                ->update(['status' => 'published']);

            if (Schema::hasColumn('newpost_details', 'is_published')) {
                DB::table('newpost_details')
                    ->where('is_published', 0)
                    ->update(['status' => 'draft']);

                DB::table('newpost_details')
                    ->where('is_published', 1)
                    ->update(['status' => 'published']);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('newpost_details', 'source_url')) {
            Schema::table('newpost_details', function (Blueprint $table) {
                $table->dropColumn('source_url');
            });
        }

        if (Schema::hasColumn('newpost_details', 'status')) {
            Schema::table('newpost_details', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
