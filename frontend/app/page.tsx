import Link from 'next/link'
import { Button } from '@/components/ui/button'

export default function LandingPage() {
  return (
    <div className="min-h-screen bg-background text-foreground">
      {/* Navigation */}
      <nav className="border-b border-border">
        <div className="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
          <div className="text-2xl font-bold">
            <span className="text-primary">Content</span>Hub
          </div>
          <div className="flex items-center gap-4">
            <Link href="/login" className="text-foreground/70 hover:text-foreground transition">
              Sign in
            </Link>
            <Button asChild>
              <Link href="/signup">Get Started</Link>
            </Button>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <main className="max-w-7xl mx-auto px-6 py-24">
        <div className="grid md:grid-cols-2 gap-12 items-center">
          <div>
            <h1 className="text-5xl md:text-6xl font-bold leading-tight mb-6 text-balance">
              AI-Powered Content Management Made Simple
            </h1>
            <p className="text-xl text-foreground/70 mb-8 text-balance">
              Create, organize, and publish professional content. Manage topics, articles, and collaborate with your team seamlessly.
            </p>
            <div className="flex gap-4">
              <Button asChild size="lg">
                <Link href="/signup">Start Free Trial</Link>
              </Button>
              <Button variant="outline" size="lg" asChild>
                <Link href="#features">Learn More</Link>
              </Button>
            </div>
          </div>
          <div className="bg-gradient-to-br from-primary/20 to-primary/5 rounded-lg p-8 aspect-square flex items-center justify-center">
            <div className="text-center">
              <div className="text-6xl mb-4">✨</div>
              <p className="text-foreground/60">Visual preview of your content management workspace</p>
            </div>
          </div>
        </div>

        {/* Features */}
        <div id="features" className="mt-32">
          <h2 className="text-4xl font-bold mb-16 text-center">Powerful Features</h2>
          <div className="grid md:grid-cols-3 gap-8">
            {[
              {
                title: 'Project Management',
                description: 'Organize your content into projects and keep everything in one place.',
                icon: '📁',
              },
              {
                title: 'Topic Scoring',
                description: 'Automatically score topics based on relevance and engagement metrics.',
                icon: '⭐',
              },
              {
                title: 'Article Workflow',
                description: 'Draft, review, and publish articles with an intuitive workflow.',
                icon: '✍️',
              },
              {
                title: 'Team Collaboration',
                description: 'Work together with your team and manage access per project.',
                icon: '👥',
              },
              {
                title: 'Usage Analytics',
                description: 'Track your usage and stay within your plan limits.',
                icon: '📊',
              },
              {
                title: 'Flexible Plans',
                description: 'Choose from Free, Pro, or Agency plans with unlimited growth.',
                icon: '💳',
              },
            ].map((feature, i) => (
              <div
                key={i}
                className="bg-card border border-border rounded-lg p-6 hover:border-primary/50 transition"
              >
                <div className="text-4xl mb-4">{feature.icon}</div>
                <h3 className="text-xl font-semibold mb-2">{feature.title}</h3>
                <p className="text-foreground/60">{feature.description}</p>
              </div>
            ))}
          </div>
        </div>

        {/* Pricing */}
        <div className="mt-32">
          <h2 className="text-4xl font-bold mb-16 text-center">Pricing Plans</h2>
          <div className="grid md:grid-cols-3 gap-8">
            {[
              {
                name: 'Free',
                price: '$0',
                description: 'Perfect for getting started',
                features: [
                  '2 Projects',
                  '50 Topics',
                  '200 Articles',
                  'Basic Support',
                ],
              },
              {
                name: 'Pro',
                price: '$29',
                description: 'For growing creators',
                features: [
                  'Unlimited Projects',
                  'Unlimited Topics',
                  '5,000 Articles',
                  'Priority Support',
                  'Advanced Analytics',
                ],
                highlighted: true,
              },
              {
                name: 'Agency',
                price: '$99',
                description: 'For teams and agencies',
                features: [
                  'Everything in Pro',
                  'Unlimited Articles',
                  'Team Management',
                  '24/7 Support',
                  'Custom Integrations',
                ],
              },
            ].map((plan, i) => (
              <div
                key={i}
                className={`rounded-lg p-8 border transition ${
                  plan.highlighted
                    ? 'border-primary bg-primary/10'
                    : 'border-border bg-card hover:border-primary/50'
                }`}
              >
                <h3 className="text-2xl font-bold mb-2">{plan.name}</h3>
                <p className="text-foreground/60 mb-4">{plan.description}</p>
                <div className="mb-6">
                  <span className="text-4xl font-bold">{plan.price}</span>
                  <span className="text-foreground/60">/month</span>
                </div>
                <Button className="w-full mb-6" variant={plan.highlighted ? 'default' : 'outline'} asChild>
                  <Link href="/signup">Get Started</Link>
                </Button>
                <ul className="space-y-3">
                  {plan.features.map((feature, j) => (
                    <li key={j} className="flex items-center gap-2 text-foreground/70">
                      <span className="text-primary">✓</span>
                      {feature}
                    </li>
                  ))}
                </ul>
              </div>
            ))}
          </div>
        </div>
      </main>

      {/* Footer */}
      <footer className="border-t border-border mt-32 py-12 px-6">
        <div className="max-w-7xl mx-auto text-center text-foreground/60">
          <p>&copy; 2026 ContentHub. All rights reserved.</p>
        </div>
      </footer>
    </div>
  )
}
