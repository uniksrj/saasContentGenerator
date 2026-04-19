'use client'

import { useEffect, useState } from 'react'

import { AppShell } from '@/components/app-shell'
import { Button } from '@/components/ui/button'
import { Card } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { api, getApiErrorMessage } from '@/lib/api'
import type { Project } from '@/lib/types'

export default function ProjectsPage() {
  const [projects, setProjects] = useState<Project[]>([])
  const [name, setName] = useState('')
  const [description, setDescription] = useState('')
  const [loading, setLoading] = useState(true)
  const [submitting, setSubmitting] = useState(false)
  const [error, setError] = useState<string | null>(null)

  async function loadProjects() {
    try {
      setLoading(true)
      setError(null)
      const response = await api.getProjects()
      setProjects(response.data)
    } catch (caughtError) {
      setError(getApiErrorMessage(caughtError))
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    loadProjects()
  }, [])

  async function handleSubmit(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault()

    try {
      setSubmitting(true)
      setError(null)

      const response = await api.createProject({
        name,
        description: description || undefined,
      })

      setProjects((currentProjects) => [response.data, ...currentProjects])
      setName('')
      setDescription('')
    } catch (caughtError) {
      setError(getApiErrorMessage(caughtError))
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <AppShell>
      <div className="space-y-6">
        <div className="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <p className="text-sm uppercase tracking-[0.25em] text-cyan-300">Projects</p>
            <h2 className="text-3xl font-semibold text-white">Manage workspaces</h2>
            <p className="mt-2 text-slate-400">
              This page is reading and creating projects through Laravel `/api/projects`.
            </p>
          </div>

          <Button type="button" variant="outline" onClick={loadProjects} disabled={loading}>
            {loading ? 'Refreshing...' : 'Refresh'}
          </Button>
        </div>

        <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
          <h3 className="text-xl font-semibold text-white">Create a project</h3>
          <form onSubmit={handleSubmit} className="mt-5 grid gap-4 lg:grid-cols-[1fr,1fr,auto]">
            <Input
              value={name}
              onChange={(event) => setName(event.target.value)}
              placeholder="Project name"
              required
              className="border-slate-700 bg-slate-950 text-slate-50"
            />
            <Input
              value={description}
              onChange={(event) => setDescription(event.target.value)}
              placeholder="Description (optional)"
              className="border-slate-700 bg-slate-950 text-slate-50"
            />
            <Button type="submit" disabled={submitting}>
              {submitting ? 'Creating...' : 'Create Project'}
            </Button>
          </form>

          {error ? (
            <div className="mt-4 rounded-2xl border border-rose-500/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
              {error}
            </div>
          ) : null}
        </Card>

        <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
          {projects.map((project) => (
            <Card key={project.id} className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
              <div className="flex items-start justify-between gap-4">
                <div>
                  <h3 className="text-xl font-semibold text-white">{project.name}</h3>
                  <p className="mt-2 text-sm text-slate-400">
                    {project.description || 'No description provided.'}
                  </p>
                </div>
                <span className="rounded-full bg-cyan-400/10 px-3 py-1 text-xs uppercase tracking-wide text-cyan-200">
                  {project.is_active ? 'Active' : 'Inactive'}
                </span>
              </div>

              <div className="mt-6 grid grid-cols-2 gap-4">
                <div className="rounded-2xl bg-slate-950/70 p-4">
                  <p className="text-sm text-slate-400">Topics</p>
                  <p className="mt-2 text-2xl font-semibold text-white">
                    {project.topics_count ?? 0}
                  </p>
                </div>
                <div className="rounded-2xl bg-slate-950/70 p-4">
                  <p className="text-sm text-slate-400">Articles</p>
                  <p className="mt-2 text-2xl font-semibold text-white">
                    {project.articles_count ?? 0}
                  </p>
                </div>
              </div>
            </Card>
          ))}
        </div>

        {!loading && projects.length === 0 ? (
          <Card className="rounded-3xl border border-dashed border-slate-700 bg-slate-900/40 p-10 text-center text-slate-300">
            No projects yet. Create your first project to start syncing data into the dashboard.
          </Card>
        ) : null}
      </div>
    </AppShell>
  )
}
