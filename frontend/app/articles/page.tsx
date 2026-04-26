'use client'

import { useEffect, useState } from 'react'

import { AppShell } from '@/components/app-shell'
import { Card } from '@/components/ui/card'
import { api, getApiErrorMessage } from '@/lib/api'
import type { Article, Project } from '@/lib/types'

export default function ArticlesPage() {
  const [projects, setProjects] = useState<Project[]>([])
  const [selectedProjectId, setSelectedProjectId] = useState<number | null>(null)
  const [status, setStatus] = useState('all')
  const [articles, setArticles] = useState<Article[]>([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)

  async function loadProjects() {
    const response = await api.getProjects()
    setProjects(response.data)
    setSelectedProjectId((current) => current ?? response.data[0]?.id ?? null)
    return response.data
  }

  async function loadArticles(projectId: number, nextStatus: string) {
    const response = await api.getArticles(projectId, nextStatus === 'all' ? undefined : { status: nextStatus })
    setArticles(response.data)
  }

  useEffect(() => {
    let active = true

    async function bootstrap() {
      try {
        setLoading(true)
        setError(null)
        const loadedProjects = await loadProjects()
        const initialProjectId = loadedProjects[0]?.id

        if (active && initialProjectId) {
          await loadArticles(initialProjectId, status)
        }
      } catch (caughtError) {
        if (active) {
          setError(getApiErrorMessage(caughtError))
        }
      } finally {
        if (active) {
          setLoading(false)
        }
      }
    }

    bootstrap()

    return () => {
      active = false
    }
  }, [])

  useEffect(() => {
    if (!selectedProjectId) {
      setArticles([])
      return
    }

    const projectId = selectedProjectId
    let active = true

    async function refreshArticles() {
      try {
        setLoading(true)
        setError(null)
        await loadArticles(projectId, status)
      } catch (caughtError) {
        if (active) {
          setError(getApiErrorMessage(caughtError))
        }
      } finally {
        if (active) {
          setLoading(false)
        }
      }
    }

    refreshArticles()

    return () => {
      active = false
    }
  }, [selectedProjectId, status])

  return (
    <AppShell>
      <div className="space-y-6">
        <div className="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
          <div>
            <p className="text-sm uppercase tracking-[0.25em] text-cyan-300">Articles</p>
            <h2 className="text-3xl font-semibold text-white">Published and draft content</h2>
            <p className="mt-2 text-slate-400">
              Articles are loaded directly from Laravel `/api/articles/{'{project}'}` with optional status filtering.
            </p>
          </div>

          <div className="flex flex-col gap-3 sm:flex-row">
            {/* Project Select */}
            <div className="relative w-full sm:w-auto">
              <select
                value={selectedProjectId ?? ''}
                onChange={(event) => {
                  const value = event.target.value;
                  setSelectedProjectId(value ? Number(value) : null);
                }}
                className="w-full appearance-none rounded-sm border border-slate-700 bg-slate-900 px-4 py-2 pr-10 text-sm text-slate-100 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="">Select a project</option>

                {projects?.map((project) => (
                  <option key={project.id} value={project.id}>
                    {project.name}
                  </option>
                ))}
              </select>

              {/* Arrow */}
              <span className="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                ▼
              </span>
            </div>

            {/* Status Select */}
            <div className="relative w-full sm:w-auto">
              <select
                value={status}
                onChange={(event) => setStatus(event.target.value)}
                className="w-full appearance-none rounded-sm border border-slate-700 bg-slate-900 px-4 py-2 pr-10 text-sm text-slate-100 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="all">All statuses</option>
                <option value="draft">Draft</option>
                <option value="published">Published</option>
              </select>

              {/* Arrow */}
              <span className="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                ▼
              </span>
            </div>
          </div>
        </div>

        {error ? (
          <div className="rounded-2xl border border-rose-500/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
            {error}
          </div>
        ) : null}

        <Card className="overflow-hidden rounded-3xl border border-slate-800 bg-slate-900/70">
          <div className="overflow-x-auto">
            <table className="min-w-full">
              <thead className="bg-slate-950/80 text-left text-sm text-slate-300">
                <tr>
                  <th className="px-6 py-4">Title</th>
                  <th className="px-6 py-4">Topic</th>
                  <th className="px-6 py-4">Status</th>
                  <th className="px-6 py-4">Category</th>
                  <th className="px-6 py-4">Reading time</th>
                </tr>
              </thead>
              <tbody>
                {articles.map((article) => (
                  <tr key={article.id} className="border-t border-slate-800 text-sm text-slate-200">
                    <td className="px-6 py-4 font-medium text-white">{article.title}</td>
                    <td className="px-6 py-4 text-slate-400">{article.topic?.title || 'Unassigned'}</td>
                    <td className="px-6 py-4">
                      <span className="rounded-full bg-slate-800 px-3 py-1 text-xs uppercase tracking-wide text-slate-300">
                        {article.status}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-slate-400">{article.category || 'General'}</td>
                    <td className="px-6 py-4 text-slate-400">
                      {article.reading_time ? `${article.reading_time} min` : 'N/A'}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </Card>

        {!loading && articles.length === 0 ? (
          <Card className="rounded-3xl border border-dashed border-slate-700 bg-slate-900/40 p-10 text-center text-slate-300">
            No articles found for this project and filter yet.
          </Card>
        ) : null}
      </div>
    </AppShell>
  )
}
