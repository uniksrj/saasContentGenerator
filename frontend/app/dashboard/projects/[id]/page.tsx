'use client'

import { Card } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { ArrowLeft, Users, Settings, Share2, MoreVertical } from 'lucide-react'
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts'

const projectStats = [
  { name: 'Topics', value: 12 },
  { name: 'Articles', value: 45 },
  { name: 'Members', value: 3 },
  { name: 'Views', value: 2840 },
]

const topicsList = [
  { name: 'AI in Content Creation', articles: 8, score: 92 },
  { name: 'SEO Optimization', articles: 6, score: 88 },
  { name: 'Content Strategy', articles: 5, score: 85 },
]

const articlesList = [
  { title: 'Latest AI Tools', status: 'Published', views: 450 },
  { title: 'SEO Guide 2026', status: 'Published', views: 320 },
  { title: 'Content Planning', status: 'Draft', views: 0 },
]

const membersList = [
  { name: 'John Doe', role: 'Owner', email: 'john@example.com' },
  { name: 'Jane Smith', role: 'Editor', email: 'jane@example.com' },
  { name: 'Bob Wilson', role: 'Contributor', email: 'bob@example.com' },
]

export default function ProjectDetailPage() {
  return (
    <main className="p-6 space-y-6">
      {/* Header */}
      <div className="flex items-center gap-4 mb-6">
        <button className="p-2 hover:bg-secondary/50 rounded transition">
          <ArrowLeft size={20} />
        </button>
        <div className="flex-1">
          <h1 className="text-3xl font-bold">Blog Platform</h1>
          <p className="text-foreground/70 mt-1">Main blog content management</p>
        </div>
        <Button variant="outline" size="sm" className="gap-2">
          <Share2 size={18} />
          Share
        </Button>
        <Button variant="outline" size="sm" className="gap-2">
          <Settings size={18} />
          Settings
        </Button>
      </div>

      {/* Stats Grid */}
      <div className="grid md:grid-cols-4 gap-4">
        {projectStats.map((stat, i) => (
          <Card key={i} className="bg-card border border-border p-6">
            <p className="text-foreground/60 text-sm mb-2">{stat.name}</p>
            <p className="text-3xl font-bold">{stat.value}</p>
          </Card>
        ))}
      </div>

      {/* Main Content */}
      <div className="grid lg:grid-cols-3 gap-6">
        {/* Topics Section */}
        <div className="lg:col-span-2 space-y-6">
          <Card className="bg-card border border-border p-6">
            <div className="flex items-center justify-between mb-6">
              <h2 className="text-xl font-semibold">Topics</h2>
              <Button size="sm">New Topic</Button>
            </div>
            <div className="space-y-3">
              {topicsList.map((topic, i) => (
                <div
                  key={i}
                  className="p-4 rounded-lg bg-secondary/30 hover:bg-secondary/50 transition"
                >
                  <div className="flex items-center justify-between">
                    <h3 className="font-medium">{topic.name}</h3>
                    <div className="inline-flex items-center gap-2 px-2 py-1 rounded-full bg-primary/20 text-primary text-sm font-semibold">
                      {topic.score}
                    </div>
                  </div>
                  <p className="text-sm text-foreground/60 mt-2">{topic.articles} articles</p>
                </div>
              ))}
            </div>
          </Card>

          <Card className="bg-card border border-border p-6">
            <div className="flex items-center justify-between mb-6">
              <h2 className="text-xl font-semibold">Recent Articles</h2>
              <Button size="sm">New Article</Button>
            </div>
            <div className="space-y-3">
              {articlesList.map((article, i) => (
                <div
                  key={i}
                  className="p-4 rounded-lg bg-secondary/30 hover:bg-secondary/50 transition cursor-pointer"
                >
                  <div className="flex items-center justify-between">
                    <h3 className="font-medium">{article.title}</h3>
                    <span className="text-xs px-2 py-1 rounded-full bg-primary/20 text-primary">
                      {article.status}
                    </span>
                  </div>
                  <p className="text-sm text-foreground/60 mt-2">{article.views} views</p>
                </div>
              ))}
            </div>
          </Card>

          {/* Activity Chart */}
          <Card className="bg-card border border-border p-6">
            <h2 className="text-xl font-semibold mb-6">Activity</h2>
            <ResponsiveContainer width="100%" height={300}>
              <BarChart
                data={[
                  { date: 'Mon', articles: 4, topics: 2 },
                  { date: 'Tue', articles: 6, topics: 1 },
                  { date: 'Wed', articles: 5, topics: 3 },
                  { date: 'Thu', articles: 8, topics: 2 },
                  { date: 'Fri', articles: 7, topics: 4 },
                  { date: 'Sat', articles: 3, topics: 1 },
                  { date: 'Sun', articles: 9, topics: 2 },
                ]}
              >
                <CartesianGrid strokeDasharray="3 3" stroke="#333" />
                <XAxis dataKey="date" stroke="#888" />
                <YAxis stroke="#888" />
                <Tooltip
                  contentStyle={{
                    backgroundColor: '#1a1a1a',
                    border: '1px solid #333',
                    borderRadius: '0.5rem',
                  }}
                />
                <Bar dataKey="articles" fill="var(--color-primary)" />
                <Bar dataKey="topics" fill="var(--color-chart-2)" />
              </BarChart>
            </ResponsiveContainer>
          </Card>
        </div>

        {/* Sidebar */}
        <div className="space-y-6">
          {/* Members */}
          <Card className="bg-card border border-border p-6">
            <div className="flex items-center justify-between mb-6">
              <h2 className="text-lg font-semibold flex items-center gap-2">
                <Users size={18} />
                Members
              </h2>
              <Button size="sm" variant="outline">
                Invite
              </Button>
            </div>
            <div className="space-y-3">
              {membersList.map((member, i) => (
                <div key={i} className="p-3 rounded-lg bg-secondary/30">
                  <p className="font-medium text-sm">{member.name}</p>
                  <p className="text-xs text-foreground/60">{member.role}</p>
                  <p className="text-xs text-foreground/50 mt-1">{member.email}</p>
                </div>
              ))}
            </div>
          </Card>

          {/* Project Settings */}
          <Card className="bg-card border border-border p-6">
            <h2 className="font-semibold mb-4">Project Settings</h2>
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium mb-2">Name</label>
                <Input defaultValue="Blog Platform" />
              </div>
              <div>
                <label className="block text-sm font-medium mb-2">Description</label>
                <textarea
                  className="w-full px-3 py-2 rounded-lg bg-secondary border border-border text-foreground text-sm"
                  rows={3}
                  defaultValue="Main blog content management"
                />
              </div>
              <div>
                <label className="block text-sm font-medium mb-2">Visibility</label>
                <select className="w-full px-3 py-2 rounded-lg bg-secondary border border-border text-foreground text-sm">
                  <option>Private</option>
                  <option>Shared with Team</option>
                </select>
              </div>
              <Button size="sm">Save Changes</Button>
            </div>
          </Card>

          {/* Quick Links */}
          <Card className="bg-secondary/30 border border-border p-6">
            <h2 className="font-semibold mb-4">Quick Actions</h2>
            <div className="space-y-2">
              <Button className="w-full justify-start" variant="ghost" size="sm">
                Archive Project
              </Button>
              <Button className="w-full justify-start text-destructive" variant="ghost" size="sm">
                Delete Project
              </Button>
            </div>
          </Card>
        </div>
      </div>
    </main>
  )
}
