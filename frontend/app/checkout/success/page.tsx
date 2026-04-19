import Link from 'next/link'
import { Button } from '@/components/ui/button'
import { Card } from '@/components/ui/card'
import { CheckCircle, Download } from 'lucide-react'

export default function CheckoutSuccessPage() {
  return (
    <div className="min-h-screen bg-background flex items-center justify-center px-4">
      <div className="w-full max-w-md">
        <div className="text-center mb-8">
          <div className="flex justify-center mb-6">
            <div className="w-20 h-20 rounded-full bg-green-500/20 flex items-center justify-center">
              <CheckCircle className="text-green-500" size={48} />
            </div>
          </div>
          <h1 className="text-4xl font-bold mb-2">Welcome!</h1>
          <p className="text-foreground/70">Your upgrade to Pro has been successful</p>
        </div>

        <Card className="bg-card border border-border p-8 mb-6">
          <div className="space-y-6">
            <div className="bg-gradient-to-r from-primary/20 to-primary/10 rounded-lg p-6">
              <p className="text-sm text-foreground/70 mb-2">YOUR NEW PLAN</p>
              <h2 className="text-3xl font-bold text-primary mb-2">Pro</h2>
              <p className="text-foreground/70">$29/month, billed monthly</p>
            </div>

            <div>
              <h3 className="font-semibold mb-4">What you get now:</h3>
              <ul className="space-y-3">
                {[
                  'Unlimited Projects',
                  'Unlimited Topics',
                  '5,000 Articles',
                  'Priority Support',
                  'Advanced Analytics',
                  'Custom Integrations',
                ].map((feature, i) => (
                  <li key={i} className="flex items-center gap-2">
                    <CheckCircle size={18} className="text-green-500" />
                    <span>{feature}</span>
                  </li>
                ))}
              </ul>
            </div>

            <div className="bg-secondary/30 rounded-lg p-4">
              <p className="text-sm text-foreground/60">
                Invoice
                {' '}
                <span className="text-foreground font-semibold">#INV-2026-001234</span>
                {' '}
                has been sent to your email.
              </p>
            </div>

            <Button className="w-full gap-2">
              <Download size={18} />
              Download Invoice
            </Button>
          </div>
        </Card>

        <div className="space-y-3">
          <Button className="w-full" size="lg" asChild>
            <Link href="/dashboard">Go to Dashboard</Link>
          </Button>
          <Button variant="outline" className="w-full" asChild>
            <Link href="/dashboard/billing">View Billing</Link>
          </Button>
        </div>

        <p className="text-center text-foreground/60 text-sm mt-6">
          Need help? Contact{' '}
          <a href="mailto:support@contenthub.io" className="text-primary hover:text-primary/80">
            support@contenthub.io
          </a>
        </p>
      </div>
    </div>
  )
}
