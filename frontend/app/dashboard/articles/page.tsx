import { Card } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { MoreVertical, Plus, Eye, Edit } from 'lucide-react'

const articles = [
  {
    id: 1,
    title: 'Complete Guide to AI Writing Tools',
    topic: 'AI in Content Creation',
    status: 'Published',
    views: 1240,
    date: '2 days ago',
  },
  {
    id: 2,
    title: 'SEO Optimization Techniques 2026',
    topic: 'SEO Best Practices 2026',
    status: 'Published',
    views: 892,
    date: '1 week ago',
  },
  {
    id: 3,
    title: 'Content Strategy Framework Deep Dive',
    topic: 'Content Strategy Framework',
    status: 'Draft',
    views: 0,
    date: 'Today',
  },
  {
    id: 4,
    title: 'Creating Engaging Social Media Content',
    topic: 'AI in Content Creation',
    status: 'Review',
    views: 0,
    date: 'Yesterday',
  },
  {
    id: 5,
    title: 'Product Launch Checklist',
    topic: 'Product Launch Guide',
    status: 'Published',
    views: 456,
    date: '3 days ago',
  },
  {
    id: 6,
    title: 'Email Subject Line Best Practices',
    topic: 'Email Marketing Tips',
    status: 'Published',
    views: 634,
    date: '1 week ago',
  },
]

const getStatusColor = (status: string) => {
  switch (status) {
    case 'Published':
      return 'bg-green-500/20 text-green-400'
    case 'Draft':
      return 'bg-yellow-500/20 text-yellow-400'
    case 'Review':
      return 'bg-blue-500/20 text-blue-400'
    default:
      return 'bg-gray-500/20 text-gray-400'
  }
}

export default function ArticlesPage() {
  return (
    <main className="p-6 space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold mb-2">Articles</h1>
          <p className="text-foreground/70">Manage and publish your content</p>
        </div>
        <Button className="gap-2">
          <Plus size={18} />
          New Article
        </Button>
      </div>

      {/* Search and Filter */}
      <div className="flex gap-4">
        <Input placeholder="Search articles..." className="max-w-md" />
        <Button variant="outline">Filter by Status</Button>
      </div>

      {/* Articles Table */}
      <Card className="bg-card border border-border overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr className="border-b border-border bg-secondary/50">
                <th className="px-6 py-4 text-left text-sm font-semibold">Title</th>
                <th className="px-6 py-4 text-left text-sm font-semibold">Topic</th>
                <th className="px-6 py-4 text-center text-sm font-semibold">Status</th>
                <th className="px-6 py-4 text-center text-sm font-semibold">Views</th>
                <th className="px-6 py-4 text-left text-sm font-semibold">Date</th>
                <th className="px-6 py-4 text-center text-sm font-semibold">Actions</th>
              </tr>
            </thead>
            <tbody>
              {articles.map((article, idx) => (
                <tr
                  key={article.id}
                  className={`border-b border-border hover:bg-secondary/30 transition ${
                    idx === articles.length - 1 ? 'border-b-0' : ''
                  }`}
                >
                  <td className="px-6 py-4">
                    <h3 className="font-medium hover:text-primary cursor-pointer">{article.title}</h3>
                  </td>
                  <td className="px-6 py-4 text-sm text-foreground/70">{article.topic}</td>
                  <td className="px-6 py-4 text-center">
                    <span className={`text-xs px-2 py-1 rounded-full ${getStatusColor(article.status)}`}>
                      {article.status}
                    </span>
                  </td>
                  <td className="px-6 py-4 text-center">
                    <div className="flex items-center justify-center gap-1">
                      <Eye size={16} className="text-foreground/60" />
                      <span className="text-sm">{article.views}</span>
                    </div>
                  </td>
                  <td className="px-6 py-4 text-sm text-foreground/70">{article.date}</td>
                  <td className="px-6 py-4 text-center">
                    <div className="flex items-center justify-center gap-2">
                      <button className="p-1 hover:bg-secondary/50 rounded transition">
                        <Edit size={18} className="text-foreground/60" />
                      </button>
                      <button className="p-1 hover:bg-secondary/50 rounded transition">
                        <MoreVertical size={18} className="text-foreground/60" />
                      </button>
                    </div>
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
          Showing 1 to {articles.length} of {articles.length} articles
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
