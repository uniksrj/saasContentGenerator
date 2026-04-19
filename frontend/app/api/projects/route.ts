import { NextRequest, NextResponse } from 'next/server'

// Mock database
let projects = [
  {
    id: 1,
    name: 'Blog Platform',
    description: 'Main blog content management',
    topics: 12,
    articles: 45,
    members: 3,
    created: '2024-01-15',
    userId: 'user-1',
  },
  {
    id: 2,
    name: 'Marketing Campaign',
    description: 'Q1 2026 marketing initiatives',
    topics: 8,
    articles: 23,
    members: 2,
    created: '2024-02-10',
    userId: 'user-1',
  },
]

let nextProjectId = 3

export async function GET(request: NextRequest) {
  try {
    // In a real app, you'd verify the user session here
    const userId = request.headers.get('x-user-id') || 'user-1'
    
    const userProjects = projects.filter(p => p.userId === userId)
    
    return NextResponse.json({
      success: true,
      data: userProjects,
    })
  } catch (error) {
    return NextResponse.json(
      { success: false, error: 'Failed to fetch projects' },
      { status: 500 }
    )
  }
}

export async function POST(request: NextRequest) {
  try {
    const body = await request.json()
    const userId = request.headers.get('x-user-id') || 'user-1'

    const newProject = {
      id: nextProjectId++,
      ...body,
      members: 1,
      articles: 0,
      topics: 0,
      created: new Date().toISOString().split('T')[0],
      userId,
    }

    projects.push(newProject)

    return NextResponse.json(
      { success: true, data: newProject },
      { status: 201 }
    )
  } catch (error) {
    return NextResponse.json(
      { success: false, error: 'Failed to create project' },
      { status: 500 }
    )
  }
}
