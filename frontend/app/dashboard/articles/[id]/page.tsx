'use client'

import { Card } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { ArrowLeft, Save, Eye, Share2 } from 'lucide-react'
import { useState } from 'react'

export default function ArticleEditorPage() {
  const [title, setTitle] = useState('Complete Guide to AI Writing Tools')
  const [content, setContent] = useState(
    'AI writing tools are revolutionizing how content creators work...'
  )

  return (
    <main className="p-6 space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div className="flex items-center gap-4">
          <button className="p-2 hover:bg-secondary/50 rounded transition">
            <ArrowLeft size={20} />
          </button>
          <div>
            <h1 className="text-3xl font-bold">Edit Article</h1>
            <p className="text-foreground/70 mt-1">Last saved 5 minutes ago</p>
          </div>
        </div>
        <div className="flex gap-2">
          <Button variant="outline" size="sm" className="gap-2">
            <Eye size={18} />
            Preview
          </Button>
          <Button size="sm" className="gap-2">
            <Save size={18} />
            Save Draft
          </Button>
        </div>
      </div>

      <div className="grid lg:grid-cols-3 gap-6">
        {/* Editor */}
        <div className="lg:col-span-2 space-y-6">
          <Card className="bg-card border border-border p-8">
            <div className="space-y-6">
              <div>
                <label className="block text-sm font-medium mb-2">Title</label>
                <Input
                  value={title}
                  onChange={(e) => setTitle(e.target.value)}
                  placeholder="Article title..."
                  className="text-xl"
                />
              </div>

              <div>
                <label className="block text-sm font-medium mb-2">Slug</label>
                <Input
                  defaultValue="complete-guide-to-ai-writing-tools"
                  placeholder="article-slug..."
                />
              </div>

              <div>
                <label className="block text-sm font-medium mb-2">Content</label>
                <textarea
                  value={content}
                  onChange={(e) => setContent(e.target.value)}
                  placeholder="Write your article content here..."
                  className="w-full px-4 py-3 rounded-lg bg-secondary border border-border text-foreground font-mono text-sm"
                  rows={16}
                />
              </div>

              <div className="flex gap-2">
                <Button variant="outline" className="flex-1">
                  Format
                </Button>
                <Button variant="outline" className="flex-1">
                  Add Media
                </Button>
                <Button variant="outline" className="flex-1">
                  Add Link
                </Button>
              </div>
            </div>
          </Card>
        </div>

        {/* Sidebar */}
        <div className="space-y-6">
          {/* Status */}
          <Card className="bg-card border border-border p-6">
            <h2 className="font-semibold mb-4">Status</h2>
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium mb-2">Publication Status</label>
                <select className="w-full px-4 py-2 rounded-lg bg-secondary border border-border text-foreground">
                  <option>Draft</option>
                  <option>In Review</option>
                  <option>Published</option>
                  <option>Archived</option>
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium mb-2">Topic</label>
                <select className="w-full px-4 py-2 rounded-lg bg-secondary border border-border text-foreground">
                  <option>AI in Content Creation</option>
                  <option>SEO Best Practices</option>
                </select>
              </div>
            </div>
          </Card>

          {/* SEO */}
          <Card className="bg-card border border-border p-6">
            <h2 className="font-semibold mb-4">SEO Settings</h2>
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium mb-2">Meta Description</label>
                <textarea
                  className="w-full px-3 py-2 rounded-lg bg-secondary border border-border text-foreground text-sm"
                  rows={3}
                  placeholder="SEO meta description (160 chars max)"
                />
              </div>
              <div>
                <label className="block text-sm font-medium mb-2">Keywords</label>
                <Input placeholder="ai, writing, content" />
              </div>
            </div>
          </Card>

          {/* Actions */}
          <Card className="bg-card border border-border p-6">
            <h2 className="font-semibold mb-4">Actions</h2>
            <div className="space-y-2">
              <Button className="w-full justify-start" variant="outline">
                <Eye size={18} className="mr-2" />
                Preview
              </Button>
              <Button className="w-full justify-start" variant="outline">
                <Share2 size={18} className="mr-2" />
                Share
              </Button>
              <Button className="w-full justify-start">Publish Now</Button>
            </div>
          </Card>

          {/* Article Stats */}
          <Card className="bg-secondary/30 border border-border p-4">
            <h3 className="font-semibold text-sm mb-3">Writing Stats</h3>
            <div className="space-y-2 text-sm">
              <div className="flex justify-between">
                <span className="text-foreground/60">Words</span>
                <span className="font-semibold">342</span>
              </div>
              <div className="flex justify-between">
                <span className="text-foreground/60">Reading Time</span>
                <span className="font-semibold">2 min</span>
              </div>
              <div className="flex justify-between">
                <span className="text-foreground/60">Readability</span>
                <span className="font-semibold text-green-400">Good</span>
              </div>
            </div>
          </Card>
        </div>
      </div>
    </main>
  )
}
