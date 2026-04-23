import Link from 'next/link'

const platformStats = [
  { label: 'Active workspaces', value: '120+' },
  { label: 'Topics tracked monthly', value: '18k' },
  { label: 'Publishing flow', value: '4-step' },
]

const featureGroups = [
  {
    title: 'Editorial structure',
    description:
      'Organize projects, topics, and article pipelines in one system that feels calm instead of cluttered.',
  },
  {
    title: 'Scored opportunities',
    description:
      'Review which ideas deserve attention first with ranking logic that supports better prioritization.',
  },
  {
    title: 'Publishing control',
    description:
      'Move from draft to publish with a workflow designed for consistency, approvals, and cleaner execution.',
  },
]

const operatingPoints = [
  {
    title: 'Designed for focused teams',
    body: 'The layout favors hierarchy, legibility, and decision-making over noise, gimmicks, and overdrawn UI.',
  },
  {
    title: 'Built around repeatable output',
    body: 'Projects, topic discovery, drafting, and usage tracking are framed as one operating system for content.',
  },
  {
    title: 'Professional from the first screen',
    body: 'Every section is intentional, restrained, and easier to trust at a glance than the old playful landing page.',
  },
]

const plans = [
  {
    name: 'Starter',
    price: '$0',
    cadence: '/month',
    summary: 'For testing the workflow and setting up an initial publishing system.',
    items: ['2 projects', '50 topics', '200 articles', 'Core reporting'],
  },
  {
    name: 'Pro',
    price: '$29',
    cadence: '/month',
    summary: 'For operators who need scale, stronger visibility, and fewer operational limits.',
    items: ['Unlimited projects', 'Unlimited topics', '5,000 articles', 'Priority support'],
    highlighted: true,
  },
  {
    name: 'Agency',
    price: '$99',
    cadence: '/month',
    summary: 'For multi-client teams managing heavier publishing volume and team access.',
    items: ['Everything in Pro', 'Unlimited articles', 'Team management', 'Custom onboarding'],
  },
]

