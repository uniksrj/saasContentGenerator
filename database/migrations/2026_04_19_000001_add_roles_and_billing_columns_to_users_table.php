<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 20)->default('user')->after('email');
                $table->index('role');
            }

            if (!Schema::hasColumn('users', 'article_limit_override')) {
                $table->unsignedInteger('article_limit_override')->nullable()->after('current_project_id');
            }

            if (!Schema::hasColumn('users', 'token_limit_override')) {
                $table->unsignedInteger('token_limit_override')->nullable()->after('article_limit_override');
            }

            if (!Schema::hasColumn('users', 'stripe_customer_id')) {
                $table->string('stripe_customer_id')->nullable()->after('token_limit_override');
                $table->unique('stripe_customer_id');
            }

            if (!Schema::hasColumn('users', 'stripe_subscription_id')) {
                $table->string('stripe_subscription_id')->nullable()->after('stripe_customer_id');
                $table->unique('stripe_subscription_id');
            }

            if (!Schema::hasColumn('users', 'stripe_price_id')) {
                $table->string('stripe_price_id')->nullable()->after('stripe_subscription_id');
            }

            if (!Schema::hasColumn('users', 'subscription_status')) {
                $table->string('subscription_status', 30)->default('inactive')->after('stripe_price_id');
                $table->index('subscription_status');
            }

            if (!Schema::hasColumn('users', 'subscribed_at')) {
                $table->timestamp('subscribed_at')->nullable()->after('subscription_status');
            }

            if (!Schema::hasColumn('users', 'current_period_starts_at')) {
                $table->timestamp('current_period_starts_at')->nullable()->after('subscribed_at');
            }

            if (!Schema::hasColumn('users', 'current_period_ends_at')) {
                $table->timestamp('current_period_ends_at')->nullable()->after('current_period_starts_at');
            }

            if (!Schema::hasColumn('users', 'subscription_ends_at')) {
                $table->timestamp('subscription_ends_at')->nullable()->after('current_period_ends_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropUnique('users_stripe_customer_id_unique');
            } catch (\Throwable $e) {
                // Ignore missing indexes during rollback.
            }

            try {
                $table->dropUnique('users_stripe_subscription_id_unique');
            } catch (\Throwable $e) {
                // Ignore missing indexes during rollback.
            }

            try {
                $table->dropIndex('users_role_index');
            } catch (\Throwable $e) {
                // Ignore missing indexes during rollback.
            }

            try {
                $table->dropIndex('users_subscription_status_index');
            } catch (\Throwable $e) {
                // Ignore missing indexes during rollback.
            }

            foreach ([
                'subscription_ends_at',
                'current_period_ends_at',
                'current_period_starts_at',
                'subscribed_at',
                'subscription_status',
                'stripe_price_id',
                'stripe_subscription_id',
                'stripe_customer_id',
                'token_limit_override',
                'article_limit_override',
                'role',
            ] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
