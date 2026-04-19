'use client'

import { AUTH_TOKEN_KEY, AUTH_USER_KEY } from '@/lib/constants'
import type { ApiUser } from '@/lib/types'

function readCookie(name: string): string | null {
  if (typeof document === 'undefined') {
    return null
  }

  const match = document.cookie
    .split('; ')
    .find((entry) => entry.startsWith(`${name}=`))

  return match ? decodeURIComponent(match.split('=').slice(1).join('=')) : null
}

export function getAuthToken(): string | null {
  if (typeof window === 'undefined') {
    return null
  }

  return window.localStorage.getItem(AUTH_TOKEN_KEY) ?? readCookie(AUTH_TOKEN_KEY)
}

export function getStoredUser(): ApiUser | null {
  if (typeof window === 'undefined') {
    return null
  }

  const rawUser = window.localStorage.getItem(AUTH_USER_KEY)
  if (!rawUser) {
    return null
  }

  try {
    return JSON.parse(rawUser) as ApiUser
  } catch {
    return null
  }
}

export function setStoredUser(user: ApiUser) {
  if (typeof window === 'undefined') {
    return
  }

  window.localStorage.setItem(AUTH_USER_KEY, JSON.stringify(user))
}

export function persistAuthSession(token: string, user: ApiUser) {
  if (typeof window === 'undefined') {
    return
  }

  window.localStorage.setItem(AUTH_TOKEN_KEY, token)
  window.localStorage.setItem(AUTH_USER_KEY, JSON.stringify(user))
  document.cookie = `${AUTH_TOKEN_KEY}=${encodeURIComponent(token)}; path=/; max-age=2592000; samesite=lax`
}

export function clearAuthSession() {
  if (typeof window === 'undefined') {
    return
  }

  window.localStorage.removeItem(AUTH_TOKEN_KEY)
  window.localStorage.removeItem(AUTH_USER_KEY)
  document.cookie = `${AUTH_TOKEN_KEY}=; path=/; max-age=0; samesite=lax`
}
