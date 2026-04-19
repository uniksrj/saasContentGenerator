import { NextRequest, NextResponse } from 'next/server'

// Mock database
let articles = [
  {
    id: 1,
    title: 'Complete Guide to AI Writing Tools',
    topic: 'AI in Content Creation',
    status: 'published',
    views: 1240,
    created: '2024-02-15',
    content: 'AI writing tools are revolutionizing content creation...',
    projectId: 1,
    userId: 'user-1',
  },
  {
    id: 2,
    title: 'SEO Optimization Techniques 2026',
    topic: 'SEO Best Practices 2026',
    status: 'published',
    views: 892,
    created: '2024-02-08',
    content: 'Learn the latest SEO techniques...',
    projectId: 1,
    userId: 'user-1',
  },
  {
    id: 3,
    title: 'Content Strategy Framework Deep Dive',
    topic: 'Content Strategy Framework',
    status: 'draft',
    views: 0,
    created: '2024-02-20',
    content: 'Building an effective content strategy...',
    projectId: 1,
    userId: 'user-1',
  },
]

let nextArticleId = 4

export async function GET(request: NextRequest) {
  try {
    const userId = request.headers.get('x-user-id') || 'user-1'
    const status = request.nextUrl.searchParams.get('status')
    const projectId = request.nextUrl.searchParams.get('projectId')

    let filtered = articles.filter(a => a.userId === userId)

    if (status) {
      filtered = filtered.filter(a => a.status === status)
    }

    if (projectId) {
      filtered = filtered.filter(a => a.projectId === parseInt(projectId))
    }

    return NextResponse.json({
      success: true,
      data: filtered,
    })
  } catch (error) {
    return NextResponse.json(
      { success: false, error: 'Failed to fetch articles' },
      { status: 500 }
    )
  }
}

export async function POST(request: NextRequest) {
  try {
    const body = await request.json()
    const userId = request.headers.get('x-user-id') || 'user-1'

    const newArticle = {
      id: nextArticleId++,
      ...body,
      views: 0,
      created: new Date().toISOString().split('T')[0],
      userId,
    }

    articles.push(newArticle)

    return NextResponse.json(
      { success: true, data: newArticle },
      { status: 201 }
    )
  } catch (error) {
    return NextResponse.json(
      { success: false, error: 'Failed to create article' },
      { status: 500 }
    )
  }
}
