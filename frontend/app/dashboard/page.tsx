'use client'

import Link from 'next/link'
import { useEffect, useState } from 'react'

import { Card } from '@/components/ui/card'
import { api, getApiErrorMessage } from '@/lib/api'
import { setStoredUser } from '@/lib/auth'
import type { Article, ProfileData, Project, Topic } from '@/lib/types'

export default function DashboardPage() {
  const [profile, setProfile] = useState<ProfileData | null>(null)
  const [projects, setProjects] = useState<Project[]>([])
  const [articles, setArticles] = useState<Article[]>([])
  const [topics, setTopics] = useState<Topic[]>([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)

  useEffect(() => {
    let active = true

    async function loadDashboard() {
      try {
        setLoading(true)
        setError(null)

        const [profileResponse, projectsResponse] = await Promise.all([
          api.profile(),
          api.getProjects(),
        ])

        if (!active) {
          return
        }

        const profileData = profileResponse.data
        const availableProjects = projectsResponse.data
        const currentProject =
          profileData.current_project ??
          availableProjects.find((project) => project.id === profileData.user.current_project_id) ??
          availableProjects[0] ??
          null

        setStoredUser(profileData.user)
        setProfile(profileData)
        setProjects(availableProjects)

        if (!currentProject) {
          setArticles([])
          setTopics([])
          return
        }

        const [articlesResponse, topicsResponse] = await Promise.all([
          api.getArticles(currentProject.id),
          api.getTopics(currentProject.id),
        ])

        if (!active) {
          return
        }

        setArticles(articlesResponse.data.slice(0, 5))
        setTopics(topicsResponse.data.slice(0, 5))
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

    loadDashboard()

    return () => {
      active = false
    }
  }, [])

  if (loading) {
    return (
      <div className="rounded-3xl border border-slate-800 bg-slate-900/70 p-8 text-slate-200">
        Loading your SaaS dashboard...
      </div>
    )
  }

  if (error) {
    return (
      <div className="rounded-3xl border border-rose-500/40 bg-rose-500/10 p-8 text-rose-100">
        {error}
      </div>
    )
  }

  const usage = profile?.usage

  return (
    <div className="space-y-6">
      <section className="rounded-[2rem] border border-cyan-400/20 bg-gradient-to-r from-cyan-400/12 via-slate-900 to-slate-900 p-8">
        <p className="text-sm uppercase tracking-[0.25em] text-cyan-300">Live from Laravel</p>
        <h2 className="mt-3 text-3xl font-semibold text-white">
          Welcome back, {profile?.user.name ?? 'there'}
        </h2>
        <p className="mt-3 max-w-2xl text-slate-300">
          Your Next.js dashboard is now pulling real profile, project, topic, and article data from
          the Laravel API.
        </p>
      </section>

      <section className="grid gap-4 md:grid-cols-4">
        {[
          { label: 'Projects', value: profile?.counts.projects ?? 0, note: 'Active workspaces' },
          { label: 'Topics', value: profile?.counts.topics ?? 0, note: 'Ideas in pipeline' },
          { label: 'Articles', value: profile?.counts.articles ?? 0, note: 'Generated content' },
          {
            label: 'Remaining credits',
            value: usage?.remaining_articles ?? 0,
            note: usage?.message ?? 'Usage snapshot',
          },
        ].map((item) => (
          <Card key={item.label} className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <p className="text-sm text-slate-400">{item.label}</p>
            <p className="mt-3 text-4xl font-semibold text-white">{item.value}</p>
            <p className="mt-2 text-sm text-slate-400">{item.note}</p>
          </Card>
        ))}
      </section>

      <section className="grid gap-6 xl:grid-cols-[1.3fr,0.9fr]">
        <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
          <div className="flex items-center justify-between">
            <div>
              <h3 className="text-xl font-semibold text-white">Recent Articles</h3>
              <p className="text-sm text-slate-400">Latest content from your current workspace</p>
            </div>
            <Link href="/articles" className="text-sm text-cyan-300 hover:text-cyan-200">
              View all
            </Link>
          </div>

          <div className="mt-6 space-y-3">
            {articles.length > 0 ? (
              articles.map((article) => (
                <div
                  key={article.id}
                  className="rounded-2xl border border-slate-800 bg-slate-950/80 p-4"
                >
                  <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                      <p className="font-medium text-white">{article.title}</p>
                      <p className="text-sm text-slate-400">
                        {article.topic?.title ?? 'No topic linked'}
                      </p>
                    </div>
                    <span className="rounded-full bg-slate-800 px-3 py-1 text-xs uppercase tracking-wide text-slate-300">
                      {article.status}
                    </span>
                  </div>
                </div>
              ))
            ) : (
              <p className="rounded-2xl border border-dashed border-slate-700 p-6 text-slate-400">
                No articles yet. Generate topics first, then create articles from them.
              </p>
            )}
          </div>
        </Card>

        <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
          <h3 className="text-xl font-semibold text-white">Workspace Snapshot</h3>
          <div className="mt-6 space-y-4 text-sm text-slate-300">
            <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
              <p className="text-slate-400">Plan</p>
              <p className="mt-1 text-lg font-medium text-white">
                {profile?.plan?.name ?? 'Starter'}
              </p>
            </div>
            <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
              <p className="text-slate-400">Current project</p>
              <p className="mt-1 text-lg font-medium text-white">
                {profile?.current_project?.name ?? 'No project selected'}
              </p>
            </div>
            <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
              <p className="text-slate-400">Topics ready to use</p>
              <p className="mt-1 text-lg font-medium text-white">{topics.length}</p>
            </div>
            <div className="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
              <p className="text-slate-400">Projects loaded</p>
              <p className="mt-1 text-lg font-medium text-white">{projects.length}</p>
            </div>
          </div>
        </Card>
      </section>
    </div>
  )
}
