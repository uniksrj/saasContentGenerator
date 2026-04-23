"use client"
import { Card } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Check, X } from 'lucide-react'
import { useState } from 'react'
// import { useState } from 'react'

const plans = [
  {
    name: 'Free',
    price: '$0',
    description: 'Perfect for getting started',
    features: [
      { name: '2 Projects', included: true },
      { name: '50 Topics', included: true },
      { name: '200 Articles', included: true },
      { name: 'Basic Support', included: true },
      { name: 'Advanced Analytics', included: false },
      { name: 'Custom Integrations', included: false },
      { name: 'Team Management', included: false },
    ],
    current: true,
  },
  {
    name: 'Pro',
    price: '$29',
    description: 'For growing creators',
    features: [
      { name: 'Unlimited Projects', included: true },
      { name: 'Unlimited Topics', included: true },
      { name: '5,000 Articles', included: true },
      { name: 'Priority Support', included: true },
      { name: 'Advanced Analytics', included: true },
      { name: 'Custom Integrations', included: false },
      { name: 'Team Management', included: false },
    ],
    current: false,
  },
  {
    name: 'Agency',
    price: '$99',
    description: 'For teams and agencies',
    features: [
      { name: 'Unlimited Projects', included: true },
      { name: 'Unlimited Topics', included: true },
      { name: 'Unlimited Articles', included: true },
      { name: '24/7 Support', included: true },
      { name: 'Advanced Analytics', included: true },
      { name: 'Custom Integrations', included: true },
      { name: 'Team Management', included: true },
    ],
    current: false,
  },
]

const invoices = [
  { date: 'Feb 1, 2026', amount: '$0.00', status: 'Free Plan', link: '#' },
  { date: 'Jan 1, 2026', amount: '$0.00', status: 'Free Plan', link: '#' },
  { date: 'Dec 1, 2025', amount: '$0.00', status: 'Free Plan', link: '#' },
]

export default function BillingPage() {
  const [name, setName] = useState("John Doe");
  const [email, setEmail] = useState("john@example.com");
  return (
    <main className="p-6 space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold mb-2">Billing</h1>
        <p className="text-foreground/70">Manage your plan and billing information</p>
      </div>

      {/* Current Plan */}
      <Card className="bg-gradient-to-r from-primary/20 to-primary/10 border border-primary/20 p-8">
        <div className="flex items-center justify-between">
          <div>
            <p className="text-sm text-foreground/70 mb-2">CURRENT PLAN</p>
            <h2 className="text-3xl font-bold mb-2">Free</h2>
            <p className="text-foreground/70">You are on the free plan. Upgrade to unlock more features.</p>
          </div>
          <Button size="lg">Upgrade Plan</Button>
        </div>
      </Card>

      {/* Pricing Plans */}
      <div>
        <h3 className="text-2xl font-bold mb-6">Choose Your Plan</h3>
        <div className="grid md:grid-cols-3 gap-6">
          {plans.map((plan) => (
            <Card
              key={plan.name}
              className={`border p-8 transition ${plan.current
                ? 'border-primary bg-primary/5'
                : 'border-border bg-card hover:border-primary/50'
                }`}
            >
              {plan.current && (
                <div className="mb-4 inline-block px-3 py-1 rounded-full bg-primary/20 text-primary text-sm font-semibold">
                  Current Plan
                </div>
              )}
              <h3 className="text-2xl font-bold mb-2">{plan.name}</h3>
              <p className="text-foreground/60 mb-4">{plan.description}</p>
              <div className="mb-6">
                <span className="text-4xl font-bold">{plan.price}</span>
                <span className="text-foreground/60">/month</span>
              </div>
              <Button
                className="w-full mb-6"
                variant={plan.current ? 'secondary' : 'default'}
                disabled={plan.current}
              >
                {plan.current ? 'Current Plan' : 'Upgrade'}
              </Button>
              <ul className="space-y-3">
                {plan.features.map((feature, i) => (
                  <li key={i} className="flex items-center gap-2">
                    {feature.included ? (
                      <Check size={18} className="text-green-500" />
                    ) : (
                      <X size={18} className="text-foreground/30" />
                    )}
                    <span className={feature.included ? 'text-foreground' : 'text-foreground/50'}>
                      {feature.name}
                    </span>
                  </li>
                ))}
              </ul>
            </Card>
          ))}
        </div>
      </div>

      {/* Billing Information */}
      <Card className="bg-card border border-border p-8">
        <h3 className="text-xl font-bold mb-6">Billing Information</h3>
        <div className="grid md:grid-cols-2 gap-6">
          <div>
            <label className="block text-sm font-medium mb-2">Full Name</label>
            <input
              type="text"
              value={name}
              onChange={(e) => setName(e.target.value)}
              className="w-full px-4 py-2 rounded-lg bg-secondary border border-border text-foreground"
            />
          </div>
          <div>
            <label className="block text-sm font-medium mb-2">Email</label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="w-full px-4 py-2 rounded-lg bg-secondary border border-border text-foreground"
            />
          </div>
          <div>
            <label className="block text-sm font-medium mb-2">Card Number</label>
            <input
              type="text"
              placeholder="•••• •••• •••• 4242"
              className="w-full px-4 py-2 rounded-lg bg-secondary border border-border text-foreground"
            />
          </div>
          <div>
            <label className="block text-sm font-medium mb-2">Expiry Date</label>
            <input
              type="text"
              placeholder="MM/YY"
              className="w-full px-4 py-2 rounded-lg bg-secondary border border-border text-foreground"
            />
          </div>
        </div>
        <Button className="mt-6">Update Billing Information</Button>
      </Card>

      {/* Invoice History */}
      <Card className="bg-card border border-border p-8">
        <h3 className="text-xl font-bold mb-6">Invoice History</h3>
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr className="border-b border-border">
                <th className="px-4 py-3 text-left text-sm font-semibold">Date</th>
                <th className="px-4 py-3 text-left text-sm font-semibold">Amount</th>
                <th className="px-4 py-3 text-left text-sm font-semibold">Status</th>
                <th className="px-4 py-3 text-left text-sm font-semibold">Action</th>
              </tr>
            </thead>
            <tbody>
              {invoices.map((invoice, i) => (
                <tr key={i} className="border-b border-border hover:bg-secondary/30 transition">
                  <td className="px-4 py-3 text-sm">{invoice.date}</td>
                  <td className="px-4 py-3 text-sm font-semibold">{invoice.amount}</td>
                  <td className="px-4 py-3 text-sm">
                    <span className="px-2 py-1 rounded-full bg-secondary text-foreground/70 text-xs">
                      {invoice.status}
                    </span>
                  </td>
                  <td className="px-4 py-3 text-sm">
                    <a href={invoice.link} className="text-primary hover:text-primary/80">
                      Download
                    </a>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </Card>
    </main>
  )
}
