'use client'

import { useEffect, useState } from 'react'

import { AppShell } from '@/components/app-shell'
import { Button } from '@/components/ui/button'
import { Card } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { api, getApiErrorMessage } from '@/lib/api'
import type { Project, ProjectListMeta, ProjectStatus } from '@/lib/types'

export default function ProjectsPage() {
  const [projects, setProjects] = useState<Project[]>([])
  const [projectMeta, setProjectMeta] = useState<ProjectListMeta | null>(null)
  const [name, setName] = useState('')
  const [description, setDescription] = useState('')
  const [loading, setLoading] = useState(true)
  const [submitting, setSubmitting] = useState(false)
  const [busyProjectId, setBusyProjectId] = useState<number | null>(null)
  const [busyStatus, setBusyStatus] = useState<ProjectStatus | null>(null)
  const [message, setMessage] = useState<string | null>(null)
  const [error, setError] = useState<string | null>(null)

  async function loadProjects() {
    try {
      setLoading(true)
      setError(null)
      const response = await api.getProjects({ includeInactive: true, includeRemoved: true })
      setProjects(response.data)
      setProjectMeta(response.meta)
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
      setMessage(null)
      setError(null)

      const response = await api.createProject({
        name,
        description: description || undefined,
      })

      setProjects((currentProjects) => [response.data, ...currentProjects])
      setProjectMeta((currentMeta) =>
        currentMeta
          ? {
              ...currentMeta,
              total_projects: currentMeta.total_projects + 1,
              active_projects: currentMeta.active_projects + 1,
              remaining_project_slots:
                currentMeta.remaining_project_slots === null
                  ? null
                  : Math.max(0, currentMeta.remaining_project_slots - 1),
            }
          : currentMeta,
      )
      setName('')
      setDescription('')
      setMessage('Project created successfully.')
    } catch (caughtError) {
      setError(getApiErrorMessage(caughtError))
    } finally {
      setSubmitting(false)
    }
  }

  async function handleStatusChange(projectId: number, status: ProjectStatus) {
    const confirmMessage =
      status === 'removed'
        ? 'This will mark the project as removed without deleting the database record. It will still count toward your project limit. Continue?'
        : `Change this project to ${status}?`

    if (!window.confirm(confirmMessage)) {
      return
    }

    try {
      setBusyProjectId(projectId)
      setBusyStatus(status)
      setMessage(null)
      setError(null)

      const response = await api.updateProjectStatus(projectId, status)

      setProjects((currentProjects) =>
        currentProjects.map((project) =>
          project.id === projectId ? response.data : project,
        ),
      )
      setMessage(response.message)
      await loadProjects()
    } catch (caughtError) {
      setError(getApiErrorMessage(caughtError))
    } finally {
      setBusyProjectId(null)
      setBusyStatus(null)
    }
  }

  function statusTone(status: ProjectStatus) {
    if (status === 'active') {
      return 'bg-cyan-400/10 text-cyan-200'
    }

    if (status === 'disabled') {
      return 'bg-amber-400/10 text-amber-200'
    }

    return 'bg-rose-500/10 text-rose-200'
  }

  const hasProjectLimit = projectMeta?.project_limit != null
  const projectLimitReached =
    hasProjectLimit &&
    (projectMeta?.remaining_project_slots ?? 0) <= 0

  return (
    <AppShell>
      <div className="space-y-6">
        <div className="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <p className="text-sm uppercase tracking-[0.25em] text-cyan-300">Projects</p>
            <h2 className="text-3xl font-semibold text-white">Manage workspaces</h2>
            <p className="mt-2 text-slate-400">
              Create, disable, or remove projects without losing the lifetime project count.
            </p>
          </div>

          <Button type="button" variant="outline" onClick={loadProjects} disabled={loading}>
            {loading ? 'Refreshing...' : 'Refresh'}
          </Button>
        </div>

        <div className="grid gap-4 md:grid-cols-3">
          <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <p className="text-sm text-slate-400">Total created</p>
            <p className="mt-2 text-3xl font-semibold text-white">{projectMeta?.total_projects ?? 0}</p>
            <p className="mt-2 text-sm text-slate-500">Removed projects still count toward the cap.</p>
          </Card>
          <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <p className="text-sm text-slate-400">Active / disabled</p>
            <p className="mt-2 text-3xl font-semibold text-white">
              {projectMeta?.active_projects ?? 0} / {projectMeta?.disabled_projects ?? 0}
            </p>
            <p className="mt-2 text-sm text-slate-500">Only active projects are available in the content workflow.</p>
          </Card>
          <Card className="rounded-3xl border border-slate-800 bg-slate-900/70 p-6">
            <p className="text-sm text-slate-400">Free plan project cap</p>
            <p className="mt-2 text-3xl font-semibold text-white">
              {!hasProjectLimit
                ? 'Unlimited'
                : `${projectMeta?.total_projects ?? 0} / ${projectMeta?.project_limit ?? 0}`}
            </p>
            <p className="mt-2 text-sm text-slate-500">
              {!hasProjectLimit
                ? 'Paid plans are not capped here.'
                : `${projectMeta.remaining_project_slots ?? 0} slots remaining.`}
            </p>
          </Card>
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
            <Button type="submit" disabled={submitting || projectLimitReached}>
              {submitting ? 'Creating...' : 'Create Project'}
            </Button>
          </form>

          {hasProjectLimit ? (
            <p className="mt-4 text-sm text-slate-400">
              Free users can create up to {projectMeta?.project_limit ?? 0} projects total. Disabled and removed projects still count.
            </p>
          ) : null}

          {message ? (
            <div className="mt-4 rounded-2xl border border-cyan-400/30 bg-cyan-400/10 px-4 py-3 text-sm text-cyan-100">
              {message}
            </div>
          ) : null}

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
                <span className={`rounded-full px-3 py-1 text-xs uppercase tracking-wide ${statusTone(project.status)}`}>
                  {project.status}
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

              <div className="mt-6 flex flex-wrap gap-3">
                {project.status === 'active' ? (
                  <Button
                    type="button"
                    variant="outline"
                    size="sm"
                    onClick={() => handleStatusChange(project.id, 'disabled')}
                    disabled={busyProjectId === project.id}
                  >
                    {busyProjectId === project.id && busyStatus === 'disabled' ? 'Saving...' : 'Disable'}
                  </Button>
                ) : (
                  <Button
                    type="button"
                    variant="outline"
                    size="sm"
                    onClick={() => handleStatusChange(project.id, 'active')}
                    disabled={busyProjectId === project.id}
                  >
                    {busyProjectId === project.id && busyStatus === 'active' ? 'Saving...' : 'Activate'}
                  </Button>
                )}

                {project.status === 'removed' ? (
                  <Button
                    type="button"
                    size="sm"
                    onClick={() => handleStatusChange(project.id, 'active')}
                    disabled={busyProjectId === project.id}
                  >
                    {busyProjectId === project.id && busyStatus === 'active' ? 'Saving...' : 'Restore'}
                  </Button>
                ) : (
                  <Button
                    type="button"
                    variant="destructive"
                    size="sm"
                    onClick={() => handleStatusChange(project.id, 'removed')}
                    disabled={busyProjectId === project.id}
                  >
                    {busyProjectId === project.id && busyStatus === 'removed' ? 'Removing...' : 'Remove'}
                  </Button>
                )}
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
