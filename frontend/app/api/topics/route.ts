import { NextRequest, NextResponse } from 'next/server'

// Mock database with scoring system
let topics = [
  {
    id: 1,
    name: 'AI in Content Creation',
    project: 'Blog Platform',
    score: 92,
    articles: 8,
    engagement: '+34%',
    status: 'Trending',
    views: 2480,
    created: '2024-01-10',
    projectId: 1,
    userId: 'user-1',
  },
  {
    id: 2,
    name: 'SEO Best Practices 2026',
    project: 'Marketing Campaign',
    score: 88,
    articles: 5,
    engagement: '+28%',
    status: 'Popular',
    views: 1920,
    created: '2024-01-15',
    projectId: 2,
    userId: 'user-1',
  },
  {
    id: 3,
    name: 'Content Strategy Framework',
    project: 'Blog Platform',
    score: 85,
    articles: 3,
    engagement: '+15%',
    status: 'Growing',
    views: 1250,
    created: '2024-01-20',
    projectId: 1,
    userId: 'user-1',
  },
]

let nextTopicId = 4

// Calculate topic score based on articles, views, and engagement
function calculateScore(
  articleCount: number,
  views: number,
  engagement: number
): number {
  const articleScore = Math.min(articleCount * 5, 30)
  const viewScore = Math.min((views / 100) * 3, 40)
  const engagementScore = Math.min(engagement, 30)
  return Math.round(articleScore + viewScore + engagementScore)
}

export async function GET(request: NextRequest) {
  try {
    const userId = request.headers.get('x-user-id') || 'user-1'
    const projectId = request.nextUrl.searchParams.get('projectId')

    let filtered = topics.filter(t => t.userId === userId)

    if (projectId) {
      filtered = filtered.filter(t => t.projectId === parseInt(projectId))
    }

    // Sort by score descending
    filtered.sort((a, b) => b.score - a.score)

    return NextResponse.json({
      success: true,
      data: filtered,
    })
  } catch (error) {
    return NextResponse.json(
      { success: false, error: 'Failed to fetch topics' },
      { status: 500 }
    )
  }
}

export async function POST(request: NextRequest) {
  try {
    const body = await request.json()
    const userId = request.headers.get('x-user-id') || 'user-1'

    const score = calculateScore(0, body.views || 0, body.engagement || 0)

    const newTopic = {
      id: nextTopicId++,
      ...body,
      score,
      articles: 0,
      views: body.views || 0,
      created: new Date().toISOString().split('T')[0],
      userId,
    }

    topics.push(newTopic)

    return NextResponse.json(
      { success: true, data: newTopic },
      { status: 201 }
    )
  } catch (error) {
    return NextResponse.json(
      { success: false, error: 'Failed to create topic' },
      { status: 500 }
    )
  }
}
