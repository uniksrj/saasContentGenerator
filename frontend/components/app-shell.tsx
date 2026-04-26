'use client'

import Link from 'next/link'
import { usePathname, useRouter } from 'next/navigation'
import { useEffect, useState } from 'react'
import {
  FileText,
  FolderKanban,
  LayoutDashboard,
  LogOut,
  Sparkles,
  UserCircle2,
} from 'lucide-react'

import { api } from '@/lib/api'
import { clearAuthSession, getAuthToken, getStoredUser } from '@/lib/auth'
import type { ApiUser } from '@/lib/types'

const navigation = [
  { href: '/dashboard', label: 'Dashboard', icon: LayoutDashboard },
  { href: '/projects', label: 'Projects', icon: FolderKanban },
  { href: '/topics', label: 'Topics', icon: Sparkles },
  { href: '/articles', label: 'Articles', icon: FileText },
  { href: '/profile', label: 'Profile', icon: UserCircle2 },
]

function isActive(pathname: string, href: string) {
  if (href === '/dashboard') {
    return pathname === href
  }

  return pathname === href || pathname.startsWith(`${href}/`)
}

export function AppShell({ children }: { children: React.ReactNode }) {
  const pathname = usePathname()
  const router = useRouter()
  const [user, setUser] = useState<ApiUser | null>(null)
  const [isLoggingOut, setIsLoggingOut] = useState(false)

  useEffect(() => {
    setUser(getStoredUser())
  }, [pathname])

  async function handleLogout() {
    setIsLoggingOut(true)

    try {
      if (getAuthToken()) {
        await api.logout()
      }
    } catch {
      // We still clear local auth even if the API token is already invalid.
    } finally {
      clearAuthSession()
      router.replace('/login')
      router.refresh()
      setIsLoggingOut(false)
    }
  }

  const initials = user?.name
    ?.split(' ')
    .map((part) => part[0])
    .join('')
    .slice(0, 2)
    .toUpperCase() ?? 'U'

  return (
    <div className="min-h-screen bg-slate-950 text-slate-50 lg:flex">
      <aside className="border-b border-slate-800 px-6 py-6 backdrop-blur lg:w-72 lg:border-b-0 lg:border-r">
        <div className="mb-8 flex items-center justify-between lg:block">
          <Link href="/dashboard" className="inline-flex items-center gap-3">
            <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-cyan-400/15 text-cyan-300">
              <Sparkles className="h-5 w-5" />
            </div>
            <div>
              <p className="text-lg font-semibold">ContentHub</p>
              {/* <p className="text-sm text-slate-400">Laravel + Next SaaS</p> */}
            </div>
          </Link>
        </div>

        <nav className="grid gap-2 sm:grid-cols-2 lg:grid-cols-1">
          {navigation.map((item) => {
            const Icon = item.icon
            const active = isActive(pathname, item.href)

            return (
              <Link
                key={item.href}
                href={item.href}
                className={`flex items-center gap-3 rounded-2xl px-4 py-3 text-sm transition ${
                  active
                    ? 'bg-cyan-400/15 text-cyan-200'
                    : 'text-slate-300 hover:bg-slate-900 hover:text-white'
                }`}
              >
                <Icon className="h-4 w-4" />
                {item.label}
              </Link>
            )
          })}
        </nav>

        <div className="mt-8 rounded-3xl border border-slate-800 bg-slate-900/80 p-4">
          <p className="text-sm text-slate-400">Signed in as</p>
          <p className="mt-1 font-medium">{user?.name ?? 'Authenticated user'}</p>
          <p className="text-sm text-slate-400">{user?.email ?? 'Loading account...'}</p>
        </div>
      </aside>

      <div className="flex min-h-screen flex-1 flex-col">
        <header className="border-b border-slate-800 bg-slate-950/90 px-6 py-5 backdrop-blur">
          <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
              <p className="text-sm uppercase tracking-[0.25em] text-cyan-300">Connected SaaS</p>
              <h1 className="text-2xl font-semibold text-white">API-driven workspace</h1>
            </div>

            <div className="flex items-center gap-3">
              <div className="flex items-center gap-3 rounded-2xl border border-slate-800 bg-slate-900 px-4 py-2">
                <div className="flex h-10 w-10 items-center justify-center rounded-full bg-cyan-400/15 text-sm font-semibold text-cyan-200">
                  {initials}
                </div>
                <div>
                  <p className="text-sm font-medium">{user?.name ?? 'Loading...'}</p>
                  <p className="text-xs text-slate-400">{user?.role ?? 'member'}</p>
                </div>
              </div>

              <button
                type="button"
                onClick={handleLogout}
                disabled={isLoggingOut}
                className="inline-flex items-center gap-2 rounded-2xl border border-slate-800 bg-slate-900 px-4 py-3 text-sm text-slate-200 transition hover:border-slate-700 hover:bg-slate-800 disabled:opacity-60"
              >
                <LogOut className="h-4 w-4" />
                {isLoggingOut ? 'Signing out...' : 'Sign out'}
              </button>
            </div>
          </div>
        </header>

        <main className="flex-1 bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.1),_transparent_35%),linear-gradient(180deg,_#020617_0%,_#0f172a_100%)] px-6 py-6">
          {children}
        </main>
      </div>
    </div>
  )
}
