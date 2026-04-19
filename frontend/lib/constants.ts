export const API_BASE_URL =
  process.env.NEXT_PUBLIC_API_BASE_URL?.replace(/\/$/, '') ?? 'http://127.0.0.1:8000/api'

export const AUTH_TOKEN_KEY = 'contenthub_token'
export const AUTH_USER_KEY = 'contenthub_user'
