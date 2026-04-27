'use client'

import { clearAuthSession, getAuthToken } from '@/lib/auth'
import { API_BASE_URL } from '@/lib/constants'
import type {
  Article,
  BillingOverview,
  BillingSubscriptionResponse,
  ApiUser,
  AuthResponse,
  PaginatedResponse,
  ProfileData,
  Project,
  ProjectListResponse,
  ProjectStatus,
  Topic,
} from '@/lib/types'

type RequestOptions = Omit<RequestInit, 'body'> & {
  body?: Record<string, unknown>
  requiresAuth?: boolean
}

type ApiErrorPayload = {
  message?: string
  errors?: Record<string, string[]>
}

export class ApiError extends Error {
  status: number
  payload?: ApiErrorPayload

  constructor(message: string, status: number, payload?: ApiErrorPayload) {
    super(message)
    this.name = 'ApiError'
    this.status = status
    this.payload = payload
  }
}

async function apiRequest<T>(endpoint: string, options: RequestOptions = {}): Promise<T> {
  const { body, headers, requiresAuth = true, ...rest } = options
  const token = requiresAuth ? getAuthToken() : null

  const requestHeaders = new Headers(headers)
  requestHeaders.set('Accept', 'application/json')

  if (body) {
    requestHeaders.set('Content-Type', 'application/json')
  }

  if (token) {
    requestHeaders.set('Authorization', `Bearer ${token}`)
  }

  const response = await fetch(`${API_BASE_URL}${endpoint}`, {
    ...rest,
    headers: requestHeaders,
    body: body ? JSON.stringify(body) : undefined,
    cache: 'no-store',
  })

  const text = await response.text()
  const payload = text ? (JSON.parse(text) as ApiErrorPayload | T) : null

  if (!response.ok) {
    if (response.status === 401) {
      clearAuthSession()

      if (typeof window !== 'undefined' && window.location.pathname !== '/login') {
        window.location.href = '/login'
      }
    }

    const errorPayload = payload as ApiErrorPayload | null
    const firstValidationMessage = errorPayload?.errors
      ? Object.values(errorPayload.errors)[0]?.[0]
      : null

    throw new ApiError(
      firstValidationMessage ?? errorPayload?.message ?? 'The API request failed.',
      response.status,
      errorPayload ?? undefined,
    )
  }

  return payload as T
}

function buildQuery(params: Record<string, string | number | boolean | undefined>) {
  const search = new URLSearchParams()

  for (const [key, value] of Object.entries(params)) {
    if (value !== undefined && value !== null && value !== '') {
      search.set(key, String(value))
    }
  }

  const query = search.toString()
  return query ? `?${query}` : ''
}

export function getApiErrorMessage(error: unknown) {
  if (error instanceof ApiError) {
    return error.message
  }

  if (error instanceof Error) {
    return error.message
  }

  return 'Something went wrong while talking to the API.'
}

export const api = {
  login(email: string, password: string) {
    return apiRequest<AuthResponse>('/login', {
      method: 'POST',
      body: { email, password },
      requiresAuth: false,
    })
  },

  register(name: string, email: string, password: string, passwordConfirmation: string) {
    return apiRequest<AuthResponse>('/register', {
      method: 'POST',
      body: {
        name,
        email,
        password,
        password_confirmation: passwordConfirmation,
      },
      requiresAuth: false,
    })
  },

  logout() {
    return apiRequest<{ message: string }>('/logout', { method: 'POST' })
  },

  profile() {
    return apiRequest<{ data: ProfileData }>('/profile')
  },

  getProjects(params?: { includeInactive?: boolean; includeRemoved?: boolean }) {
    return apiRequest<ProjectListResponse>(
      `/projects${buildQuery({
        include_inactive: params?.includeInactive,
        include_removed: params?.includeRemoved,
      })}`,
    )
  },

  getBillingOverview() {
    return apiRequest<{ data: BillingOverview }>('/billing')
  },

  subscribeToPlan(payload: { planId: number; paymentMethod: 'card' | 'upi' }) {
    return apiRequest<BillingSubscriptionResponse>('/billing/subscribe', {
      method: 'POST',
      body: {
        plan_id: payload.planId,
        payment_method: payload.paymentMethod,
      },
    })
  },

  syncSubscription(subscriptionId: string|number|null) {
    if (!subscriptionId) {
      return Promise.reject()
    }

    return apiRequest<{ message: string; data: { user: ApiUser } }>('/billing/sync', {
      method: 'POST',
      body: { subscription_id: subscriptionId },
    })
  },

  createProject(payload: { name: string; description?: string }) {
    return apiRequest<{ message: string; data: Project }>('/projects', {
      method: 'POST',
      body: payload,
    })
  },

  updateProjectStatus(projectId: number, status: ProjectStatus) {
    return apiRequest<{ message: string; data: Project }>(`/projects/${projectId}/status`, {
      method: 'PATCH',
      body: { status },
    })
  },

  getTopics(projectId: number, params?: { refresh?: boolean; limit?: number }) {
    return apiRequest<PaginatedResponse<Topic>>(`/topics/${projectId}${buildQuery(params ?? {})}`)
  },

  generateProjectArticles(projectId: number, limit = 5) {
    return apiRequest<{ message: string; data: Record<string, unknown> }>(`/generate/${projectId}`, {
      method: 'POST',
      body: { limit },
    })
  },

  generateTopicArticle(projectId: number, topicId: number) {
    return apiRequest<{ message: string; data: Record<string, unknown> }>(
      `/projects/${projectId}/topics/${topicId}/generate`,
      { method: 'POST' },
    )
  },

  getArticles(projectId: number, params?: { status?: string }) {
    return apiRequest<PaginatedResponse<Article>>(
      `/articles/${projectId}${buildQuery(params ?? {})}`,
    )
  },
}
