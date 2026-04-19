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

export interface Project {
  id: number
  name: string
  slug: string
  description?: string | null
  is_active: boolean
  topics_count?: number
  articles_count?: number
  created_at: string
  updated_at: string
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
