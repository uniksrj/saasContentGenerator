import { NextRequest, NextResponse } from 'next/server'

// Stripe plan IDs (these would be from your Stripe dashboard)
const STRIPE_PLANS = {
  free: null,
  pro: 'price_pro_monthly',
  agency: 'price_agency_monthly',
}

const PLAN_DETAILS = {
  free: {
    name: 'Free',
    price: 0,
    features: ['2 Projects', '50 Topics', '200 Articles', 'Basic Support'],
  },
  pro: {
    name: 'Pro',
    price: 2900, // cents
    features: [
      'Unlimited Projects',
      'Unlimited Topics',
      '5,000 Articles',
      'Priority Support',
      'Advanced Analytics',
    ],
  },
  agency: {
    name: 'Agency',
    price: 9900, // cents
    features: [
      'Everything in Pro',
      'Unlimited Articles',
      'Team Management',
      '24/7 Support',
      'Custom Integrations',
    ],
  },
}

export async function POST(request: NextRequest) {
  try {
    const body = await request.json()
    const { planId, email, userId } = body

    if (!planId || !['free', 'pro', 'agency'].includes(planId)) {
      return NextResponse.json(
        { success: false, error: 'Invalid plan ID' },
        { status: 400 }
      )
    }

    // If upgrading to free, no checkout needed
    if (planId === 'free') {
      return NextResponse.json({
        success: true,
        message: 'Upgraded to free plan',
        planId,
      })
    }

    // In a real implementation, you would:
    // 1. Create or get a Stripe customer
    // 2. Create a checkout session
    // 3. Return the checkout URL

    // For now, we'll return a mock response
    const plan = PLAN_DETAILS[planId as keyof typeof PLAN_DETAILS]

    return NextResponse.json({
      success: true,
      message: 'Checkout session created',
      planId,
      planName: plan.name,
      price: plan.price,
      checkoutUrl: `/checkout/${planId}?session_id=mock_session_123`,
      // In production, this would be a real Stripe checkout URL:
      // checkoutUrl: 'https://checkout.stripe.com/pay/cs_test_...'
    })
  } catch (error) {
    console.error('Checkout error:', error)
    return NextResponse.json(
      { success: false, error: 'Failed to create checkout session' },
      { status: 500 }
    )
  }
}

export async function GET(request: NextRequest) {
  try {
    // Return available plans
    return NextResponse.json({
      success: true,
      plans: PLAN_DETAILS,
    })
  } catch (error) {
    return NextResponse.json(
      { success: false, error: 'Failed to fetch plans' },
      { status: 500 }
    )
  }
}
