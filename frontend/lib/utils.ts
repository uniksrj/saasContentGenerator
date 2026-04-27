import { clsx, type ClassValue } from 'clsx'
import { twMerge } from 'tailwind-merge'
import { BillingPlan } from './types'

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs))
}

export function formatPrice(plan: BillingPlan) {
  if (!plan || plan.price_cents == null) return ''

  if (plan.price_cents === 0) {
    return 'Free'
  }

  try {
    return new Intl.NumberFormat('en-IN', {
      style: 'currency',
      currency: plan.currency || 'INR',
    }).format(plan.price_cents / 100)
  } catch {
    // fallback if currency is invalid
    return `${(plan.price_cents / 100).toFixed(2)} ${plan.currency}`
  }
}