<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register | {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100">
    <div class="mx-auto flex min-h-screen max-w-6xl items-center px-4 py-10">
        <div class="grid w-full gap-6 lg:grid-cols-2">
            <section class="rounded-2xl bg-white p-8 shadow-sm">
                <h2 class="text-2xl font-semibold text-slate-900">Create account</h2>
                <p class="mt-1 text-sm text-slate-500">Start your multi-project content SaaS workspace.</p>
                <form action="{{ route('saas.register.submit') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-700 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-700 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Password</label>
                        <input type="password" name="password" required class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-700 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" required class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-700 focus:outline-none">
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">Create account</button>
                </form>
                <p class="mt-4 text-sm text-slate-500">Already registered? <a href="{{ route('saas.login') }}" class="font-semibold text-slate-900">Login</a></p>
            </section>
            <section class="rounded-2xl bg-slate-900 p-8 text-white shadow-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-300">Includes</p>
                <ul class="mt-4 space-y-3 text-sm text-slate-200">
                    <li>Project-level article pipelines</li>
                    <li>Topic scoring and prioritization</li>
                    <li>Draft and publishing workflow</li>
                    <li>Stripe-backed subscriptions with monthly limits</li>
                </ul>
            </section>
        </div>
    </div>
</body>
</html>
