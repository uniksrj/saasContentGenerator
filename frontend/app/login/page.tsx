'use client'

import Link from 'next/link'
import { useRouter, useSearchParams } from 'next/navigation'
import { useState } from 'react'

import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { api, getApiErrorMessage } from '@/lib/api'
import { persistAuthSession } from '@/lib/auth'

export default function LoginPage() {
  const router = useRouter()
  const searchParams = useSearchParams()
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [submitting, setSubmitting] = useState(false)
  const [error, setError] = useState<string | null>(null)

  async function handleSubmit(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault()

    try {
      setSubmitting(true)
      setError(null)

      const response = await api.login(email, password)
      persistAuthSession(response.token, response.data.user)

      router.replace(searchParams.get('redirect') || '/dashboard')
      router.refresh()
    } catch (caughtError) {
      setError(getApiErrorMessage(caughtError))
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <div className="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(34,211,238,0.12),_transparent_30%),linear-gradient(180deg,_#020617_0%,_#0f172a_100%)] px-4 py-12 text-slate-50">
      <div className="mx-auto flex min-h-[calc(100vh-6rem)] max-w-5xl items-center">
        <div className="grid w-full gap-10 lg:grid-cols-[1.05fr,0.95fr]">
          <div className="flex flex-col justify-center">
            <p className="text-sm uppercase tracking-[0.3em] text-cyan-300">Production flow</p>
            <h1 className="mt-4 text-5xl font-semibold leading-tight">
              Sign in to the Next.js dashboard using your Laravel API.
            </h1>
            <p className="mt-6 max-w-xl text-lg text-slate-300">
              This login form sends credentials to `/api/login`, stores the Sanctum token, and
              unlocks the protected SaaS routes.
            </p>
          </div>

          <div className="rounded-[2rem] border border-slate-800 bg-slate-900/80 p-8 shadow-2xl shadow-slate-950/40">
            <div className="mb-8">
              <Link href="/" className="text-2xl font-semibold text-white">
                ContentHub
              </Link>
              <p className="mt-2 text-slate-400">Sign in to your account</p>
            </div>

            <form onSubmit={handleSubmit} className="space-y-5">
              <div>
                <label className="mb-2 block text-sm font-medium text-slate-200">Email</label>
                <Input
                  type="email"
                  value={email}
                  onChange={(event) => setEmail(event.target.value)}
                  placeholder="you@example.com"
                  required
                  className="border-slate-700 bg-slate-950 text-slate-50"
                />
              </div>

              <div>
                <label className="mb-2 block text-sm font-medium text-slate-200">Password</label>
                <Input
                  type="password"
                  value={password}
                  onChange={(event) => setPassword(event.target.value)}
                  placeholder="Enter your password"
                  required
                  className="border-slate-700 bg-slate-950 text-slate-50"
                />
              </div>

              {error ? (
                <div className="rounded-2xl border border-rose-500/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
                  {error}
                </div>
              ) : null}

              <Button type="submit" className="w-full" size="lg" disabled={submitting}>
                {submitting ? 'Signing in...' : 'Sign In'}
              </Button>
            </form>

            <p className="mt-6 text-sm text-slate-400">
              Don&apos;t have an account?{' '}
              <Link href="/signup" className="text-cyan-300 hover:text-cyan-200">
                Register here
              </Link>
            </p>
          </div>
        </div>
      </div>
    </div>
  )
}
