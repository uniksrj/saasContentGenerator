'use client'

import { useEffect, useState } from 'react'

import { AppShell } from '@/components/app-shell'
import { Button } from '@/components/ui/button'
import { Card } from '@/components/ui/card'
import { api, getApiErrorMessage } from '@/lib/api'
import type { Project, Topic } from '@/lib/types'

export default function TopicsPage() {
  const [projects, setProjects] = useState<Project[]>([])
  const [selectedProjectId, setSelectedProjectId] = useState<number | null>(null)
  const [topics, setTopics] = useState<Topic[]>([])
  const [loading, setLoading] = useState(true)
  const [refreshing, setRefreshing] = useState(false)
  const [generating, setGenerating] = useState(false)
  const [generatingTopicId, setGeneratingTopicId] = useState<number | null>(null)
  const [message, setMessage] = useState<string | null>(null)
  const [error, setError] = useState<string | null>(null)

  async function loadProjects() {
    const response = await api.getProjects()
    setProjects(response.data)
    setSelectedProjectId((current) => current ?? response.data[0]?.id ?? null)
    return response.data
  }

  async function loadTopics(projectId: number, refresh = false) {
    const response = await api.getTopics(projectId, refresh ? { refresh: true, limit: 25 } : undefined)
    setTopics(response.data)
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
          await loadTopics(initialProjectId)
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
      setTopics([])
      return
    }

    const projectId = selectedProjectId
    let active = true

    async function refreshSelection() {
      try {
        setLoading(true)
        setError(null)
        await loadTopics(projectId)
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

    refreshSelection()

    return () => {
      active = false
    }
  }, [selectedProjectId])

  async function handleRefresh() {
    if (!selectedProjectId) {
      return
    }

    try {
      setRefreshing(true)
      setMessage(null)
      setError(null)
      await loadTopics(selectedProjectId, true)
      setMessage('Topics refreshed from the Laravel content pipeline.')
    } catch (caughtError) {
      setError(getApiErrorMessage(caughtError))
    } finally {
      setRefreshing(false)
    }
  }

  async function handleGenerateProject() {
    if (!selectedProjectId) {
      return
    }

    try {
      setGenerating(true)
      setMessage(null)
      setError(null)
      const response = await api.generateProjectArticles(selectedProjectId)
      setMessage(response.message)
      await loadTopics(selectedProjectId)
    } catch (caughtError) {
      setError(getApiErrorMessage(caughtError))
    } finally {
      setGenerating(false)
    }
  }

  async function handleGenerateTopic(topicId: number) {
    if (!selectedProjectId) {
      return
    }

    try {
      setGeneratingTopicId(topicId)
      setMessage(null)
      setError(null)
      const response = await api.generateTopicArticle(selectedProjectId, topicId)
      setMessage(response.message)
    } catch (caughtError) {
      setError(getApiErrorMessage(caughtError))
    } finally {
      setGeneratingTopicId(null)
    }
  }

  return (
    <AppShell>
      <div className="space-y-6">
        <div className="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
          <div>
            <p className="text-sm uppercase tracking-[0.25em] text-cyan-300">Topics</p>
            <h2 className="text-3xl font-semibold text-white">Pipeline and generation</h2>
            <p className="mt-2 text-slate-400">
              Pull fresh topics from Laravel and generate articles for an entire project or a single topic.
            </p>
          </div>

          <div className="flex flex-col gap-3 sm:flex-row">
            <select
              value={selectedProjectId ?? ''}
              onChange={(event) => setSelectedProjectId(Number(event.target.value))}
              className="rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-sm text-slate-50"
            >
              {projects.map((project) => (
                <option key={project.id} value={project.id}>
                  {project.name}
                </option>
              ))}
            </select>
            <Button type="button" variant="outline" onClick={handleRefresh} disabled={refreshing || !selectedProjectId}>
              {refreshing ? 'Refreshing...' : 'Refresh Topics'}
            </Button>
            <Button type="button" onClick={handleGenerateProject} disabled={generating || !selectedProjectId}>
              {generating ? 'Generating...' : 'Generate Articles'}
            </Button>
          </div>
        </div>

        {message ? (
          <div className="rounded-2xl border border-cyan-400/30 bg-cyan-400/10 px-4 py-3 text-sm text-cyan-100">
            {message}
          </div>
        ) : null}

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
                  <th className="px-6 py-4">Topic</th>
                  <th className="px-6 py-4">Source</th>
                  <th className="px-6 py-4">Score</th>
                  <th className="px-6 py-4">Articles</th>
                  <th className="px-6 py-4">Action</th>
                </tr>
              </thead>
              <tbody>
                {topics.map((topic) => (
                  <tr key={topic.id} className="border-t border-slate-800 text-sm text-slate-200">
                    <td className="px-6 py-4">
                      <p className="font-medium text-white">{topic.title}</p>
                      <p className="mt-1 text-xs text-slate-400">{topic.description || 'No description available'}</p>
                    </td>
                    <td className="px-6 py-4 text-slate-400">{topic.source_type || 'mixed'}</td>
                    <td className="px-6 py-4">
                      <span className="rounded-full bg-cyan-400/10 px-3 py-1 text-xs text-cyan-200">
                        {topic.score ?? 0}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-slate-400">{topic.articles_count ?? 0}</td>
                    <td className="px-6 py-4">
                      <Button
                        type="button"
                        size="sm"
                        variant="outline"
                        onClick={() => handleGenerateTopic(topic.id)}
                        disabled={generatingTopicId === topic.id}
                      >
                        {generatingTopicId === topic.id ? 'Generating...' : 'Generate'}
                      </Button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </Card>

        {!loading && topics.length === 0 ? (
          <Card className="rounded-3xl border border-dashed border-slate-700 bg-slate-900/40 p-10 text-center text-slate-300">
            No topics found for this project yet. Use “Refresh Topics” to fetch them from the backend.
          </Card>
        ) : null}
      </div>
    </AppShell>
  )
}
