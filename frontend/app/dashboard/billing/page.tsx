'use client'

import { useEffect, useRef, useState } from 'react'

import { Button } from '@/components/ui/button'
import { Card } from '@/components/ui/card'
import { api, getApiErrorMessage } from '@/lib/api'
import { setStoredUser } from '@/lib/auth'
import type { ApiUser, BillingOverview, BillingPlan } from '@/lib/types'

declare global {
  interface Window {
    Stripe?: (publishableKey: string) => any
  }
}

const stripeScriptUrl = 'https://js.stripe.com/v3/'

function formatPrice(plan: BillingPlan) {
  if (plan.price_cents === 0) {
    return 'Free'
  }

  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: plan.currency,
    minimumFractionDigits: 2,
  }).format(plan.price_cents / 100)
}

function loadStripeScript() {
  if (typeof window === 'undefined') {
    return Promise.resolve()
  }

  if (window.Stripe) {
    return Promise.resolve()
  }

  return new Promise<void>((resolve, reject) => {
    const existingScript = document.querySelector<HTMLScriptElement>(`script[src="${stripeScriptUrl}"]`)
    if (existingScript) {
      existingScript.addEventListener('load', () => resolve(), { once: true })
      existingScript.addEventListener('error', () => reject(new Error('Stripe.js failed to load.')), { once: true })
      return
    }

    const script = document.createElement('script')
    script.src = stripeScriptUrl
    script.async = true
    script.onload = () => resolve()
    script.onerror = () => reject(new Error('Stripe.js failed to load.'))
    document.head.appendChild(script)
  })
}

