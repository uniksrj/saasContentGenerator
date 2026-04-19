<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'description')) {
                $table->text('description')->nullable()->after('name');
            }

            if (!Schema::hasColumn('plans', 'monthly_token_limit')) {
                $table->unsignedInteger('monthly_token_limit')->default(120000)->after('monthly_article_limit');
            }

            if (!Schema::hasColumn('plans', 'stripe_price_id')) {
                $table->string('stripe_price_id')->nullable()->after('currency');
                $table->unique('stripe_price_id');
            }

            if (!Schema::hasColumn('plans', 'billing_interval')) {
                $table->string('billing_interval', 20)->default('month')->after('stripe_price_id');
            }
        });

        $now = now();

        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Entry-level plan for getting started with automated article generation.',
                'monthly_article_limit' => 10,
                'monthly_token_limit' => 120000,
                'price_cents' => 0,
                'currency' => 'USD',
                'stripe_price_id' => null,
                'billing_interval' => 'month',
                'is_active' => true,
                'features' => json_encode([
                    '10 AI articles per month',
                    'Single active subscription',
                    'Usage dashboard',
                    'Project management',
                ]),
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'For growing teams that need higher monthly generation capacity.',
                'monthly_article_limit' => 50,
                'monthly_token_limit' => 600000,
                'price_cents' => 2900,
                'currency' => 'USD',
                'stripe_price_id' => env('STRIPE_PRICE_PRO'),
                'billing_interval' => 'month',
                'is_active' => true,
                'features' => json_encode([
                    '50 AI articles per month',
                    'Priority generation access',
                    'Expanded usage limits',
                    'Team-ready analytics',
                ]),
                'updated_at' => $now,
                'created_at' => $now,
            ],
            [
                'name' => 'Scale',
                'slug' => 'scale',
                'description' => 'High-volume content operations with larger article and token allowances.',
                'monthly_article_limit' => 200,
                'monthly_token_limit' => 2500000,
                'price_cents' => 9900,
                'currency' => 'USD',
                'stripe_price_id' => env('STRIPE_PRICE_SCALE'),
                'billing_interval' => 'month',
                'is_active' => true,
                'features' => json_encode([
                    '200 AI articles per month',
                    'Large token allowance',
                    'Admin usage oversight',
                    'Scale-ready operations',
                ]),
                'updated_at' => $now,
                'created_at' => $now,
            ],
        ];

        foreach ($plans as $plan) {
            DB::table('plans')->updateOrInsert(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            try {
                $table->dropUnique('plans_stripe_price_id_unique');
            } catch (\Throwable $e) {
                // Ignore missing indexes during rollback.
            }

            foreach ([
                'billing_interval',
                'stripe_price_id',
                'monthly_token_limit',
                'description',
            ] as $column) {
                if (Schema::hasColumn('plans', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
