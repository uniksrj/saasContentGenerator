'use client'

import Link from 'next/link'
import { useEffect, useMemo, useState } from 'react'
import {
  ArrowRight,
  Clock3,
  Layers3,
  RefreshCcw,
  Sparkles,
  TrendingUp,
} from 'lucide-react'

import { AppShell } from '@/components/app-shell'
import { Button } from '@/components/ui/button'
import { api, getApiErrorMessage } from '@/lib/api'
import type { Project, Topic } from '@/lib/types'

function formatSource(source?: string | null) {
  if (!source) {
    return 'Mixed'
  }

  return source
    .split('_')
    .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
    .join(' ')
}

function formatStatus(status?: string | null) {
  if (!status) {
    return 'Unknown'
  }

  return status
    .split('_')
    .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
    .join(' ')
}

function getStatusClasses(status?: string | null) {
  switch (status) {
    case 'processed':
      return 'border-emerald-400/30 bg-emerald-400/10 text-emerald-200'
    case 'duplicate':
      return 'border-amber-400/30 bg-amber-400/10 text-amber-200'
    case 'failed':
      return 'border-rose-400/30 bg-rose-400/10 text-rose-200'
    case 'fetched':
      return 'border-cyan-400/30 bg-cyan-400/10 text-cyan-200'
    default:
      return 'border-slate-700 bg-slate-900 text-slate-300'
  }
}

function formatDate(value?: string | null) {
  if (!value) {
    return 'Not available'
  }

  const date = new Date(value)

  if (Number.isNaN(date.getTime())) {
    return value
  }

  return new Intl.DateTimeFormat('en-IN', {
    dateStyle: 'medium',
  }).format(date)
}

