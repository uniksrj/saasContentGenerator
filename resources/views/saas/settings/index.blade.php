@extends('saas.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="max-w-5xl space-y-8">
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-2xl font-semibold text-slate-900">Profile, Billing & Usage</h2>
        <p class="mt-2 text-sm text-slate-500">Manage your account details, subscription state, and monthly limits in one place.</p>
    </section>

    <section class="grid gap-6 lg:grid-cols-[1fr_1fr]">
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Account Details</h3>
            <form action="{{ route('saas.settings.profile') }}" method="POST" class="mt-5 space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Name</label>
                    <input name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-700 focus:outline-none">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
                    <input name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-700 focus:outline-none">
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Role</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ ucfirst($user->role) }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Current project</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ $user->currentProject?->name ?? 'Not selected' }}</p>
                    </div>
                </div>
                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">Save Profile</button>
            </form>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Usage Snapshot</h3>
            <div class="mt-5 space-y-4">
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Articles this month</span>
                        <span class="font-semibold text-slate-900">{{ $usageThisMonth }} / {{ $usageLimit }}</span>
                    </div>
                    <div class="mt-3 h-3 rounded-full bg-slate-200">
                        <div class="h-3 rounded-full bg-slate-900" style="width: {{ $usageLimit > 0 ? min(100, round(($usageThisMonth / $usageLimit) * 100)) : 0 }}%"></div>
                    </div>
                </div>
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Tokens this month</span>
                        <span class="font-semibold text-slate-900">{{ number_format($tokenUsageThisMonth) }} / {{ number_format($tokenUsageLimit) }}</span>
                    </div>
                    <div class="mt-3 h-3 rounded-full bg-slate-200">
                        <div class="h-3 rounded-full bg-cyan-500" style="width: {{ $tokenUsageLimit > 0 ? min(100, round(($tokenUsageThisMonth / $tokenUsageLimit) * 100)) : 0 }}%"></div>
                    </div>
                </div>
                <div class="rounded-xl border {{ $hasActiveSubscription ? 'border-emerald-200 bg-emerald-50' : 'border-amber-200 bg-amber-50' }} p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide {{ $hasActiveSubscription ? 'text-emerald-600' : 'text-amber-600' }}">Subscription</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ ucfirst($user->subscription_status ?? 'inactive') }}</p>
                    <p class="mt-1 text-sm text-slate-600">
                        {{ $generationSnapshot['message'] }}
                    </p>
                    @if($user->current_period_ends_at)
                        <p class="mt-2 text-xs text-slate-500">Current period ends {{ $user->current_period_ends_at->format('d M Y') }}</p>
                    @endif
                </div>
            </div>
        </article>
    </section>

    <section class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach($plans as $plan)
            <article class="rounded-2xl border {{ $user->plan_id === $plan->id ? 'border-slate-900 bg-slate-50' : 'border-slate-200 bg-white' }} p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ strtoupper($plan->slug) }}</p>
                <h3 class="mt-2 text-xl font-semibold text-slate-900">{{ $plan->name }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ $plan->monthly_article_limit }} articles / month</p>
                <p class="mt-1 text-sm text-slate-500">{{ number_format($plan->monthly_token_limit) }} tokens / month</p>
                @if($plan->description)
                    <p class="mt-3 text-sm text-slate-500">{{ $plan->description }}</p>
                @endif
                <p class="mt-3 text-2xl font-semibold text-slate-900">${{ number_format($plan->price_cents / 100, 2) }}</p>

                @if(!empty($plan->features))
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        @foreach($plan->features as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                @endif

                <form action="{{ route('saas.settings.subscription') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <button type="submit" class="w-full rounded-xl px-4 py-2.5 text-sm font-semibold {{ $user->plan_id === $plan->id && $hasActiveSubscription ? 'bg-slate-900 text-white' : 'border border-slate-300 text-slate-700 hover:border-slate-900 hover:text-slate-900' }}">
                        {{ $user->plan_id === $plan->id && $hasActiveSubscription ? 'Current Subscription' : ((int) $plan->price_cents === 0 ? 'Activate Plan' : 'Subscribe With Stripe') }}
                    </button>
                </form>
            </article>
        @endforeach
    </section>

    @if($hasActiveSubscription)
        <section class="rounded-2xl border border-rose-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Cancel Subscription</h3>
                    <p class="mt-1 text-sm text-slate-500">Cancel the active subscription and stop future generation access after the period ends.</p>
                </div>
                <form action="{{ route('saas.settings.subscription.cancel') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-xl border border-rose-300 px-4 py-2.5 text-sm font-semibold text-rose-700 hover:bg-rose-50">Cancel Subscription</button>
                </form>
            </div>
        </section>
    @endif
</div>
@endsection
