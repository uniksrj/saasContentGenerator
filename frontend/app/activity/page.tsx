'use client'

import { useEffect, useState } from 'react'
import {
  Activity,
  Clock3,
  FileText,
  RefreshCcw,
  Sparkles,
  Workflow,
} from 'lucide-react'

import { AppShell } from '@/components/app-shell'
import { api, getApiErrorMessage } from '@/lib/api'
import type { ActivityLog } from '@/lib/types'

function formatActionLabel(action: string) {
  return action
    .split('_')
    .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
    .join(' ')
}

function formatTimestamp(value?: string | null) {
  if (!value) {
    return 'Unknown time'
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

function getActionDescription(item: ActivityLog) {
  const generated = Number(item.metadata?.generated ?? 0)
  const duplicates = Number(item.metadata?.duplicates ?? 0)
  const failed = Number(item.metadata?.failed ?? 0)

  switch (item.action) {
    case 'topics_refreshed':
      return `Fetched ${item.units} ranked topics for ${item.project?.name ?? 'the selected project'}.`
    case 'topic_generation_requested':
      return `Started article generation for ${item.topic?.title ?? 'a selected topic'}.`
    case 'topic_generation_completed':
      return `Completed topic generation with ${generated} article${generated === 1 ? '' : 's'}, ${duplicates} duplicates, and ${failed} failures.`
    case 'project_generation_requested':
      return `Started a project-wide generation run for ${item.project?.name ?? 'the current project'}.`
    case 'project_generation_completed':
      return `Finished the generation run with ${generated} article${generated === 1 ? '' : 's'} created.`
    case 'article_generated':
      return `Created a draft article from ${item.topic?.title ?? 'a ranked topic'}.`
    case 'tokens_used':
      return `Consumed ${item.units.toLocaleString()} AI tokens during content generation.`
    default:
      return `Recorded ${formatActionLabel(item.action).toLowerCase()} for this workspace.`
  }
}

function ActionIcon({ action }: { action: string }) {
  if (action === 'topics_refreshed') {
    return <RefreshCcw className="h-5 w-5 text-cyan-300" />
  }

  if (action === 'article_generated') {
    return <FileText className="h-5 w-5 text-emerald-300" />
  }

  if (action === 'tokens_used') {
    return <Activity className="h-5 w-5 text-amber-300" />
  }

  return <Sparkles className="h-5 w-5 text-violet-300" />
}

export default function ActivityPage() {
  const [activity, setActivity] = useState<ActivityLog[]>([])
  const [total, setTotal] = useState(0)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)

  useEffect(() => {
    let active = true

    async function loadActivity() {
      try {
        setLoading(true)
        setError(null)

        const response = await api.getActivity({ limit: 30 })

        if (!active) {
          return
        }

        setActivity(response.data)
        setTotal(response.total)
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

    loadActivity()

    return () => {
      active = false
    }
  }, [])

  if (loading) {
    return (
      <AppShell>
        <div className="rounded-3xl border border-slate-800 bg-slate-900/70 p-8 text-slate-200">
          Loading activity history...
        </div>
      </AppShell>
    )
  }

  if (error) {
    return (
      <AppShell>
        <div className="rounded-3xl border border-rose-500/40 bg-rose-500/10 p-8 text-rose-100">
          {error}
        </div>
      </AppShell>
    )
  }

  const articleCount = activity
    .filter((item) => item.action === 'article_generated')
    .reduce((sum, item) => sum + item.units, 0)

  const tokenCount = activity
    .filter((item) => item.action === 'tokens_used')
    .reduce((sum, item) => sum + item.units, 0)

  const refreshCount = activity.filter((item) => item.action === 'topics_refreshed').length
  const generationCount = activity.filter((item) => item.action.endsWith('_completed')).length

  return (
    <AppShell>
      <div className="space-y-6">
        <section className="rounded-[2rem] border border-cyan-400/20 bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.18),_transparent_30%),linear-gradient(135deg,_rgba(15,23,42,0.96),_rgba(10,37,64,0.92))] p-8 shadow-[0_30px_80px_-40px_rgba(34,211,238,0.55)]">
          <p className="text-sm uppercase tracking-[0.28em] text-cyan-300">Activity</p>
          <h2 className="mt-3 text-3xl font-semibold text-white">Workspace history and user actions</h2>
          <p className="mt-3 max-w-2xl text-slate-300">
            This page shows what was refreshed, what was generated, how many tokens were used, and which project each action touched.
          </p>
        </section>

        <section className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
          {[
            { label: 'Events loaded', value: total, note: 'Recent actions captured for this user' },
            { label: 'Articles generated', value: articleCount, note: 'Draft articles created from ranked topics' },
            { label: 'Tokens used', value: tokenCount.toLocaleString(), note: 'AI usage consumed by generation runs' },
            { label: 'Workflow runs', value: generationCount + refreshCount, note: 'Refresh and generation milestones' },
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
                <p className="text-sm uppercase tracking-[0.22em] text-cyan-300">Recent feed</p>
                <h3 className="mt-2 text-2xl font-semibold text-white">What happened in the workspace</h3>
              </div>
              <div className="rounded-2xl border border-cyan-400/20 bg-cyan-400/10 p-3 text-cyan-200">
                <Workflow className="h-5 w-5" />
              </div>
            </div>

            <div className="mt-6 space-y-4">
              {activity.length > 0 ? (
                activity.map((item) => (
                  <div key={item.id} className="rounded-3xl border border-slate-800 bg-slate-950/70 p-5">
                    <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                      <div className="flex gap-4">
                        <div className="rounded-2xl border border-slate-800 bg-slate-900/80 p-3">
                          <ActionIcon action={item.action} />
                        </div>
                        <div>
                          <div className="flex flex-wrap items-center gap-2">
                            <p className="font-medium text-white">{formatActionLabel(item.action)}</p>
                            <span className="rounded-full border border-slate-700 bg-slate-900 px-3 py-1 text-xs uppercase tracking-wide text-slate-300">
                              {item.project?.name ?? 'Workspace'}
                            </span>
                          </div>
                          <p className="mt-2 text-sm leading-6 text-slate-300">{getActionDescription(item)}</p>
                          <div className="mt-3 flex flex-wrap gap-2 text-xs text-slate-400">
                            <span className="rounded-full bg-slate-900 px-3 py-1">
                              Actor: {item.user?.name ?? 'Current user'}
                            </span>
                            {item.topic ? (
                              <span className="rounded-full bg-slate-900 px-3 py-1">
                                Topic: {item.topic.title}
                              </span>
                            ) : null}
                            {item.article ? (
                              <span className="rounded-full bg-slate-900 px-3 py-1">
                                Article: {item.article.title}
                              </span>
                            ) : null}
                            {item.action === 'tokens_used' ? (
                              <span className="rounded-full bg-slate-900 px-3 py-1">
                                Units: {item.units.toLocaleString()}
                              </span>
                            ) : null}
                          </div>
                        </div>
                      </div>

                      <div className="flex items-center gap-2 text-sm text-slate-400">
                        <Clock3 className="h-4 w-4" />
                        <span>{formatTimestamp(item.created_at ?? item.used_on)}</span>
                      </div>
                    </div>
                  </div>
                ))
              ) : (
                <div className="rounded-3xl border border-dashed border-slate-700 bg-slate-950/50 p-10 text-center text-slate-400">
                  No activity yet. Start by refreshing topics or generating an article.
                </div>
              )}
            </div>
          </div>

          <div className="space-y-6">
            <div className="rounded-[1.75rem] border border-slate-800 bg-slate-900/75 p-6">
              <p className="text-sm uppercase tracking-[0.22em] text-cyan-300">How to use it</p>
              <h3 className="mt-2 text-2xl font-semibold text-white">Read the workflow faster</h3>
              <div className="mt-5 space-y-4 text-sm text-slate-300">
                <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
                  Refresh events tell you when fresh ranked topics were fetched into a project.
                </div>
                <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
                  Generation events show whether the user ran a single-topic action or a project-wide action.
                </div>
                <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
                  Token events help you understand AI usage and plan consumption over time.
                </div>
              </div>
            </div>

            <div className="rounded-[1.75rem] border border-slate-800 bg-[linear-gradient(180deg,rgba(15,23,42,0.98),rgba(17,94,89,0.9))] p-6">
              <p className="text-sm uppercase tracking-[0.22em] text-emerald-200">Suggested flow</p>
              <h3 className="mt-2 text-2xl font-semibold text-white">Topics first, then activity</h3>
              <p className="mt-3 text-sm leading-6 text-slate-300">
                Use the Topics page to discover and rank ideas. Then come back here to audit what was fetched, generated, and consumed.
              </p>
            </div>
          </div>
        </section>
      </div>
    </AppShell>
  )
}