export default function TopicsPage() {
  const [projects, setProjects] = useState<Project[]>([])
  const [selectedProjectId, setSelectedProjectId] = useState<number | null>(null)
  const [topics, setTopics] = useState<Topic[]>([])
  const [topicTotal, setTopicTotal] = useState(0)
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
  }

  async function loadTopics(projectId: number, refresh = false) {
    const response = await api.getTopics(projectId, refresh ? { refresh: true, limit: 25 } : undefined)
    setTopics(response.data)
    setTopicTotal(response.total)
  }

  useEffect(() => {
    let active = true

    async function bootstrap() {
      try {
        setLoading(true)
        setError(null)
        await loadProjects()
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
      setTopicTotal(0)
      return
    }

    const projectId = selectedProjectId
    let active = true

    async function syncTopics() {
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

    syncTopics()

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
      setMessage('Topics refreshed from the content pipeline and re-ranked for this project.')
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
      await loadTopics(selectedProjectId)
    } catch (caughtError) {
      setError(getApiErrorMessage(caughtError))
    } finally {
      setGeneratingTopicId(null)
    }
  }

  const selectedProject = projects.find((project) => project.id === selectedProjectId) ?? null

  const summary = useMemo(() => {
    const totalArticles = topics.reduce((sum, topic) => sum + (topic.articles_count ?? 0), 0)
    const totalScore = topics.reduce((sum, topic) => sum + (topic.score ?? 0), 0)
    const averageScore = topics.length > 0 ? Math.round(totalScore / topics.length) : 0
    const readyCount = topics.filter((topic) => ['fetched', 'failed'].includes(topic.status ?? '')).length
    const processedCount = topics.filter((topic) => topic.status === 'processed').length

    return {
      totalArticles,
      averageScore,
      readyCount,
      processedCount,
      topScore: Math.max(...topics.map((topic) => topic.score ?? 0), 0),
    }
  }, [topics])

  if (loading && projects.length === 0) {
    return (
      <AppShell>
        <div className="rounded-3xl border border-slate-800 bg-slate-900/70 p-8 text-slate-200">
          Loading ranked topics...
        </div>
      </AppShell>
    )
  }

  return (
    <AppShell>
      <div className="space-y-6">
        <section className="rounded-[2rem] border border-cyan-400/20 bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.18),_transparent_28%),linear-gradient(135deg,_rgba(15,23,42,0.98),_rgba(8,47,73,0.92))] p-8 shadow-[0_30px_80px_-40px_rgba(34,211,238,0.5)]">
          <div className="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div className="max-w-2xl">
              <p className="text-sm uppercase tracking-[0.28em] text-cyan-300">Topics</p>
              <h2 className="mt-3 text-3xl font-semibold text-white">Discover, rank, and generate from real topic signals</h2>
              <p className="mt-3 text-slate-300">
                This workspace shows generated topics from your connected sources, ranks them by score, and lets you create articles one topic at a time or in a project-wide run.
              </p>
            </div>

            <div className="flex flex-col gap-3 sm:flex-row">
              <select
                value={selectedProjectId ?? ''}
                onChange={(event) => setSelectedProjectId(event.target.value ? Number(event.target.value) : null)}
                className="rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-sm text-slate-100 outline-none transition focus:border-cyan-400"
              >
                <option value="">Select a project</option>
                {projects.map((project) => (
                  <option key={project.id} value={project.id}>
                    {project.name}
                  </option>
                ))}
              </select>

              <Button type="button" variant="outline" onClick={handleRefresh} disabled={refreshing || !selectedProjectId}>
                {refreshing ? 'Refreshing...' : 'Refresh topics'}
              </Button>
              <Button type="button" onClick={handleGenerateProject} disabled={generating || !selectedProjectId}>
                {generating ? 'Generating...' : 'Generate project articles'}
              </Button>
            </div>
          </div>
        </section>

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

        <section className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
          {[
            {
              label: 'Topics loaded',
              value: topicTotal,
              note: selectedProject ? `Available inside ${selectedProject.name}` : 'Choose a project first',
            },
            {
              label: 'Average score',
              value: summary.averageScore,
              note: 'The stronger the score, the stronger the opportunity',
            },
            {
              label: 'Ready to generate',
              value: summary.readyCount,
              note: 'Fetched or failed topics that can still move forward',
            },
            {
              label: 'Articles linked',
              value: summary.totalArticles,
              note: 'Drafts already created from ranked topics',
            },
          ].map((item) => (
            <div key={item.label} className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
              <p className="text-sm text-slate-400">{item.label}</p>
              <p className="mt-3 text-3xl font-semibold text-white">{item.value}</p>
              <p className="mt-2 text-sm text-slate-500">{item.note}</p>
            </div>
          ))}
        </section>

        <section className="grid gap-6 xl:grid-cols-[1.2fr,0.8fr]">
          <div className="rounded-[1.75rem] border border-slate-800 bg-slate-900/75 p-6">
            <div className="flex items-center justify-between gap-4">
              <div>
                <p className="text-sm uppercase tracking-[0.22em] text-cyan-300">Ranked queue</p>
                <h3 className="mt-2 text-2xl font-semibold text-white">Generated topics ready for action</h3>
              </div>
              <div className="rounded-2xl border border-cyan-400/20 bg-cyan-400/10 p-3 text-cyan-200">
                <Layers3 className="h-5 w-5" />
              </div>
            </div>

            <div className="mt-6 space-y-4">
              {!loading && topics.length > 0 ? (
                topics.map((topic) => (
                  <div key={topic.id} className="rounded-3xl border border-slate-800 bg-slate-950/70 p-5">
                    <div className="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                      <div className="min-w-0 flex-1">
                        <div className="flex flex-wrap items-center gap-2">
                          <h4 className="text-lg font-semibold text-white">{topic.title}</h4>
                          <span className={`rounded-full border px-3 py-1 text-xs uppercase tracking-wide ${getStatusClasses(topic.status)}`}>
                            {formatStatus(topic.status)}
                          </span>
                        </div>
                        <p className="mt-3 text-sm leading-6 text-slate-300">
                          {topic.description || 'No description was provided for this topic yet.'}
                        </p>

                        <div className="mt-4 flex flex-wrap gap-2 text-xs text-slate-300">
                          <span className="rounded-full bg-slate-900 px-3 py-1">
                            Source: {formatSource(topic.source_type)}
                          </span>
                          <span className="rounded-full bg-slate-900 px-3 py-1">
                            Score: {topic.score ?? 0}
                          </span>
                          <span className="rounded-full bg-slate-900 px-3 py-1">
                            Articles: {topic.articles_count ?? 0}
                          </span>
                          <span className="rounded-full bg-slate-900 px-3 py-1">
                            Updated: {formatDate(topic.updated_at ?? topic.published_at)}
                          </span>
                        </div>

                        {topic.source_url ? (
                          <a
                            href={topic.source_url}
                            target="_blank"
                            rel="noreferrer"
                            className="mt-4 inline-flex items-center gap-2 text-sm text-cyan-300 transition hover:text-cyan-200"
                          >
                            Open source
                            <ArrowRight className="h-4 w-4" />
                          </a>
                        ) : null}
                      </div>

                      <div className="flex w-full flex-col gap-3 lg:w-56">
                        <div className="rounded-2xl border border-slate-800 bg-slate-900/80 p-4">
                          <p className="text-xs uppercase tracking-wide text-slate-500">Top signal</p>
                          <p className="mt-2 text-3xl font-semibold text-white">{topic.score ?? 0}</p>
                          <p className="mt-1 text-xs text-slate-500">Higher score means stronger ranking confidence.</p>
                        </div>
                        <Button
                          type="button"
                          variant="outline"
                          onClick={() => handleGenerateTopic(topic.id)}
                          disabled={generatingTopicId === topic.id}
                        >
                          {generatingTopicId === topic.id ? 'Generating...' : 'Generate article'}
                        </Button>
                      </div>
                    </div>
                  </div>
                ))
              ) : !loading ? (
                <div className="rounded-3xl border border-dashed border-slate-700 bg-slate-950/50 p-10 text-center text-slate-400">
                  No topics found for this project yet. Refresh topics to fetch and rank opportunities from connected sources.
                </div>
              ) : (
                <div className="rounded-3xl border border-slate-800 bg-slate-950/50 p-10 text-center text-slate-300">
                  Loading topics for the selected project...
                </div>
              )}
            </div>
          </div>

          <div className="space-y-6">
            <div className="rounded-[1.75rem] border border-slate-800 bg-slate-900/75 p-6">
              <div className="flex items-center gap-3">
                <TrendingUp className="h-5 w-5 text-cyan-300" />
                <p className="text-sm uppercase tracking-[0.22em] text-cyan-300">Project snapshot</p>
              </div>
              <h3 className="mt-3 text-2xl font-semibold text-white">
                {selectedProject?.name ?? 'No project selected'}
              </h3>
              <p className="mt-3 text-sm leading-6 text-slate-400">
                {selectedProject?.description ?? 'Choose a project to inspect ranked topics, source signals, and generation opportunities.'}
              </p>

              <div className="mt-5 space-y-4 text-sm text-slate-300">
                <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
                  <p className="text-slate-400">Project status</p>
                  <p className="mt-1 font-medium capitalize text-white">{selectedProject?.status ?? 'Not selected'}</p>
                </div>
                <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
                  <p className="text-slate-400">Processed topics</p>
                  <p className="mt-1 font-medium text-white">{summary.processedCount}</p>
                </div>
                <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
                  <p className="text-slate-400">Best score in view</p>
                  <p className="mt-1 font-medium text-white">{summary.topScore}</p>
                </div>
              </div>
            </div>

            <div className="rounded-[1.75rem] border border-slate-800 bg-[linear-gradient(180deg,rgba(15,23,42,0.98),rgba(12,74,110,0.9))] p-6">
              <div className="flex items-center gap-3">
                <Clock3 className="h-5 w-5 text-cyan-200" />
                <p className="text-sm uppercase tracking-[0.22em] text-cyan-200">Workflow</p>
              </div>
              <div className="mt-4 space-y-3 text-sm text-slate-200">
                {[
                  'Refresh topics from connected platforms.',
                  'Review score, source, and topic description.',
                  'Generate one article or run the whole project.',
                  'Open Activity to see who did what and when.',
                ].map((step) => (
                  <div key={step} className="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    {step}
                  </div>
                ))}
              </div>

              <Link
                href="/activity"
                className="mt-6 inline-flex items-center gap-2 rounded-2xl bg-cyan-300 px-4 py-3 text-sm font-medium text-slate-950 transition hover:bg-cyan-200"
              >
                Open activity history
                <ArrowRight className="h-4 w-4" />
              </Link>
            </div>

            <div className="rounded-[1.75rem] border border-slate-800 bg-slate-900/75 p-6">
              <div className="flex items-center gap-3">
                <RefreshCcw className="h-5 w-5 text-emerald-300" />
                <p className="text-sm uppercase tracking-[0.22em] text-emerald-300">Ownership</p>
              </div>
              <p className="mt-3 text-sm leading-6 text-slate-300">
                Topics shown here are scoped to the signed-in user and selected project. Activity history records refreshes, generation runs, article creation, and token usage for that user.
              </p>
            </div>
          </div>
        </section>
      </div>
    </AppShell>
  )
}
