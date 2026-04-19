import type { NextRequest } from 'next/server'
import { NextResponse } from 'next/server'

import { AUTH_TOKEN_KEY } from './lib/constants'

const protectedPrefixes = ['/dashboard', '/projects', '/topics', '/articles', '/profile']
const guestOnlyPrefixes = ['/login', '/signup']

export function proxy(request: NextRequest) {
  const token = request.cookies.get(AUTH_TOKEN_KEY)?.value
  const { pathname } = request.nextUrl

  const isProtectedRoute = protectedPrefixes.some(
    (prefix) => pathname === prefix || pathname.startsWith(`${prefix}/`),
  )
  const isGuestRoute = guestOnlyPrefixes.some(
    (prefix) => pathname === prefix || pathname.startsWith(`${prefix}/`),
  )

  if (isProtectedRoute && !token) {
    const loginUrl = new URL('/login', request.url)
    loginUrl.searchParams.set('redirect', pathname)
    return NextResponse.redirect(loginUrl)
  }

  if (isGuestRoute && token) {
    return NextResponse.redirect(new URL('/dashboard', request.url))
  }

  return NextResponse.next()
}

export const config = {
  matcher: ['/dashboard/:path*', '/projects/:path*', '/topics/:path*', '/articles/:path*', '/profile/:path*', '/login', '/signup'],
}
