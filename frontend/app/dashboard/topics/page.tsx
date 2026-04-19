import { Card } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { MoreVertical, Plus, TrendingUp } from 'lucide-react'

const topics = [
  {
    id: 1,
    name: 'AI in Content Creation',
    project: 'Blog Platform',
    score: 92,
    articles: 8,
    engagement: '+34%',
    status: 'Trending',
  },
  {
    id: 2,
    name: 'SEO Best Practices 2026',
    project: 'Marketing Campaign',
    score: 88,
    articles: 5,
    engagement: '+28%',
    status: 'Popular',
  },
  {
    id: 3,
    name: 'Content Strategy Framework',
    project: 'Blog Platform',
    score: 85,
    articles: 3,
    engagement: '+15%',
    status: 'Growing',
  },
  {
    id: 4,
    name: 'Product Launch Guide',
    project: 'Product Documentation',
    score: 78,
    articles: 4,
    engagement: '+12%',
    status: 'Active',
  },
  {
    id: 5,
    name: 'Email Marketing Tips',
    project: 'Newsletter Content',
    score: 72,
    articles: 6,
    engagement: '+8%',
    status: 'Active',
  },
]

export default function TopicsPage() {
  return (
    <main className="p-6 space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold mb-2">Topics</h1>
          <p className="text-foreground/70">Manage topics and view their performance</p>
        </div>
        <Button className="gap-2">
          <Plus size={18} />
          New Topic
        </Button>
      </div>

      {/* Search and Filter */}
      <div className="flex gap-4">
        <Input placeholder="Search topics..." className="max-w-md" />
        <Button variant="outline">Filter by Project</Button>
      </div>

      {/* Topics Table */}
      <Card className="bg-card border border-border overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr className="border-b border-border bg-secondary/50">
                <th className="px-6 py-4 text-left text-sm font-semibold">Topic</th>
                <th className="px-6 py-4 text-left text-sm font-semibold">Project</th>
                <th className="px-6 py-4 text-center text-sm font-semibold">Score</th>
                <th className="px-6 py-4 text-center text-sm font-semibold">Articles</th>
                <th className="px-6 py-4 text-center text-sm font-semibold">Engagement</th>
                <th className="px-6 py-4 text-left text-sm font-semibold">Status</th>
                <th className="px-6 py-4 text-center text-sm font-semibold">Actions</th>
              </tr>
            </thead>
            <tbody>
              {topics.map((topic, idx) => (
                <tr
                  key={topic.id}
                  className={`border-b border-border hover:bg-secondary/30 transition ${
                    idx === topics.length - 1 ? 'border-b-0' : ''
                  }`}
                >
                  <td className="px-6 py-4">
                    <h3 className="font-medium">{topic.name}</h3>
                  </td>
                  <td className="px-6 py-4 text-sm text-foreground/70">{topic.project}</td>
                  <td className="px-6 py-4 text-center">
                    <div className="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-primary/20 text-primary font-semibold">
                      {topic.score}
                    </div>
                  </td>
                  <td className="px-6 py-4 text-center text-sm">{topic.articles}</td>
                  <td className="px-6 py-4 text-center">
                    <div className="flex items-center justify-center gap-1 text-green-500 text-sm">
                      <TrendingUp size={16} />
                      {topic.engagement}
                    </div>
                  </td>
                  <td className="px-6 py-4">
                    <span className="text-xs px-2 py-1 rounded-full bg-secondary text-foreground/70">
                      {topic.status}
                    </span>
                  </td>
                  <td className="px-6 py-4 text-center">
                    <button className="text-foreground/60 hover:text-foreground">
                      <MoreVertical size={18} />
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </Card>

      {/* Pagination */}
      <div className="flex items-center justify-between">
        <p className="text-sm text-foreground/60">
          Showing 1 to {topics.length} of {topics.length} topics
        </p>
        <div className="flex gap-2">
          <Button variant="outline" size="sm">
            Previous
          </Button>
          <Button variant="outline" size="sm">
            Next
          </Button>
        </div>
      </div>
    </main>
  )
}
