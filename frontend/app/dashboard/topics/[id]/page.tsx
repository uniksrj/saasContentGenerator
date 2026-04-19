'use client'

import { Card } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts'
import { ArrowLeft, Edit2, Trash2, BarChart3 } from 'lucide-react'

const performanceData = [
  { day: 'Mon', score: 75, engagement: 45 },
  { day: 'Tue', score: 78, engagement: 52 },
  { day: 'Wed', score: 82, engagement: 60 },
  { day: 'Thu', score: 85, engagement: 68 },
  { day: 'Fri', score: 88, engagement: 75 },
  { day: 'Sat', score: 90, engagement: 82 },
  { day: 'Sun', score: 92, engagement: 88 },
]

const relatedArticles = [
  { id: 1, title: 'Getting Started with AI', status: 'Published', views: 450 },
  { id: 2, title: 'Advanced AI Techniques', status: 'Published', views: 320 },
  { id: 3, title: 'AI Tools Review 2026', status: 'Draft', views: 0 },
  { id: 4, title: 'Future of AI', status: 'Review', views: 0 },
]

export default function TopicDetailPage() {
  return (
    <main className="p-6 space-y-6">
      {/* Header */}
      <div className="flex items-center gap-4">
        <button className="p-2 hover:bg-secondary/50 rounded transition">
          <ArrowLeft size={20} />
        </button>
        <div className="flex-1">
          <h1 className="text-3xl font-bold mb-2">AI in Content Creation</h1>
          <p className="text-foreground/70">Blog Platform • Created 2 months ago</p>
        </div>
        <div className="flex gap-2">
          <Button variant="outline" size="sm">
            <Edit2 size={18} />
          </Button>
          <Button variant="outline" size="sm" className="text-destructive">
            <Trash2 size={18} />
          </Button>
        </div>
      </div>

      {/* Topic Overview */}
      <div className="grid md:grid-cols-4 gap-4">
        {[
          { label: 'Relevance Score', value: '92', change: '+5 pts' },
          { label: 'Articles', value: '8', change: '2 this week' },
          { label: 'Avg Engagement', value: '34%', change: '+8% this week' },
          { label: 'Total Views', value: '2,480', change: '+320 this week' },
        ].map((stat, i) => (
          <Card key={i} className="bg-card border border-border p-4">
            <p className="text-foreground/60 text-sm mb-2">{stat.label}</p>
            <p className="text-3xl font-bold mb-1">{stat.value}</p>
            <p className="text-foreground/50 text-sm">{stat.change}</p>
          </Card>
        ))}
      </div>

      {/* Performance Chart */}
      <Card className="bg-card border border-border p-6">
        <h2 className="text-xl font-semibold mb-4 flex items-center gap-2">
          <BarChart3 size={20} />
          Performance Trend
        </h2>
        <ResponsiveContainer width="100%" height={300}>
          <LineChart data={performanceData}>
            <CartesianGrid strokeDasharray="3 3" stroke="#333" />
            <XAxis dataKey="day" stroke="#888" />
            <YAxis stroke="#888" />
            <Tooltip
              contentStyle={{
                backgroundColor: '#1a1a1a',
                border: '1px solid #333',
                borderRadius: '0.5rem',
              }}
            />
            <Line
              type="monotone"
              dataKey="score"
              stroke="var(--color-primary)"
              strokeWidth={2}
              dot={false}
              name="Relevance Score"
            />
            <Line
              type="monotone"
              dataKey="engagement"
              stroke="var(--color-chart-2)"
              strokeWidth={2}
              dot={false}
              name="Engagement %"
            />
          </LineChart>
        </ResponsiveContainer>
      </Card>

      {/* Topic Settings */}
      <Card className="bg-card border border-border p-8">
        <h2 className="text-xl font-semibold mb-6">Topic Settings</h2>
        <div className="space-y-6">
          <div>
            <label className="block text-sm font-medium mb-2">Topic Name</label>
            <Input defaultValue="AI in Content Creation" />
          </div>
          <div>
            <label className="block text-sm font-medium mb-2">Description</label>
            <textarea
              className="w-full px-4 py-2 rounded-lg bg-secondary border border-border text-foreground"
              rows={4}
              defaultValue="Exploring how artificial intelligence is transforming content creation processes and tools."
            />
          </div>
          <div className="grid md:grid-cols-3 gap-6">
            <div>
              <label className="block text-sm font-medium mb-2">Project</label>
              <select className="w-full px-4 py-2 rounded-lg bg-secondary border border-border text-foreground">
                <option>Blog Platform</option>
                <option>Marketing Campaign</option>
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium mb-2">Status</label>
              <select className="w-full px-4 py-2 rounded-lg bg-secondary border border-border text-foreground">
                <option>Active</option>
                <option>Archived</option>
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium mb-2">Importance</label>
              <select className="w-full px-4 py-2 rounded-lg bg-secondary border border-border text-foreground">
                <option>High</option>
                <option>Medium</option>
                <option>Low</option>
              </select>
            </div>
          </div>
          <Button>Save Changes</Button>
        </div>
      </Card>

      {/* Related Articles */}
      <Card className="bg-card border border-border p-6">
        <div className="flex items-center justify-between mb-6">
          <h2 className="text-xl font-semibold">Related Articles</h2>
          <Button size="sm">New Article</Button>
        </div>
        <div className="space-y-3">
          {relatedArticles.map((article) => (
            <div
              key={article.id}
              className="p-4 rounded-lg bg-secondary/30 hover:bg-secondary/50 transition cursor-pointer flex items-center justify-between"
            >
              <div>
                <h3 className="font-medium">{article.title}</h3>
                <p className="text-sm text-foreground/60 mt-1">
                  {article.status} • {article.views} views
                </p>
              </div>
              <span className="text-xs px-2 py-1 rounded-full bg-primary/20 text-primary">
                {article.status}
              </span>
            </div>
          ))}
        </div>
      </Card>
    </main>
  )
}
