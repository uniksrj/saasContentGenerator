export interface Plan {
  id: number
  name: string
  slug: string
  description?: string | null
  monthly_article_limit: number
  monthly_token_limit: number
  price_cents: number
  currency: string
  billing_interval: string
}

export interface ApiUser {
  id: number
  name: string
  email: string
  role: string
  plan_id?: number | null
  current_project_id?: number | null
  subscription_status?: string
  plan?: Plan | null
}

export type ProjectStatus = 'active' | 'disabled' | 'removed'

export interface Project {
  id: number
  name: string
  slug: string
  description?: string | null
  status: ProjectStatus
  is_active: boolean
  topics_count?: number
  articles_count?: number
  created_at: string
  updated_at: string
}

export interface ProjectListMeta {
  total_projects: number
  active_projects: number
  disabled_projects: number
  removed_projects: number
  project_limit: number | null
  remaining_project_slots: number | null
}

export interface ProjectListResponse {
  data: Project[]
  meta: ProjectListMeta
}

export interface BillingPlan {
  id: number
  name: string
  slug: string
  description?: string | null
  monthly_article_limit: number
  monthly_token_limit: number
  price_cents: number
  currency: string
  billing_interval: string
  stripe_price_id?: string | null
  features: string[]
  is_current: boolean
  supports_upi: boolean
}

export interface BillingOverview {
  stripe_publishable_key: string
  subscription_status?: string | null
  current_plan_id?: number | null
  current_plan?: {
    id: number
    name: string
    slug: string
    currency: string
    price_cents: number
  } | null
  plans: BillingPlan[]
}

export interface BillingSubscriptionResponse {
  message: string
  mode: 'local' | 'stripe'
  subscription_id?: string | null
  client_secret?: string | null
}

export interface Topic {
  id: number
  project_id: number
  title: string
  description?: string | null
  source_type?: string | null
  source_url?: string | null
  score?: number | null
  status?: string | null
  published_at?: string | null
  articles_count?: number
}

export interface Article {
  id: number
  project_id: number
  topic_id?: number | null
  title: string
  status: string
  category?: string | null
  published_at?: string | null
  created_at: string
  reading_time?: number | null
  word_count?: number | null
  topic?: {
    id: number
    title: string
    project_id: number
  } | null
}

export interface UsageSnapshot {
  can_generate: boolean
  remaining_articles: number
  remaining_tokens: number
  message: string
}

export interface ProfileData {
  user: ApiUser
  plan: Plan | null
  current_project: Project | null
  usage: UsageSnapshot
  counts: {
    projects: number
    topics: number
    articles: number
  }
}

export interface PaginatedResponse<T> {
  current_page: number
  data: T[]
  last_page: number
  per_page: number
  total: number
}

export interface AuthResponse {
  message: string
  token: string
  token_type: string
  data: {
    user: ApiUser
  }
}
