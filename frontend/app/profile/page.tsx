'use client'

import { useEffect, useState } from 'react'

import { AppShell } from '@/components/app-shell'
import { api, getApiErrorMessage } from '@/lib/api'
import { setStoredUser } from '@/lib/auth'
import type { ProfileData } from '@/lib/types'
import { formatPrice } from '@/lib/utils'

export default function ProfilePage() {
  const [profile, setProfile] = useState<ProfileData | null>(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)
  const [selectedPlanId, setSelectedPlanId] = useState<number | null>(null)

  useEffect(() => {
    let active = true

    async function loadProfile() {
      try {
        setLoading(true)
        setError(null)
        const response = await api.profile()
console.log('Profile data:', response.data)
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

  return (
    <AppShell>
      <div className="space-y-10">

        {/* HEADER */}
        <section className="rounded-[2rem] border border-cyan-400/20 bg-gradient-to-r from-cyan-400/10 via-slate-900 to-slate-900 p-10">
          <h2 className="text-4xl font-bold text-white tracking-tight">
            Billing & Subscription
          </h2>
          <p className="mt-3 text-slate-400 max-w-xl">
            Manage your subscription, payment method, and usage limits in one place.
          </p>
        </section>

        {/* TOP CARDS */}
        <section className="grid gap-6 md:grid-cols-3">
          {/* Current Plan */}
          <div className="rounded-3xl border border-slate-800/80 bg-slate-900/60 backdrop-blur-xl p-6 hover:shadow-xl hover:shadow-cyan-500/5 transition">
            <p className="text-sm text-slate-400">Current Plan</p>
            <p className="mt-3 text-2xl font-semibold text-white">
              {profile?.plan?.name ?? 'Free'}
            </p>
            <p className="mt-1 text-xs text-slate-500">
              {profile?.user?.subscription_status ?? 'inactive'}
            </p>
          </div>

          {/* Account */}
          <div className="rounded-3xl border border-slate-800/80 bg-slate-900/60 backdrop-blur-xl p-6 hover:shadow-xl hover:shadow-cyan-500/5 transition">
            <p className="text-sm text-slate-400">Account</p>
            <p className="mt-3 text-2xl font-semibold text-white">
              {profile?.user?.name}
            </p>
            <p className="mt-1 text-xs text-slate-500">
              {profile?.user?.email}
            </p>
          </div>

          {/* Stripe Status */}
          <div className="rounded-3xl border border-slate-800/80 bg-slate-900/60 backdrop-blur-xl p-6 hover:shadow-xl hover:shadow-cyan-500/5 transition">
            <p className="text-sm text-slate-400">Payments</p>
            <p className="mt-3 text-2xl font-semibold text-white">
              {/* {billing?.stripe_publishable_key ? 'Connected' : 'Not configured'} */}
            </p>
            <p className="mt-1 text-xs text-slate-500">
              Secure payments via Stripe
            </p>
          </div>
        </section>

        {/* PLAN SELECTION */}
        <section>
          <h3 className="text-2xl font-semibold text-white mb-6">
            Choose a Plan
          </h3>

          <div className="grid gap-6 md:grid-cols-3">
            {(profile?.plan ? [profile.plan] : []).map((plan) => {
              const selected = plan.id === selectedPlanId

              return (
                <button
                  key={plan.id}
                  onClick={() => setSelectedPlanId(plan.id)}
                  className={`relative rounded-3xl border p-6 text-left transition ${selected
                      ? 'border-cyan-400 bg-cyan-400/10'
                      : 'border-slate-800 bg-slate-900/70 hover:border-slate-700'
                    }`}
                >
                  {plan.is_current && (
                    <span className="absolute top-4 right-4 text-xs bg-cyan-400/20 text-cyan-300 px-3 py-1 rounded-full">
                      Current
                    </span>
                  )}

                  <p className="text-xl font-semibold text-white">{plan.name}</p>
                  <p className="mt-1 text-sm text-slate-400">
                    {plan.description}
                  </p>

                  <div className="mt-6 text-4xl font-bold text-white">
                    {formatPrice(plan)}
                    <span className="text-sm text-slate-400 ml-2">
                      /{plan.billing_interval}
                    </span>
                  </div>

                  <div className="mt-6 space-y-2 text-sm text-slate-300">
                    {plan?.features.slice(0, 4).map((f) => (
                      <p key={f}>• {f}</p>
                    ))}
                  </div>
                </button>
              )
            })}
          </div>
        </section>

        {/* PAYMENT + SUMMARY */}
        {/* {selectedPlan && (
          <section className="grid gap-6 xl:grid-cols-[1.2fr,0.8fr]">

            <div className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
              <h3 className="text-xl font-semibold text-white">
                Payment
              </h3>

              {!clientSecret ? (
                <button
                  onClick={handleContinueToPayment}
                  disabled={submitting || selectedIsCurrent}
                  className="mt-6 w-full rounded-xl bg-cyan-500 hover:bg-cyan-400 text-black font-semibold py-3 transition"
                >
                  {submitting ? 'Preparing...' : 'Continue to Payment'}
                </button>
              ) : (
                <div className="mt-6 space-y-4">
                  <div ref={paymentElementRef} />
                  <button
                    onClick={handleSubmitPayment}
                    className="w-full rounded-xl bg-cyan-500 hover:bg-cyan-400 text-black font-semibold py-3 transition"
                  >
                    {submitting ? 'Processing...' : 'Confirm Subscription'}
                  </button>
                </div>
              )}
            </div>

            <div className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
              <h3 className="text-xl font-semibold text-white">
                Summary
              </h3>

              <div className="mt-6 divide-y divide-slate-800 text-sm">
                <div className="flex justify-between py-3">
                  <span className="text-slate-400">Plan</span>
                  <span className="text-white">{selectedPlan.name}</span>
                </div>

                <div className="flex justify-between py-3">
                  <span className="text-slate-400">Billing</span>
                  <span className="text-white">
                    {formatPrice(selectedPlan)}
                  </span>
                </div>

                <div className="flex justify-between py-3">
                  <span className="text-slate-400">Limits</span>
                  <span className="text-white">
                    {selectedPlan.monthly_article_limit} articles
                  </span>
                </div>
              </div>
            </div>

          </section>
        )} */}

        {/* STATUS */}
        {/* {message && (
          <div className="rounded-xl border border-cyan-400/30 bg-cyan-400/10 p-3 text-sm text-cyan-200">
            {message}
          </div>
        )}

        {error && (
          <div className="rounded-xl border border-red-500/40 bg-red-500/10 p-3 text-sm text-red-200">
            {error}
          </div>
        )} */}
      </div>
    </AppShell>
  )
}
