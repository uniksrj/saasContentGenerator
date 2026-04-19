'use client'

import { useEffect, useState } from 'react'

import { AppShell } from '@/components/app-shell'
import { Card } from '@/components/ui/card'
import { api, getApiErrorMessage } from '@/lib/api'
import { setStoredUser } from '@/lib/auth'
import type { ProfileData } from '@/lib/types'

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

  return (
    <AppShell>
      <div className="space-y-6">
        <div>
          <p className="text-sm uppercase tracking-[0.25em] text-cyan-300">Profile</p>
          <h2 className="text-3xl font-semibold text-white">Account and plan details</h2>
          <p className="mt-2 text-slate-400">
            This page is fully powered by Laravel `/api/profile`.
          </p>
        </div>

        {error ? (
          <div className="rounded-2xl border border-rose-500/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
            {error}
          </div>
        ) : null}

        {loading ? (
          <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-8 text-slate-200">
            Loading profile...
          </Card>
        ) : null}

        {profile ? (
          <div className="grid gap-6 lg:grid-cols-[1fr,1fr]">
            <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
              <h3 className="text-xl font-semibold text-white">User</h3>
              <div className="mt-6 space-y-4 text-sm text-slate-300">
                <div>
                  <p className="text-slate-400">Name</p>
                  <p className="mt-1 text-lg text-white">{profile.user.name}</p>
                </div>
                <div>
                  <p className="text-slate-400">Email</p>
                  <p className="mt-1 text-lg text-white">{profile.user.email}</p>
                </div>
                <div>
                  <p className="text-slate-400">Role</p>
                  <p className="mt-1 text-lg capitalize text-white">{profile.user.role}</p>
                </div>
                <div>
                  <p className="text-slate-400">Subscription</p>
                  <p className="mt-1 text-lg capitalize text-white">
                    {profile.user.subscription_status ?? 'inactive'}
                  </p>
                </div>
              </div>
            </Card>

            <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
              <h3 className="text-xl font-semibold text-white">Plan and usage</h3>
              <div className="mt-6 space-y-4 text-sm text-slate-300">
                <div>
                  <p className="text-slate-400">Plan</p>
                  <p className="mt-1 text-lg text-white">{profile.plan?.name ?? 'Starter'}</p>
                </div>
                <div>
                  <p className="text-slate-400">Article credits left</p>
                  <p className="mt-1 text-lg text-white">{profile.usage.remaining_articles}</p>
                </div>
                <div>
                  <p className="text-slate-400">Token credits left</p>
                  <p className="mt-1 text-lg text-white">{profile.usage.remaining_tokens}</p>
                </div>
                <div>
                  <p className="text-slate-400">Current project</p>
                  <p className="mt-1 text-lg text-white">
                    {profile.current_project?.name ?? 'No active project'}
                  </p>
                </div>
              </div>
            </Card>
          </div>
        ) : null}
      </div>
    </AppShell>
  )
}
