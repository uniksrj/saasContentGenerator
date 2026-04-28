'use client'

import Link from 'next/link'
import { useEffect, useState } from 'react'
import {
  Activity,
  ArrowRight,
  BadgeCheck,
  Briefcase,
  FileText,
  FolderKanban,
  ShieldCheck,
  Sparkles,
} from 'lucide-react'

import { AppShell } from '@/components/app-shell'
import { api, getApiErrorMessage } from '@/lib/api'
import { setStoredUser } from '@/lib/auth'
import type { ProfileData } from '@/lib/types'

function formatStatus(status?: string | null) {
  if (!status) {
    return 'Inactive'
  }

  return status
    .split('_')
    .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
    .join(' ')
}

function formatLimit(value: number) {
  if (value < 0) {
    return 'Unlimited'
  }

  return value.toLocaleString()
}

function formatPlanPrice(priceCents: number, currency: string) {
  if (priceCents === 0) {
    return 'Free'
  }

  try {
    return new Intl.NumberFormat('en-IN', {
      style: 'currency',
      currency,
    }).format(priceCents / 100)
  } catch {
    return `${(priceCents / 100).toFixed(2)} ${currency}`
  }
}

export default function ProfilePage() {
  const [profile, setProfile] = useState<ProfileData | null>(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)

  useEffect(() => {
    let active = true

    async function loadProfile() {
      try {
        setLoading(true)
        setError(null)

        const response = await api.profile()

        if (!active) {
          return
        }

        setStoredUser(response.data.user)
        setProfile(response.data)
      } catch (caughtError) {
        if (active) {
          setError(getApiErrorMessage(caughtError))
        }
      } finally {
        if (active) {
          setLoading(false)
        }
      }
    }

    loadProfile()

    return () => {
      active = false
    }
  }, [])

  const user = profile?.user ?? null
  const currentPlan = profile?.plan ?? null
  const usage = profile?.usage ?? null
  const counts = profile?.counts ?? null
  const currentProject = profile?.current_project ?? null

  if (loading) {
    return (
      <AppShell>
        <div className="rounded-[2rem] border border-slate-800 bg-slate-900/70 p-8 text-slate-200">
          Loading your profile...
        </div>
      </AppShell>
    )
  }

  return (
    <AppShell>
      <div className="space-y-6">
        <section className="relative overflow-hidden rounded-[2rem] border border-cyan-300/20 bg-[radial-gradient(circle_at_top_left,_rgba(103,232,249,0.22),_transparent_30%),linear-gradient(135deg,_rgba(15,23,42,0.98),_rgba(8,47,73,0.92)_55%,_rgba(15,23,42,1))] p-8 shadow-[0_30px_80px_-40px_rgba(34,211,238,0.45)]">
          <div className="absolute inset-y-0 right-0 hidden w-1/3 bg-[linear-gradient(180deg,rgba(103,232,249,0.08),transparent)] lg:block" />
          <div className="relative flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
            <div className="max-w-2xl">
              <p className="text-sm uppercase tracking-[0.3em] text-cyan-200/90">Profile</p>
              <h2 className="mt-3 text-3xl font-semibold tracking-tight text-white sm:text-4xl">
                {user?.name ?? 'Your workspace profile'}
              </h2>
              <p className="mt-3 max-w-xl text-sm leading-6 text-slate-300 sm:text-base">
                Review your subscription, monitor usage, and keep your workspace details organized from one polished dashboard.
              </p>
            </div>

            <div className="grid gap-3 sm:grid-cols-2">
              <div className="rounded-3xl border border-white/10 bg-white/[0.08] px-5 py-4 backdrop-blur">
                <p className="text-xs uppercase tracking-[0.22em] text-cyan-100/80">Current plan</p>
                <p className="mt-2 text-xl font-semibold text-white">
                  {currentPlan?.name ?? 'Free'}
                </p>
                <p className="mt-1 text-sm text-slate-300">
                  {currentPlan
                    ? `${formatPlanPrice(currentPlan.price_cents, currentPlan.currency)} / ${currentPlan.billing_interval}`
                    : 'No paid subscription yet'}
                </p>
              </div>
              <div className="rounded-3xl border border-white/10 bg-slate-950/40 px-5 py-4 backdrop-blur">
                <p className="text-xs uppercase tracking-[0.22em] text-cyan-100/80">Subscription</p>
                <p className="mt-2 text-xl font-semibold text-white">
                  {formatStatus(user?.subscription_status)}
                </p>
                <p className="mt-1 text-sm text-slate-300">
                  Secure account access and billing history from your dashboard.
                </p>
              </div>
            </div>
          </div>
        </section>

        {error ? (
          <div className="rounded-2xl border border-rose-500/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
            {error}
          </div>
        ) : null}

        <section className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
          <div className="rounded-3xl border border-slate-800 bg-slate-900/75 p-6 shadow-lg shadow-slate-950/20">
            <div className="flex items-center justify-between">
              <p className="text-sm text-slate-400">Account</p>
              <ShieldCheck className="h-5 w-5 text-cyan-300" />
            </div>
            <p className="mt-4 text-2xl font-semibold text-white">{user?.name ?? 'User'}</p>
            <p className="mt-1 text-sm text-slate-400">{user?.email ?? 'No email found'}</p>
          </div>

          <div className="rounded-3xl border border-slate-800 bg-slate-900/75 p-6 shadow-lg shadow-slate-950/20">
            <div className="flex items-center justify-between">
              <p className="text-sm text-slate-400">Role</p>
              <BadgeCheck className="h-5 w-5 text-emerald-300" />
            </div>
            <p className="mt-4 text-2xl font-semibold capitalize text-white">{user?.role ?? 'member'}</p>
            <p className="mt-1 text-sm text-slate-400">Workspace permissions and access level.</p>
          </div>

          <div className="rounded-3xl border border-slate-800 bg-slate-900/75 p-6 shadow-lg shadow-slate-950/20">
            <div className="flex items-center justify-between">
              <p className="text-sm text-slate-400">Projects</p>
              <FolderKanban className="h-5 w-5 text-amber-300" />
            </div>
            <p className="mt-4 text-2xl font-semibold text-white">{counts?.projects ?? 0}</p>
            <p className="mt-1 text-sm text-slate-400">Active workspaces connected to your account.</p>
          </div>

          <div className="rounded-3xl border border-slate-800 bg-slate-900/75 p-6 shadow-lg shadow-slate-950/20">
            <div className="flex items-center justify-between">
              <p className="text-sm text-slate-400">Articles</p>
              <FileText className="h-5 w-5 text-violet-300" />
            </div>
            <p className="mt-4 text-2xl font-semibold text-white">{counts?.articles ?? 0}</p>
            <p className="mt-1 text-sm text-slate-400">Published and generated content across projects.</p>
          </div>
        </section>

        <section className="grid gap-6 xl:grid-cols-[1.2fr,0.8fr]">
          <div className="rounded-[1.75rem] border border-slate-800 bg-slate-900/75 p-6">
            <div className="flex items-center justify-between gap-4">
              <div>
                <p className="text-sm uppercase tracking-[0.22em] text-cyan-300">Usage overview</p>
                <h3 className="mt-2 text-2xl font-semibold text-white">Plan limits and workspace activity</h3>
              </div>
              <div className="rounded-2xl border border-cyan-400/20 bg-cyan-400/10 p-3 text-cyan-200">
                <Activity className="h-5 w-5" />
              </div>
            </div>

            <div className="mt-6 grid gap-4 md:grid-cols-2">
              <div className="rounded-3xl border border-slate-800 bg-slate-950/70 p-5">
                <p className="text-sm text-slate-400">Remaining articles</p>
                <p className="mt-3 text-3xl font-semibold text-white">
                  {usage ? formatLimit(usage.remaining_articles) : '0'}
                </p>
                <p className="mt-2 text-sm text-slate-500">
                  Monthly article capacity available for new content generation.
                </p>
              </div>

              <div className="rounded-3xl border border-slate-800 bg-slate-950/70 p-5">
                <p className="text-sm text-slate-400">Remaining tokens</p>
                <p className="mt-3 text-3xl font-semibold text-white">
                  {usage ? formatLimit(usage.remaining_tokens) : '0'}
                </p>
                <p className="mt-2 text-sm text-slate-500">
                  Available generation tokens before the next billing reset.
                </p>
              </div>

              <div className="rounded-3xl border border-slate-800 bg-slate-950/70 p-5">
                <p className="text-sm text-slate-400">Topics tracked</p>
                <p className="mt-3 text-3xl font-semibold text-white">{counts?.topics ?? 0}</p>
                <p className="mt-2 text-sm text-slate-500">
                  Topics currently feeding your editorial workflow.
                </p>
              </div>

              <div className="rounded-3xl border border-slate-800 bg-slate-950/70 p-5">
                <p className="text-sm text-slate-400">Generation status</p>
                <p className="mt-3 text-2xl font-semibold text-white">
                  {usage?.can_generate ? 'Ready to generate' : 'Limit reached'}
                </p>
                <p className="mt-2 text-sm text-slate-500">
                  {usage?.message ?? 'Usage information will appear here when available.'}
                </p>
              </div>
            </div>
          </div>

          <div className="space-y-6">
            <div className="rounded-[1.75rem] border border-slate-800 bg-slate-900/75 p-6">
              <p className="text-sm uppercase tracking-[0.22em] text-cyan-300">Current workspace</p>
              <h3 className="mt-2 text-2xl font-semibold text-white">
                {currentProject?.name ?? 'No project selected'}
              </h3>
              <p className="mt-3 text-sm leading-6 text-slate-400">
                {currentProject?.description ?? 'Choose a project to keep your profile connected to the content pipeline you are actively working on.'}
              </p>

              <div className="mt-6 rounded-3xl border border-slate-800 bg-slate-950/70 p-5">
                <div className="flex items-center gap-3">
                  <Briefcase className="h-5 w-5 text-cyan-300" />
                  <div>
                    <p className="text-sm text-slate-400">Project status</p>
                    <p className="font-medium text-white capitalize">{currentProject?.status ?? 'Not set'}</p>
                  </div>
                </div>
              </div>
            </div>

            <div className="rounded-[1.75rem] border border-slate-800 bg-[linear-gradient(180deg,rgba(15,23,42,0.98),rgba(12,74,110,0.88))] p-6">
              <div className="flex items-center gap-3">
                <Sparkles className="h-5 w-5 text-cyan-200" />
                <p className="text-sm uppercase tracking-[0.22em] text-cyan-200">Next step</p>
              </div>
              <h3 className="mt-3 text-2xl font-semibold text-white">Upgrade or manage billing</h3>
              <p className="mt-3 text-sm leading-6 text-slate-300">
                If you need higher limits or want to review plan options, head to the billing flow built for subscriptions and payment setup.
              </p>
              <Link
                href="/dashboard/billing"
                className="mt-6 inline-flex items-center gap-2 rounded-2xl bg-cyan-300 px-4 py-3 text-sm font-medium text-slate-950 transition hover:bg-cyan-200"
              >
                Open billing
                <ArrowRight className="h-4 w-4" />
              </Link>
            </div>
          </div>
        </section>
      </div>
    </AppShell>
  )
}