export default function LandingPage() {
  return (
    <div className="min-h-screen bg-[#f3eee6] text-slate-900">
      <div className="relative overflow-hidden">
        <div className="absolute inset-x-0 top-0 h-[520px] bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.92),transparent_38%),linear-gradient(180deg,#f3eee6_0%,#f6f2eb_52%,#f3eee6_100%)]" />
        <div className="absolute inset-0 bg-[linear-gradient(rgba(15,23,42,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(15,23,42,0.04)_1px,transparent_1px)] bg-[size:56px_56px] opacity-40" />

        <div className="relative z-10">
          <nav className="border-b border-slate-300/70">
            <div className="mx-auto flex max-w-7xl items-center justify-between px-6 py-5">
              <Link href="/" className="text-lg font-semibold tracking-[0.18em] text-slate-900 uppercase">
                Unik Muse
              </Link>
              <div className="flex items-center gap-3">
                <Link
                  href="/login"
                  className="rounded-full border border-transparent px-4 py-2 text-sm font-medium text-slate-600 transition hover:text-slate-900"
                >
                  Sign in
                </Link>
                <Link
                  href="/signup"
                  className="rounded-full border border-slate-900 bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                  Create account
                </Link>
              </div>
            </div>
          </nav>

          <main className="mx-auto max-w-7xl px-6 pb-20 pt-14 md:pb-24 md:pt-20">
            <section className="grid gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
              <div>
                <p className="mb-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">
                  Content Operations Platform
                </p>
                <h1 className="max-w-4xl text-5xl font-semibold leading-[1.02] tracking-[-0.04em] text-slate-950 md:text-6xl lg:text-7xl">
                  A professional home page for a product that manages serious publishing work.
                </h1>
                <p className="mt-6 max-w-2xl text-lg leading-8 text-slate-600">
                  Plan topics, score opportunities, draft articles, and manage usage in one disciplined
                  workspace. The interface now speaks with more confidence and a lot less noise.
                </p>

                <div className="mt-9 flex flex-wrap gap-3">
                  <Link
                    href="/signup"
                    className="rounded-full border border-slate-900 bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                  >
                    Start free
                  </Link>
                  <Link
                    href="#product"
                    className="rounded-full border border-slate-400 bg-white/80 px-6 py-3 text-sm font-semibold text-slate-900 transition hover:border-slate-500 hover:bg-white"
                  >
                    Explore platform
                  </Link>
                </div>

                <div className="mt-10 grid gap-4 sm:grid-cols-3">
                  {platformStats.map((stat) => (
                    <div
                      key={stat.label}
                      className="rounded-[24px] border border-slate-300/70 bg-white/75 p-5 shadow-[0_20px_60px_rgba(15,23,42,0.06)] backdrop-blur"
                    >
                      <p className="mb-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                        {stat.label}
                      </p>
                      <p className="mb-0 text-3xl font-semibold text-slate-950">{stat.value}</p>
                    </div>
                  ))}
                </div>
              </div>

              <div className="rounded-[32px] border border-slate-300/70 bg-[linear-gradient(180deg,rgba(255,255,255,0.9),rgba(248,250,252,0.82))] p-4 shadow-[0_30px_80px_rgba(15,23,42,0.10)] backdrop-blur md:p-6">
                <div className="rounded-[26px] border border-slate-200 bg-[#fbfaf7] p-5 md:p-6">
                  <div className="flex items-center justify-between border-b border-slate-200 pb-4">
                    <div>
                      <p className="mb-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                        Editorial workspace
                      </p>
                      <h2 className="text-2xl font-semibold text-slate-950">Publishing overview</h2>
                    </div>
                    <div className="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-emerald-700">
                      Stable
                    </div>
                  </div>

                  <div className="mt-5 grid gap-4 md:grid-cols-[1.15fr_0.85fr]">
                    <div className="space-y-4">
                      <div className="rounded-[22px] border border-slate-200 bg-white p-4">
                        <div className="flex items-start justify-between gap-4">
                          <div>
                            <p className="mb-1 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                              Current queue
                            </p>
                            <p className="text-lg font-semibold text-slate-950">
                              12 topics approved for article generation
                            </p>
                          </div>
                          <span className="text-sm font-medium text-slate-500">This week</span>
                        </div>
                        <div className="mt-4 h-2 rounded-full bg-slate-100">
                          <div className="h-2 w-[72%] rounded-full bg-slate-900" />
                        </div>
                      </div>

                      <div className="grid gap-4 sm:grid-cols-2">
                        <div className="rounded-[22px] border border-slate-200 bg-white p-4">
                          <p className="mb-1 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                            Draft velocity
                          </p>
                          <p className="text-3xl font-semibold text-slate-950">84%</p>
                          <p className="mt-2 text-sm leading-6 text-slate-600">
                            Higher conversion from accepted topic to usable first draft.
                          </p>
                        </div>
                        <div className="rounded-[22px] border border-slate-200 bg-white p-4">
                          <p className="mb-1 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                            Usage visibility
                          </p>
                          <p className="text-3xl font-semibold text-slate-950">Real-time</p>
                          <p className="mt-2 text-sm leading-6 text-slate-600">
                            Teams can see plan usage before it becomes an operational surprise.
                          </p>
                        </div>
                      </div>
                    </div>

                    <div className="rounded-[22px] border border-slate-200 bg-white p-4">
                      <p className="mb-4 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                        Workflow
                      </p>
                      <div className="space-y-3">
                        {['Research topics', 'Score opportunities', 'Generate article', 'Review and publish'].map(
                          (step, index) => (
                            <div
                              key={step}
                              className="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3"
                            >
                              <p className="mb-1 text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">
                                Step {index + 1}
                              </p>
                              <p className="text-sm font-medium text-slate-800">{step}</p>
                            </div>
                          )
                        )}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            <section
              id="product"
              className="mt-24 rounded-[32px] border border-slate-300/70 bg-white/75 p-6 shadow-[0_24px_70px_rgba(15,23,42,0.06)] backdrop-blur md:p-8"
            >
              <div className="grid gap-8 lg:grid-cols-[0.8fr_1.2fr] lg:items-start">
                <div>
                  <p className="mb-3 text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">
                    Why it feels better
                  </p>
                  <h2 className="text-3xl font-semibold tracking-[-0.03em] text-slate-950 md:text-4xl">
                    The page now looks like a product people can trust.
                  </h2>
                </div>
                <div className="grid gap-4 md:grid-cols-3">
                  {operatingPoints.map((point) => (
                    <div
                      key={point.title}
                      className="rounded-[24px] border border-slate-200 bg-[#fcfbf8] p-5"
                    >
                      <h3 className="text-xl font-semibold text-slate-950">{point.title}</h3>
                      <p className="mt-3 text-sm leading-7 text-slate-600">{point.body}</p>
                    </div>
                  ))}
                </div>
              </div>
            </section>

            <section className="mt-24">
              <div className="mb-10 max-w-3xl">
                <p className="mb-3 text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">
                  Core capabilities
                </p>
                <h2 className="text-3xl font-semibold tracking-[-0.03em] text-slate-950 md:text-4xl">
                  Three product areas, presented with cleaner hierarchy and no cheap icon treatment.
                </h2>
              </div>

              <div className="grid gap-6 md:grid-cols-3">
                {featureGroups.map((feature) => (
                  <article
                    key={feature.title}
                    className="rounded-[28px] border border-slate-300/70 bg-white/80 p-6 shadow-[0_20px_55px_rgba(15,23,42,0.06)]"
                  >
                    <p className="mb-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                      Product area
                    </p>
                    <h3 className="text-2xl font-semibold text-slate-950">{feature.title}</h3>
                    <p className="mt-4 text-sm leading-7 text-slate-600">{feature.description}</p>
                  </article>
                ))}
              </div>
            </section>

            <section id="pricing" className="mt-24">
              <div className="mb-10 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div className="max-w-3xl">
                  <p className="mb-3 text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">
                    Pricing
                  </p>
                  <h2 className="text-3xl font-semibold tracking-[-0.03em] text-slate-950 md:text-4xl">
                    Plans that read clearly and feel more premium.
                  </h2>
                </div>
                <Link
                  href="/signup"
                  className="text-sm font-semibold text-slate-900 underline decoration-slate-400 underline-offset-4"
                >
                  Start with the plan that fits
                </Link>
              </div>

              <div className="grid gap-6 lg:grid-cols-3">
                {plans.map((plan) => (
                  <article
                    key={plan.name}
                    className={`rounded-[30px] border p-7 shadow-[0_22px_60px_rgba(15,23,42,0.06)] ${
                      plan.highlighted
                        ? 'border-slate-900 bg-slate-900 text-white'
                        : 'border-slate-300/70 bg-white/82 text-slate-900'
                    }`}
                  >
                    <p
                      className={`mb-3 text-xs font-semibold uppercase tracking-[0.2em] ${
                        plan.highlighted ? 'text-slate-300' : 'text-slate-500'
                      }`}
                    >
                      {plan.name}
                    </p>
                    <div className="flex items-end gap-2">
                      <span className="text-5xl font-semibold tracking-[-0.04em]">{plan.price}</span>
                      <span className={plan.highlighted ? 'text-slate-300' : 'text-slate-500'}>
                        {plan.cadence}
                      </span>
                    </div>
                    <p className={`mt-4 text-sm leading-7 ${plan.highlighted ? 'text-slate-300' : 'text-slate-600'}`}>
                      {plan.summary}
                    </p>
                    <ul className={`mt-6 space-y-3 text-sm ${plan.highlighted ? 'text-slate-200' : 'text-slate-700'}`}>
                      {plan.items.map((item) => (
                        <li key={item} className="border-b border-current/10 pb-3 last:border-b-0 last:pb-0">
                          {item}
                        </li>
                      ))}
                    </ul>
                    <Link
                      href="/signup"
                      className={`mt-8 inline-flex rounded-full px-5 py-2.5 text-sm font-semibold transition ${
                        plan.highlighted
                          ? 'border border-white bg-white text-slate-900 hover:bg-slate-100'
                          : 'border border-slate-900 bg-slate-900 text-white hover:bg-slate-800'
                      }`}
                    >
                      Choose {plan.name}
                    </Link>
                  </article>
                ))}
              </div>
            </section>
          </main>

          <footer className="border-t border-slate-300/70">
            <div className="mx-auto flex max-w-7xl flex-col gap-4 px-6 py-8 text-sm text-slate-600 md:flex-row md:items-center md:justify-between">
              <p>Unik Muse content platform</p>
              <div className="flex flex-wrap gap-5">
                <Link href="/login" className="transition hover:text-slate-900">
                  Sign in
                </Link>
                <Link href="/signup" className="transition hover:text-slate-900">
                  Create account
                </Link>
              </div>
            </div>
          </footer>
        </div>
      </div>
    </div>
  )
}
