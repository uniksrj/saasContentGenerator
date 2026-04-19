import { Card } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { MoreVertical, Plus, Edit2, Trash2 } from 'lucide-react'

const projects = [
  {
    id: 1,
    name: 'Blog Platform',
    description: 'Main blog content management',
    topics: 12,
    articles: 45,
    members: 3,
    created: '2 months ago',
  },
  {
    id: 2,
    name: 'Marketing Campaign',
    description: 'Q1 2026 marketing initiatives',
    topics: 8,
    articles: 23,
    members: 2,
    created: '1 month ago',
  },
  {
    id: 3,
    name: 'Product Documentation',
    description: 'Internal documentation and guides',
    topics: 5,
    articles: 18,
    members: 4,
    created: '3 weeks ago',
  },
  {
    id: 4,
    name: 'Newsletter Content',
    description: 'Weekly newsletter articles and ideas',
    topics: 15,
    articles: 52,
    members: 2,
    created: '2 weeks ago',
  },
]

export default function ProjectsPage() {
  return (
    <main className="p-6 space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold mb-2">Projects</h1>
          <p className="text-foreground/70">Manage your content projects</p>
        </div>
        <Button className="gap-2">
          <Plus size={18} />
          New Project
        </Button>
      </div>

      {/* Search and Filter */}
      <div className="flex gap-4">
        <Input placeholder="Search projects..." className="max-w-md" />
        <Button variant="outline">Filter</Button>
      </div>

      {/* Projects Grid */}
      <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        {projects.map((project) => (
          <Card
            key={project.id}
            className="bg-card border border-border p-6 hover:border-primary/50 transition cursor-pointer group"
          >
            <div className="flex items-start justify-between mb-4">
              <div>
                <h3 className="text-lg font-semibold mb-1">{project.name}</h3>
                <p className="text-sm text-foreground/60">{project.description}</p>
              </div>
              <button className="opacity-0 group-hover:opacity-100 transition">
                <MoreVertical size={18} className="text-foreground/60" />
              </button>
            </div>

            <div className="grid grid-cols-3 gap-4 py-4 border-t border-border">
              <div>
                <p className="text-2xl font-bold">{project.topics}</p>
                <p className="text-xs text-foreground/60">Topics</p>
              </div>
              <div>
                <p className="text-2xl font-bold">{project.articles}</p>
                <p className="text-xs text-foreground/60">Articles</p>
              </div>
              <div>
                <p className="text-2xl font-bold">{project.members}</p>
                <p className="text-xs text-foreground/60">Members</p>
              </div>
            </div>

            <p className="text-xs text-foreground/50 mt-4">Created {project.created}</p>

            <div className="flex gap-2 mt-4">
              <Button variant="outline" size="sm" className="flex-1">
                <Edit2 size={16} />
              </Button>
              <Button variant="outline" size="sm" className="flex-1">
                <Trash2 size={16} />
              </Button>
            </div>
          </Card>
        ))}
      </div>

      {/* Empty State Alternative */}
      {projects.length === 0 && (
        <div className="text-center py-20">
          <div className="text-6xl mb-4">📁</div>
          <h3 className="text-2xl font-semibold mb-2">No projects yet</h3>
          <p className="text-foreground/60 mb-6">Create your first project to start managing content</p>
          <Button size="lg">Create First Project</Button>
        </div>
      )}
    </main>
  )
}
