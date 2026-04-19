import { NextRequest, NextResponse } from 'next/server'

// Plan limits
const PLAN_LIMITS = {
  free: {
    projects: 2,
    topics: 50,
    articles: 200,
    teamMembers: 1,
  },
  pro: {
    projects: 100,
    topics: 1000,
    articles: 5000,
    teamMembers: 10,
  },
  agency: {
    projects: 1000,
    topics: 10000,
    articles: 100000,
    teamMembers: 100,
  },
}

// Mock usage data
let usageData = {
  'user-1': {
    plan: 'free',
    projects: 5,
    topics: 89,
    articles: 245,
    teamMembers: 3,
    monthlyRequests: 1250,
    storageUsed: 125, // MB
  },
}

export async function GET(request: NextRequest) {
  try {
    const userId = request.headers.get('x-user-id') || 'user-1'

    if (!usageData[userId as keyof typeof usageData]) {
      usageData[userId as keyof typeof usageData] = {
        plan: 'free',
        projects: 0,
        topics: 0,
        articles: 0,
        teamMembers: 1,
        monthlyRequests: 0,
        storageUsed: 0,
      }
    }

    const usage = usageData[userId as keyof typeof usageData]
    const limits = PLAN_LIMITS[usage.plan as keyof typeof PLAN_LIMITS]

    // Calculate percentages
    const projectPercentage = Math.round((usage.projects / limits.projects) * 100)
    const topicPercentage = Math.round((usage.topics / limits.topics) * 100)
    const articlePercentage = Math.round((usage.articles / limits.articles) * 100)
    const memberPercentage = Math.round(
      (usage.teamMembers / limits.teamMembers) * 100
    )

    // Check for warnings (>80% usage)
    const warnings = []
    if (projectPercentage > 80)
      warnings.push('Projects limit approaching')
    if (topicPercentage > 80) warnings.push('Topics limit approaching')
    if (articlePercentage > 80)
      warnings.push('Articles limit approaching')
    if (memberPercentage > 80)
      warnings.push('Team members limit approaching')

    return NextResponse.json({
      success: true,
      plan: usage.plan,
      limits,
      usage: {
        projects: {
          used: usage.projects,
          limit: limits.projects,
          percentage: projectPercentage,
        },
        topics: {
          used: usage.topics,
          limit: limits.topics,
          percentage: topicPercentage,
        },
        articles: {
          used: usage.articles,
          limit: limits.articles,
          percentage: articlePercentage,
        },
        teamMembers: {
          used: usage.teamMembers,
          limit: limits.teamMembers,
          percentage: memberPercentage,
        },
      },
      monthlyRequests: usage.monthlyRequests,
      storageUsed: usage.storageUsed,
      warnings,
    })
  } catch (error) {
    return NextResponse.json(
      { success: false, error: 'Failed to fetch usage data' },
      { status: 500 }
    )
  }
}

export async function PUT(request: NextRequest) {
  try {
    const userId = request.headers.get('x-user-id') || 'user-1'
    const body = await request.json()

    if (!usageData[userId as keyof typeof usageData]) {
      usageData[userId as keyof typeof usageData] = {
        plan: 'free',
        projects: 0,
        topics: 0,
        articles: 0,
        teamMembers: 1,
        monthlyRequests: 0,
        storageUsed: 0,
      }
    }

    // Update usage
    const usage = usageData[userId as keyof typeof usageData]

    if (body.projects !== undefined) usage.projects = body.projects
    if (body.topics !== undefined) usage.topics = body.topics
    if (body.articles !== undefined) usage.articles = body.articles
    if (body.teamMembers !== undefined)
      usage.teamMembers = body.teamMembers

    return NextResponse.json({
      success: true,
      data: usage,
    })
  } catch (error) {
    return NextResponse.json(
      { success: false, error: 'Failed to update usage data' },
      { status: 500 }
    )
  }
}
