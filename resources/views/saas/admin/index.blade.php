@extends('saas.layouts.app')

@section('title', 'Admin')

@section('content')
<div class="space-y-8">
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Administration</p>
        <h2 class="mt-2 text-2xl font-semibold text-slate-900">User, Plan & Usage Control</h2>
        <p class="mt-2 text-sm text-slate-500">Manage subscription access, roles, plan limits, and system-wide usage from one panel.</p>
    </section>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Users</p>
            <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $adminStats['users'] }}</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Admins</p>
            <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $adminStats['admins'] }}</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Active Subs</p>
            <p class="mt-3 text-3xl font-semibold text-emerald-600">{{ $adminStats['active_subscriptions'] }}</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Articles This Month</p>
            <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($adminStats['articles_generated']) }}</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Tokens This Month</p>
            <p class="mt-3 text-3xl font-semibold text-cyan-600">{{ number_format($adminStats['tokens_used']) }}</p>
        </article>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-slate-900">Users</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">User</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Workspace</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Usage</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Access</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Update</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @foreach($users as $managedUser)
                        <tr class="align-top">
                            <td class="px-4 py-4">
                                <p class="text-sm font-semibold text-slate-900">{{ $managedUser->name }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $managedUser->email }}</p>
                                <p class="mt-1 text-xs text-slate-500">Current plan: {{ $managedUser->plan?->name ?? 'None' }}</p>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-600">
                                <p>{{ $managedUser->projects_count }} projects</p>
                                <p class="mt-1">{{ $managedUser->articles_count }} articles</p>
                                <p class="mt-1">Current project: {{ $managedUser->currentProject?->name ?? 'None' }}</p>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-600">
                                <p>Articles: {{ $managedUser->used_articles }} / {{ $managedUser->usage_snapshot['remaining_articles'] + $managedUser->used_articles }}</p>
                                <p class="mt-1">Tokens: {{ number_format($managedUser->used_tokens) }} / {{ number_format($managedUser->usage_snapshot['remaining_tokens'] + $managedUser->used_tokens) }}</p>
                                <p class="mt-1">{{ $managedUser->usage_snapshot['message'] }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <form action="{{ route('saas.admin.users.update', $managedUser) }}" method="POST" class="grid gap-2 sm:grid-cols-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="rounded-lg border border-slate-300 px-3 py-2 text-xs">
                                        <option value="user" @selected($managedUser->role === 'user')>User</option>
                                        <option value="admin" @selected($managedUser->role === 'admin')>Admin</option>
                                    </select>
                                    <select name="plan_id" class="rounded-lg border border-slate-300 px-3 py-2 text-xs">
                                        <option value="">No plan</option>
                                        @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}" @selected($managedUser->plan_id === $plan->id)>{{ $plan->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="subscription_status" class="rounded-lg border border-slate-300 px-3 py-2 text-xs">
                                        @foreach(['inactive', 'active', 'trialing', 'past_due', 'cancelled'] as $status)
                                            <option value="{{ $status }}" @selected($managedUser->subscription_status === $status)>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                    <input name="article_limit_override" type="number" min="0" value="{{ $managedUser->article_limit_override }}" placeholder="Article override" class="rounded-lg border border-slate-300 px-3 py-2 text-xs">
                                    <input name="token_limit_override" type="number" min="0" value="{{ $managedUser->token_limit_override }}" placeholder="Token override" class="rounded-lg border border-slate-300 px-3 py-2 text-xs sm:col-span-2">
                                    <input name="current_period_ends_at" type="date" value="{{ optional($managedUser->current_period_ends_at)->format('Y-m-d') }}" class="rounded-lg border border-slate-300 px-3 py-2 text-xs sm:col-span-2">
                                    <div class="sm:col-span-2 flex justify-end">
                                        <button type="submit" class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700">Save User</button>
                                    </div>
                                </form>
                            </td>
                            <td class="px-4 py-4 text-right text-xs text-slate-500">
                                {{ ucfirst($managedUser->subscription_status) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-slate-900">Plans</h3>
        </div>
        <div class="grid gap-4 p-6 lg:grid-cols-3">
            @foreach($plans as $plan)
                <form action="{{ route('saas.admin.plans.update', $plan) }}" method="POST" class="rounded-2xl border border-slate-200 p-5 shadow-sm">
                    @csrf
                    @method('PATCH')
                    <div class="space-y-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-400">Name</label>
                            <input name="name" value="{{ $plan->name }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-400">Description</label>
                            <textarea name="description" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ $plan->description }}</textarea>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-400">Articles</label>
                                <input type="number" name="monthly_article_limit" min="0" value="{{ $plan->monthly_article_limit }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-400">Tokens</label>
                                <input type="number" name="monthly_token_limit" min="0" value="{{ $plan->monthly_token_limit }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            </div>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-400">Price cents</label>
                                <input type="number" name="price_cents" min="0" value="{{ $plan->price_cents }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-400">Currency</label>
                                <input name="currency" value="{{ $plan->currency }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-400">Stripe price ID</label>
                            <input name="stripe_price_id" value="{{ $plan->stripe_price_id }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-400">Billing interval</label>
                            <select name="billing_interval" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <option value="month" @selected($plan->billing_interval === 'month')>Month</option>
                                <option value="year" @selected($plan->billing_interval === 'year')>Year</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-400">Features</label>
                            <textarea name="features" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">@foreach($plan->features ?? [] as $feature){{ $feature }}
@endforeach</textarea>
                        </div>
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300" @checked($plan->is_active)>
                            Plan active
                        </label>
                        <button type="submit" class="w-full rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">Save Plan</button>
                    </div>
                </form>
            @endforeach
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-slate-900">Recent Usage Logs</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">User</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Project</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Action</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Units</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">When</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($recentUsage as $log)
                        <tr>
                            <td class="px-4 py-4 text-sm text-slate-800">{{ $log->user?->name ?? 'Unknown' }}</td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ $log->project?->name ?? '-' }}</td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ str_replace('_', ' ', $log->action) }}</td>
                            <td class="px-4 py-4 text-sm font-semibold text-slate-900">{{ number_format($log->units) }}</td>
                            <td class="px-4 py-4 text-xs text-slate-500">{{ $log->created_at?->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">No usage logged yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