export default function BillingPage() {
  const paymentElementRef = useRef<HTMLDivElement | null>(null)
  const stripeInstanceRef = useRef<any>(null)
  const elementsRef = useRef<any>(null)

  const [profileUser, setProfileUser] = useState<ApiUser | null>(null)
  const [billing, setBilling] = useState<BillingOverview | null>(null)
  const [selectedPlanId, setSelectedPlanId] = useState<number | null>(null)
  const [paymentMethod, setPaymentMethod] = useState<'card' | 'upi'>('card')
  const [clientSecret, setClientSecret] = useState<string | null>(null)
  const [subscriptionId, setSubscriptionId] = useState<string | null>(null)
  const [loading, setLoading] = useState(true)
  const [submitting, setSubmitting] = useState(false)
  const [message, setMessage] = useState<string | null>(null)
  const [error, setError] = useState<string | null>(null)

  const selectedPlan = billing?.plans.find((plan) => plan.id === selectedPlanId) ?? null
  const currentPlan = billing?.current_plan ?? null
  const selectedIsCurrent = selectedPlan?.is_current ?? false
  const canUseUpi =
    selectedPlan?.supports_upi ?? false

  useEffect(() => {
    let active = true

    async function loadBilling() {
      try {
        setLoading(true)
        setError(null)

        const [profileResponse, billingResponse] = await Promise.all([
          api.profile(),
          api.getBillingOverview(),
        ])

        if (!active) {
          return
        }

        setProfileUser(profileResponse.data.user)
        setStoredUser(profileResponse.data.user)
        setBilling(billingResponse.data)

        const initialPlanId =
          billingResponse.data.current_plan_id ??
          billingResponse.data.plans.find((plan) => !plan.is_current)?.id ??
          billingResponse.data.plans[0]?.id ??
          null

        setSelectedPlanId(initialPlanId)
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

    loadBilling()

    return () => {
      active = false
    }
  }, [])

  useEffect(() => {
    if (!clientSecret || !selectedPlan || paymentMethod !== 'card') {
      return
    }

    let cancelled = false

    async function mountPaymentElement() {
      try {
        await loadStripeScript()

        if (cancelled || !window.Stripe || !billing?.stripe_publishable_key || !paymentElementRef.current) {
          return
        }

        paymentElementRef.current.innerHTML = ''
        const stripe = window.Stripe(billing.stripe_publishable_key)
        const elements = stripe.elements({
          clientSecret,
          appearance: {
            theme: 'night',
            variables: {
              colorPrimary: '#06b6d4',
              colorBackground: '#020617',
              colorText: '#e2e8f0',
              colorDanger: '#fb7185',
              fontFamily:
                'Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
              borderRadius: '14px',
            },
          },
        })

        const paymentElement = elements.create('payment', {
          layout: {
            type: 'tabs',
            defaultCollapsed: false,
          },
        })

        paymentElement.mount(paymentElementRef.current)
        stripeInstanceRef.current = stripe
        elementsRef.current = elements
      } catch (caughtError) {
        if (!cancelled) {
          setError(getApiErrorMessage(caughtError))
        }
      }
    }

    mountPaymentElement()

    return () => {
      cancelled = true
      stripeInstanceRef.current = null
      elementsRef.current = null
      if (paymentElementRef.current) {
        paymentElementRef.current.innerHTML = ''
      }
    }
  }, [billing?.stripe_publishable_key, clientSecret, paymentMethod, selectedPlan])

  useEffect(() => {
    setClientSecret(null)
    setSubscriptionId(null)
    if (paymentElementRef.current) {
      paymentElementRef.current.innerHTML = ''
    }
  }, [selectedPlanId, paymentMethod])

  async function refreshBilling() {
    const [profileResponse, billingResponse] = await Promise.all([
      api.profile(),
      api.getBillingOverview(),
    ])

    setProfileUser(profileResponse.data.user)
    setStoredUser(profileResponse.data.user)
    setBilling(billingResponse.data)
    setSelectedPlanId((current) => current ?? billingResponse.data.current_plan_id ?? billingResponse.data.plans[0]?.id ?? null)
  }

  async function handleContinueToPayment() {
    if (!selectedPlan) {
      return
    }

    if (paymentMethod === 'upi' && !canUseUpi) {
      setError('UPI is not available for recurring Stripe subscriptions in this setup. Please use card.')
      return
    }

    try {
      setSubmitting(true)
      setMessage(null)
      setError(null)
      setClientSecret(null)
      setSubscriptionId(null)

      const response = await api.subscribeToPlan({
        planId: selectedPlan.id,
        paymentMethod,
      })

      if (response.mode === 'local') {
        await refreshBilling()
        setMessage(response.message)
        return
      }

      if (!response.client_secret) {
        throw new Error('Stripe did not return a payment client secret.')
      }

      setClientSecret(response.client_secret)
      setSubscriptionId(response.subscription_id ?? null)
      setMessage(response.message)
    } catch (caughtError) {
      setError(getApiErrorMessage(caughtError))
    } finally {
      setSubmitting(false)
    }
  }

  async function handleSubmitPayment() {
    const stripe = stripeInstanceRef.current
    const elements = elementsRef.current

    if (!stripe || !elements || !selectedPlan) {
      setError('Payment form is not ready yet.')
      return
    }

    try {
      setSubmitting(true)
      setError(null)
      setMessage(null)

      const { error: confirmError, paymentIntent } = await stripe.confirmPayment({
        elements,
        confirmParams: {
          return_url: `${window.location.origin}/dashboard/billing`,
        },
        redirect: 'if_required',
      })

      if (confirmError) {
        throw new Error(confirmError.message || 'Stripe could not confirm the payment.')
      }

      if (paymentIntent?.status !== 'succeeded' && paymentIntent?.status !== 'processing') {
        throw new Error('The payment has not completed yet.')
      }

      if (subscriptionId) {
        await api.syncSubscription(subscriptionId)
      } else if (billing?.current_plan_id !== selectedPlan.id) {
        await refreshBilling()
      }

      setMessage('Subscription activated successfully.')
      await refreshBilling()
      setClientSecret(null)
      setSubscriptionId(null)
    } catch (caughtError) {
      setError(getApiErrorMessage(caughtError))
    } finally {
      setSubmitting(false)
    }
  }

  if (loading) {
    return (
      <div className="rounded-3xl border border-slate-800 bg-slate-900/70 p-8 text-slate-200">
        Loading billing options...
      </div>
    )
  }

  return (
    <div className="space-y-6">
        <section className="rounded-[2rem] border border-cyan-400/20 bg-gradient-to-r from-cyan-400/12 via-slate-900 to-slate-900 p-8">
          <p className="text-sm uppercase tracking-[0.25em] text-cyan-300">Billing</p>
          <h2 className="mt-3 text-3xl font-semibold text-white">Choose a plan and pay in one flow</h2>
          <p className="mt-3 max-w-2xl text-slate-300">
            Pick a subscription plan, then complete the payment with Stripe card details directly on this page.
            UPI is displayed for transparency, but Stripe does not support recurring UPI billing in this setup.
          </p>
        </section>

        <section className="grid gap-4 md:grid-cols-3">
          <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <p className="text-sm text-slate-400">Current plan</p>
            <p className="mt-3 text-2xl font-semibold text-white">{currentPlan?.name ?? 'Free'}</p>
            <p className="mt-2 text-sm text-slate-500">
              {billing?.subscription_status ?? profileUser?.subscription_status ?? 'inactive'}
            </p>
          </Card>
          <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <p className="text-sm text-slate-400">Account</p>
            <p className="mt-3 text-2xl font-semibold text-white">{profileUser?.name ?? 'Account'}</p>
            <p className="mt-2 text-sm text-slate-500">{profileUser?.email ?? 'Signed in user'}</p>
          </Card>
          <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <p className="text-sm text-slate-400">Stripe support</p>
            <p className="mt-3 text-2xl font-semibold text-white">
              {billing?.stripe_publishable_key ? 'Connected' : 'Missing key'}
            </p>
            <p className="mt-2 text-sm text-slate-500">
              Card payments use Stripe Elements. UPI requires a non-recurring flow.
            </p>
          </Card>
        </section>

        <section>
          <h3 className="mb-4 text-2xl font-semibold text-white">Select a plan</h3>
          <div className="grid gap-4 md:grid-cols-3">
            {billing?.plans.map((plan) => {
              const selected = plan.id === selectedPlanId

              return (
                <button
                  key={plan.id}
                  type="button"
                  onClick={() => setSelectedPlanId(plan.id)}
                  className={`rounded-3xl border p-6 text-left transition ${
                    selected
                      ? 'border-cyan-400/60 bg-cyan-400/10'
                      : 'border-slate-800 bg-slate-900/70 hover:border-slate-700 hover:bg-slate-900'
                  }`}
                >
                  <div className="flex items-start justify-between gap-3">
                    <div>
                      <p className="text-xl font-semibold text-white">{plan.name}</p>
                      <p className="mt-1 text-sm text-slate-400">{plan.description || 'No description available.'}</p>
                    </div>
                    {plan.is_current ? (
                      <span className="rounded-full bg-cyan-400/10 px-3 py-1 text-xs uppercase tracking-wide text-cyan-200">
                        Current
                      </span>
                    ) : null}
                  </div>

                  <div className="mt-5">
                    <span className="text-4xl font-semibold tracking-[-0.04em] text-white">
                      {formatPrice(plan)}
                    </span>
                    <span className="ml-2 text-sm text-slate-400">/{plan.billing_interval}</span>
                  </div>

                  <div className="mt-5 space-y-2 text-sm text-slate-300">
                    {plan.features.slice(0, 4).map((feature) => (
                      <p key={feature}>• {feature}</p>
                    ))}
                  </div>

                  <p className="mt-5 text-xs text-slate-500">
                    UPI support: {plan.supports_upi ? 'Possible for INR plans only' : 'Not available for USD pricing'}
                  </p>
                </button>
              )
            })}
          </div>
        </section>

        {selectedPlan ? (
          <section className="grid gap-6 xl:grid-cols-[1.2fr,0.8fr]">
            <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
              <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                  <h3 className="text-xl font-semibold text-white">Payment details</h3>
                  <p className="mt-1 text-sm text-slate-400">
                    After you continue, Stripe will mount the secure payment fields here.
                  </p>
                </div>
                <div className="flex items-center gap-2 rounded-full border border-slate-700 bg-slate-950 px-3 py-1 text-xs uppercase tracking-wide text-slate-300">
                  <span>{selectedPlan.name}</span>
                  <span>•</span>
                  <span>{formatPrice(selectedPlan)}</span>
                </div>
              </div>

              <div className="mt-6 grid gap-3 sm:grid-cols-2">
                <button
                  type="button"
                  onClick={() => setPaymentMethod('card')}
                  className={`rounded-2xl border px-4 py-4 text-left transition ${
                    paymentMethod === 'card'
                      ? 'border-cyan-400/60 bg-cyan-400/10'
                      : 'border-slate-800 bg-slate-950/70 hover:border-slate-700'
                  }`}
                >
                  <p className="font-medium text-white">Card / subscription</p>
                  <p className="mt-1 text-sm text-slate-400">
                    Enter card details and start the recurring subscription.
                  </p>
                </button>
                <button
                  type="button"
                  onClick={() => setPaymentMethod('upi')}
                  className={`rounded-2xl border px-4 py-4 text-left transition ${
                    paymentMethod === 'upi'
                      ? 'border-amber-400/60 bg-amber-400/10'
                      : 'border-slate-800 bg-slate-950/70 hover:border-slate-700'
                  }`}
                >
                  <p className="font-medium text-white">UPI</p>
                  <p className="mt-1 text-sm text-slate-400">
                    Stripe UPI does not support recurring subscriptions. This stays disabled for billing.
                  </p>
                </button>
              </div>

              {paymentMethod === 'upi' ? (
                <div className="mt-6 rounded-2xl border border-amber-400/30 bg-amber-400/10 px-4 py-3 text-sm text-amber-100">
                  UPI is not available for this recurring Stripe plan. Switch back to card to continue.
                </div>
              ) : null}

              {clientSecret && paymentMethod === 'card' ? (
                <div className="mt-6 space-y-4">
                  <div ref={paymentElementRef} className="rounded-2xl border border-slate-800 bg-slate-950/60 p-4" />
                  <Button type="button" onClick={handleSubmitPayment} disabled={submitting}>
                    {submitting ? 'Processing...' : 'Confirm subscription'}
                  </Button>
                </div>
              ) : (
                <div className="mt-6">
                  <Button
                    type="button"
                    onClick={handleContinueToPayment}
                    disabled={submitting || paymentMethod === 'upi' || selectedIsCurrent}
                  >
                    {submitting ? 'Preparing...' : selectedIsCurrent ? 'Current plan' : 'Continue to payment'}
                  </Button>
                </div>
              )}
            </Card>

            <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
              <h3 className="text-xl font-semibold text-white">Plan summary</h3>
              <div className="mt-6 space-y-4 text-sm text-slate-300">
                <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
                  <p className="text-slate-400">Selected plan</p>
                  <p className="mt-1 text-lg font-medium text-white">{selectedPlan.name}</p>
                </div>
                <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
                  <p className="text-slate-400">Billing</p>
                  <p className="mt-1 text-lg font-medium text-white">
                    {formatPrice(selectedPlan)} / {selectedPlan.billing_interval}
                  </p>
                </div>
                <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
                  <p className="text-slate-400">Payment method</p>
                  <p className="mt-1 text-lg font-medium text-white">
                    {paymentMethod === 'card' ? 'Card subscription' : 'UPI unavailable for this plan'}
                  </p>
                </div>
                <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
                  <p className="text-slate-400">Included limits</p>
                  <p className="mt-1 text-lg font-medium text-white">
                    {selectedPlan.monthly_article_limit} articles, {selectedPlan.monthly_token_limit.toLocaleString()} tokens
                  </p>
                </div>
              </div>
            </Card>
          </section>
        ) : null}

        {message ? (
          <div className="rounded-2xl border border-cyan-400/30 bg-cyan-400/10 px-4 py-3 text-sm text-cyan-100">
            {message}
          </div>
        ) : null}

        {error ? (
          <div className="rounded-2xl border border-rose-500/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
            {error}
          </div>
        ) : null}
    </div>
  )
}
