'use client'

import { useEffect, useMemo, useState } from 'react'
import {
  BookOpenText,
  Clock3,
  Copy,
  Eye,
  FileText,
  Layers3,
} from 'lucide-react'

import { AppShell } from '@/components/app-shell'
import { Button } from '@/components/ui/button'
import { Card } from '@/components/ui/card'
import { api, getApiErrorMessage } from '@/lib/api'
import type { Article, Project } from '@/lib/types'

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
    timeStyle: 'short',
  }).format(date)
}

function formatStatus(status: string) {
  return status.charAt(0).toUpperCase() + status.slice(1)
}

function statusTone(status: string) {
  if (status === 'published') {
    return 'border-emerald-400/30 bg-emerald-400/10 text-emerald-200'
  }

  return 'border-cyan-400/30 bg-cyan-400/10 text-cyan-200'
}

export default function ArticlesPage() {
  const [projects, setProjects] = useState<Project[]>([])
  const [selectedProjectId, setSelectedProjectId] = useState<number | null>(null)
  const [status, setStatus] = useState('all')
  const [articles, setArticles] = useState<Article[]>([])
  const [selectedArticleId, setSelectedArticleId] = useState<number | null>(null)
  const [loading, setLoading] = useState(true)
  const [copying, setCopying] = useState(false)
  const [message, setMessage] = useState<string | null>(null)
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
    setSelectedArticleId((current) => {
      const currentStillExists = response.data.some((article) => article.id === current)
      return currentStillExists ? current : (response.data[0]?.id ?? null)
    })
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
      setSelectedArticleId(null)
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

  async function handleCopyContent(article: Article) {
    if (!article.content) {
      setError('This article does not have content to copy yet.')
      return
    }

    try {
      setCopying(true)
      setMessage(null)
      setError(null)
      await navigator.clipboard.writeText(article.content)
      setMessage('Article content copied. You can now paste it into your editor, CMS, or publishing workflow.')
    } catch (caughtError) {
      setError(getApiErrorMessage(caughtError))
    } finally {
      setCopying(false)
    }
  }

  const selectedArticle = articles.find((article) => article.id === selectedArticleId) ?? null
  const selectedProject = projects.find((project) => project.id === selectedProjectId) ?? null

  const summary = useMemo(() => {
    const publishedCount = articles.filter((article) => article.status === 'published').length
    const draftCount = articles.filter((article) => article.status === 'draft').length
    const totalWords = articles.reduce((sum, article) => sum + (article.word_count ?? 0), 0)

    return {
      publishedCount,
      draftCount,
      totalWords,
    }
  }, [articles])

  return (
    <AppShell>
      <div className="space-y-6">
        <section className="rounded-[2rem] border border-cyan-400/20 bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.18),_transparent_30%),linear-gradient(135deg,_rgba(15,23,42,0.98),_rgba(8,47,73,0.9))] p-8 shadow-[0_30px_80px_-40px_rgba(34,211,238,0.45)]">
          <div className="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div className="max-w-2xl">
              <p className="text-sm uppercase tracking-[0.28em] text-cyan-300">Articles</p>
              <h2 className="mt-3 text-3xl font-semibold text-white">Read, review, and use the full generated content</h2>
              <p className="mt-3 text-slate-300">
                Users can now inspect the complete article body, review topic context, and copy the generated content into a CMS, blog editor, or publishing workflow.
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

              <select
                value={status}
                onChange={(event) => setStatus(event.target.value)}
                className="rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-sm text-slate-100 outline-none transition focus:border-cyan-400"
              >
                <option value="all">All statuses</option>
                <option value="draft">Draft</option>
                <option value="published">Published</option>
              </select>
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
          <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <div className="flex items-center justify-between">
              <p className="text-sm text-slate-400">Loaded articles</p>
              <Layers3 className="h-5 w-5 text-cyan-300" />
            </div>
            <p className="mt-3 text-3xl font-semibold text-white">{articles.length}</p>
            <p className="mt-2 text-sm text-slate-500">
              {selectedProject ? `Visible inside ${selectedProject.name}` : 'Choose a project first'}
            </p>
          </Card>

          <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <div className="flex items-center justify-between">
              <p className="text-sm text-slate-400">Drafts</p>
              <FileText className="h-5 w-5 text-amber-300" />
            </div>
            <p className="mt-3 text-3xl font-semibold text-white">{summary.draftCount}</p>
            <p className="mt-2 text-sm text-slate-500">Ready for editing, review, or publishing.</p>
          </Card>

          <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <div className="flex items-center justify-between">
              <p className="text-sm text-slate-400">Published</p>
              <Eye className="h-5 w-5 text-emerald-300" />
            </div>
            <p className="mt-3 text-3xl font-semibold text-white">{summary.publishedCount}</p>
            <p className="mt-2 text-sm text-slate-500">Already moved into published status.</p>
          </Card>

          <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <div className="flex items-center justify-between">
              <p className="text-sm text-slate-400">Words available</p>
              <BookOpenText className="h-5 w-5 text-violet-300" />
            </div>
            <p className="mt-3 text-3xl font-semibold text-white">{summary.totalWords.toLocaleString()}</p>
            <p className="mt-2 text-sm text-slate-500">Total generated content available across the filtered list.</p>
          </Card>
        </section>

        <section className="grid gap-6 xl:grid-cols-[0.9fr,1.1fr]">
          <Card className="rounded-[1.75rem] border border-slate-800 bg-slate-900/75 p-6">
            <div className="flex items-center justify-between gap-4">
              <div>
                <p className="text-sm uppercase tracking-[0.22em] text-cyan-300">Article list</p>
                <h3 className="mt-2 text-2xl font-semibold text-white">Choose an article to inspect</h3>
              </div>
            </div>

            <div className="mt-6 space-y-4">
              {!loading && articles.length > 0 ? (
                articles.map((article) => {
                  const selected = article.id === selectedArticleId

                  return (
                    <button
                      key={article.id}
                      type="button"
                      onClick={() => setSelectedArticleId(article.id)}
                      className={`w-full rounded-3xl border p-5 text-left transition ${
                        selected
                          ? 'border-cyan-400/50 bg-cyan-400/10'
                          : 'border-slate-800 bg-slate-950/70 hover:border-slate-700'
                      }`}
                    >
                      <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div className="min-w-0 flex-1">
                          <p className="text-lg font-semibold text-white">{article.title}</p>
                          <p className="mt-2 text-sm text-slate-400">
                            {article.topic?.title ?? 'Unassigned topic'}
                          </p>
                          <div className="mt-3 flex flex-wrap gap-2 text-xs text-slate-300">
                            <span className={`rounded-full border px-3 py-1 ${statusTone(article.status)}`}>
                              {formatStatus(article.status)}
                            </span>
                            <span className="rounded-full bg-slate-900 px-3 py-1">
                              {article.category || 'General'}
                            </span>
                            <span className="rounded-full bg-slate-900 px-3 py-1">
                              {article.reading_time ? `${article.reading_time} min read` : 'Reading time N/A'}
                            </span>
                          </div>
                        </div>
                        <div className="text-right text-xs text-slate-500">
                          {formatDate(article.updated_at ?? article.created_at)}
                        </div>
                      </div>
                    </button>
                  )
                })
              ) : !loading ? (
                <div className="rounded-3xl border border-dashed border-slate-700 bg-slate-950/50 p-10 text-center text-slate-400">
                  No articles found for this project and filter yet.
                </div>
              ) : (
                <div className="rounded-3xl border border-slate-800 bg-slate-950/50 p-10 text-center text-slate-300">
                  Loading articles...
                </div>
              )}
            </div>
          </Card>

          <Card className="rounded-[1.75rem] border border-slate-800 bg-slate-900/75 p-6">
            <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
              <div>
                <p className="text-sm uppercase tracking-[0.22em] text-cyan-300">Full content</p>
                <h3 className="mt-2 text-2xl font-semibold text-white">
                  {selectedArticle?.title ?? 'Select an article'}
                </h3>
                <p className="mt-2 text-sm text-slate-400">
                  {selectedArticle?.topic?.title ?? 'Choose an article from the left to read the full generated content.'}
                </p>
              </div>

              {selectedArticle ? (
                <Button type="button" variant="outline" onClick={() => handleCopyContent(selectedArticle)} disabled={copying}>
                  <Copy className="mr-2 h-4 w-4" />
                  {copying ? 'Copying...' : 'Copy content'}
                </Button>
              ) : null}
            </div>

            {selectedArticle ? (
              <div className="mt-6 space-y-6">
                <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                  <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
                    <p className="text-sm text-slate-400">Status</p>
                    <p className="mt-2 font-medium text-white">{formatStatus(selectedArticle.status)}</p>
                  </div>
                  <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
                    <p className="text-sm text-slate-400">Category</p>
                    <p className="mt-2 font-medium text-white">{selectedArticle.category || 'General'}</p>
                  </div>
                  <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
                    <p className="text-sm text-slate-400">Reading time</p>
                    <p className="mt-2 font-medium text-white">
                      {selectedArticle.reading_time ? `${selectedArticle.reading_time} min` : 'N/A'}
                    </p>
                  </div>
                  <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
                    <p className="text-sm text-slate-400">Word count</p>
                    <p className="mt-2 font-medium text-white">
                      {selectedArticle.word_count?.toLocaleString() ?? 'N/A'}
                    </p>
                  </div>
                </div>

                {selectedArticle.meta_description ? (
                  <div className="rounded-3xl border border-slate-800 bg-slate-950/70 p-5">
                    <p className="text-sm uppercase tracking-[0.18em] text-slate-500">Meta description</p>
                    <p className="mt-3 text-sm leading-6 text-slate-300">{selectedArticle.meta_description}</p>
                  </div>
                ) : null}

                <div className="rounded-3xl border border-slate-800 bg-slate-950/70 p-6">
                  <div className="mb-4 flex items-center gap-2 text-sm text-slate-400">
                    <Clock3 className="h-4 w-4" />
                    <span>Last updated: {formatDate(selectedArticle.updated_at ?? selectedArticle.created_at)}</span>
                  </div>
                  <article
                    className="prose prose-invert max-w-none prose-headings:text-white prose-p:text-slate-200 prose-strong:text-white prose-a:text-cyan-300"
                    dangerouslySetInnerHTML={{ __html: selectedArticle.content || '<p>No content available yet.</p>' }}
                  />
                </div>

                <div className="rounded-3xl border border-slate-800 bg-[linear-gradient(180deg,rgba(15,23,42,0.96),rgba(8,47,73,0.88))] p-5">
                  <p className="text-sm uppercase tracking-[0.18em] text-cyan-300">How to use this content</p>
                  <div className="mt-4 space-y-3 text-sm text-slate-200">
                    <p>Read the full article here before publishing or editing.</p>
                    <p>Use the `Copy content` button to paste it into WordPress, a CMS, Google Docs, or your editor.</p>
                    <p>Review title, topic, category, and reading time before sending it to the final publishing workflow.</p>
                  </div>
                </div>
              </div>
            ) : (
              <div className="mt-6 rounded-3xl border border-dashed border-slate-700 bg-slate-950/50 p-10 text-center text-slate-400">
                Select an article from the list to read and use the full generated content.
              </div>
            )}
          </Card>
        </section>
      </div>
    </AppShell>
  )
}
