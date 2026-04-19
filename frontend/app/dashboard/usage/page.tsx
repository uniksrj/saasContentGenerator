import { Card } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { AlertCircle, TrendingUp } from 'lucide-react'

const usageMetrics = [
  { name: 'Articles', used: 245, limit: 5000, percentage: 4 },
  { name: 'Topics', used: 89, limit: 1000, percentage: 8 },
  { name: 'Projects', used: 5, limit: 100, percentage: 5 },
  { name: 'Team Members', used: 3, limit: 10, percentage: 30 },
]

const apiUsage = [
  { endpoint: 'GET /api/projects', requests: 1240, percentage: 12 },
  { endpoint: 'GET /api/articles', requests: 2150, percentage: 21 },
  { endpoint: 'POST /api/articles', requests: 890, percentage: 8 },
  { endpoint: 'GET /api/topics', requests: 1560, percentage: 15 },
]

export default function UsagePage() {
  return (
    <main className="p-6 space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold mb-2">Usage & Limits</h1>
        <p className="text-foreground/70">Monitor your resource usage and plan limits</p>
      </div>

      {/* Current Plan Info */}
      <Card className="bg-gradient-to-r from-primary/20 to-primary/10 border border-primary/20 p-8">
        <div className="flex items-center justify-between">
          <div>
            <p className="text-sm text-foreground/70 mb-2">CURRENT PLAN</p>
            <h2 className="text-3xl font-bold mb-2">Free Plan</h2>
            <p className="text-foreground/70">You are using 4% of your included resources</p>
          </div>
          <Button>Upgrade to Pro</Button>
        </div>
      </Card>

      {/* Usage Breakdown */}
      <div className="grid lg:grid-cols-2 gap-6">
        {usageMetrics.map((metric) => (
          <Card key={metric.name} className="bg-card border border-border p-6">
            <div className="mb-4">
              <div className="flex items-center justify-between mb-2">
                <h3 className="font-semibold">{metric.name}</h3>
                <span className="text-lg font-bold text-primary">{metric.percentage}%</span>
              </div>
              <p className="text-sm text-foreground/60">
                {metric.used.toLocaleString()} / {metric.limit.toLocaleString()}
              </p>
            </div>
            <div className="h-3 bg-secondary rounded-full overflow-hidden">
              <div
                className="h-full bg-gradient-to-r from-primary to-primary/70 transition-all duration-300"
                style={{ width: `${metric.percentage}%` }}
              />
            </div>
            {metric.percentage > 80 && (
              <div className="flex items-center gap-2 mt-3 text-yellow-500 text-sm">
                <AlertCircle size={16} />
                Approaching limit
              </div>
            )}
          </Card>
        ))}
      </div>

      {/* API Usage */}
      <Card className="bg-card border border-border p-6">
        <h2 className="text-xl font-semibold mb-6">API Usage This Month</h2>
        <div className="space-y-4">
          {apiUsage.map((api, i) => (
            <div key={i}>
              <div className="flex items-center justify-between mb-2">
                <p className="font-medium text-sm">{api.endpoint}</p>
                <p className="text-sm text-foreground/60">{api.requests.toLocaleString()} requests</p>
              </div>
              <div className="h-2 bg-secondary rounded-full overflow-hidden">
                <div
                  className="h-full bg-primary transition-all"
                  style={{ width: `${api.percentage}%` }}
                />
              </div>
            </div>
          ))}
        </div>
        <p className="text-sm text-foreground/60 mt-6">
          Total requests: 10,240 / 100,000 monthly limit
        </p>
      </Card>

      {/* Plan Comparison */}
      <Card className="bg-card border border-border p-8">
        <h2 className="text-xl font-semibold mb-6">Plan Limits Comparison</h2>
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr className="border-b border-border">
                <th className="px-4 py-3 text-left text-sm font-semibold">Resource</th>
                <th className="px-4 py-3 text-center text-sm font-semibold">Free</th>
                <th className="px-4 py-3 text-center text-sm font-semibold">Pro</th>
                <th className="px-4 py-3 text-center text-sm font-semibold">Agency</th>
              </tr>
            </thead>
            <tbody>
              {[
                { resource: 'Projects', free: 2, pro: '∞', agency: '∞' },
                { resource: 'Topics', free: 50, pro: '∞', agency: '∞' },
                { resource: 'Articles', free: 200, pro: '5,000', agency: '∞' },
                { resource: 'Team Members', free: 1, pro: 10, agency: '∞' },
                { resource: 'Storage (GB)', free: 1, pro: 10, agency: '∞' },
                { resource: 'API Requests/mo', free: '10,000', pro: '100,000', agency: '∞' },
                { resource: 'Support', free: 'Email', pro: 'Priority', agency: '24/7' },
              ].map((row, i) => (
                <tr
                  key={i}
                  className="border-b border-border hover:bg-secondary/30 transition"
                >
                  <td className="px-4 py-3 font-medium">{row.resource}</td>
                  <td className="px-4 py-3 text-center text-foreground/70">{row.free}</td>
                  <td className="px-4 py-3 text-center">{row.pro}</td>
                  <td className="px-4 py-3 text-center text-foreground/70">{row.agency}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </Card>

      {/* Recommendations */}
      <Card className="bg-secondary/30 border border-border p-6">
        <div className="flex items-start gap-4">
          <div className="p-3 rounded-lg bg-primary/20">
            <TrendingUp className="text-primary" size={20} />
          </div>
          <div className="flex-1">
            <h3 className="font-semibold mb-2">Upgrade Recommended</h3>
            <p className="text-foreground/70 mb-4">
              You&apos;re creating content consistently! The Pro plan would give you unlimited
              projects and topics, plus advanced analytics.
            </p>
            <Button size="sm">View Pro Plan</Button>
          </div>
        </div>
      </Card>
    </main>
  )
}
