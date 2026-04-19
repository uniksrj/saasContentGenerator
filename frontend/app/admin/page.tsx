import { Card } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { MoreVertical, Ban, Check } from 'lucide-react'

const adminStats = [
  { label: 'Total Users', value: '342', change: '+45 this week' },
  { label: 'Active Projects', value: '1,240', change: '+180 this month' },
  { label: 'Articles Created', value: '8,920', change: '+1,200 this month' },
  { label: 'Revenue (MRR)', value: '$12,450', change: '+$2,340 this month' },
]

const recentUsers = [
  {
    id: 1,
    name: 'John Doe',
    email: 'john@example.com',
    plan: 'Free',
    joined: '2 days ago',
    status: 'active',
  },
  {
    id: 2,
    name: 'Jane Smith',
    email: 'jane@example.com',
    plan: 'Pro',
    joined: '1 week ago',
    status: 'active',
  },
  {
    id: 3,
    name: 'Bob Johnson',
    email: 'bob@example.com',
    plan: 'Agency',
    joined: '2 weeks ago',
    status: 'active',
  },
  {
    id: 4,
    name: 'Alice Williams',
    email: 'alice@example.com',
    plan: 'Free',
    joined: '3 weeks ago',
    status: 'inactive',
  },
]

const systemStatus = [
  { service: 'API Server', status: 'operational', uptime: '99.98%' },
  { service: 'Database', status: 'operational', uptime: '99.99%' },
  { service: 'Storage', status: 'operational', uptime: '99.95%' },
  { service: 'Auth Service', status: 'operational', uptime: '100%' },
]

export default function AdminPage() {
  return (
    <div className="min-h-screen bg-background">
      {/* Admin Header */}
      <header className="bg-card border-b border-border px-6 py-4">
        <div className="max-w-7xl mx-auto">
          <h1 className="text-3xl font-bold">Admin Dashboard</h1>
          <p className="text-foreground/70 mt-1">System management and analytics</p>
        </div>
      </header>

      <main className="max-w-7xl mx-auto px-6 py-6 space-y-6">
        {/* Stats Grid */}
        <div className="grid md:grid-cols-4 gap-4">
          {adminStats.map((stat, i) => (
            <Card key={i} className="bg-card border border-border p-6">
              <p className="text-foreground/60 text-sm mb-2">{stat.label}</p>
              <p className="text-3xl font-bold mb-2">{stat.value}</p>
              <p className="text-foreground/50 text-sm">{stat.change}</p>
            </Card>
          ))}
        </div>

        {/* System Status */}
        <Card className="bg-card border border-border p-6">
          <h2 className="text-xl font-semibold mb-6">System Status</h2>
          <div className="space-y-3">
            {systemStatus.map((item, i) => (
              <div key={i} className="flex items-center justify-between p-4 rounded-lg bg-secondary/30">
                <div className="flex items-center gap-3">
                  <div className="w-3 h-3 rounded-full bg-green-500" />
                  <div>
                    <p className="font-medium">{item.service}</p>
                    <p className="text-sm text-foreground/60">{item.status}</p>
                  </div>
                </div>
                <p className="text-sm font-semibold">{item.uptime} uptime</p>
              </div>
            ))}
          </div>
        </Card>

        {/* User Management */}
        <Card className="bg-card border border-border p-6">
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-xl font-semibold">Recent Users</h2>
            <Input placeholder="Search users..." className="max-w-xs" />
          </div>

          <div className="overflow-x-auto">
            <table className="w-full">
              <thead>
                <tr className="border-b border-border">
                  <th className="px-6 py-3 text-left text-sm font-semibold">User</th>
                  <th className="px-6 py-3 text-left text-sm font-semibold">Email</th>
                  <th className="px-6 py-3 text-left text-sm font-semibold">Plan</th>
                  <th className="px-6 py-3 text-left text-sm font-semibold">Joined</th>
                  <th className="px-6 py-3 text-left text-sm font-semibold">Status</th>
                  <th className="px-6 py-3 text-center text-sm font-semibold">Actions</th>
                </tr>
              </thead>
              <tbody>
                {recentUsers.map((user, idx) => (
                  <tr
                    key={user.id}
                    className={`border-b border-border hover:bg-secondary/30 transition ${
                      idx === recentUsers.length - 1 ? 'border-b-0' : ''
                    }`}
                  >
                    <td className="px-6 py-4 font-medium">{user.name}</td>
                    <td className="px-6 py-4 text-sm text-foreground/70">{user.email}</td>
                    <td className="px-6 py-4 text-sm">
                      <span className="inline-block px-3 py-1 rounded-full bg-primary/20 text-primary">
                        {user.plan}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-sm text-foreground/70">{user.joined}</td>
                    <td className="px-6 py-4">
                      <div
                        className={`flex items-center gap-2 text-sm ${
                          user.status === 'active' ? 'text-green-500' : 'text-yellow-500'
                        }`}
                      >
                        <div
                          className={`w-2 h-2 rounded-full ${
                            user.status === 'active' ? 'bg-green-500' : 'bg-yellow-500'
                          }`}
                        />
                        {user.status === 'active' ? 'Active' : 'Inactive'}
                      </div>
                    </td>
                    <td className="px-6 py-4 text-center">
                      <div className="flex items-center justify-center gap-2">
                        <button className="p-1 hover:bg-secondary/50 rounded transition">
                          <Check size={18} className="text-green-500" />
                        </button>
                        <button className="p-1 hover:bg-secondary/50 rounded transition">
                          <Ban size={18} className="text-red-500" />
                        </button>
                        <button className="p-1 hover:bg-secondary/50 rounded transition">
                          <MoreVertical size={18} className="text-foreground/60" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </Card>

        {/* Settings */}
        <Card className="bg-card border border-border p-6">
          <h2 className="text-xl font-semibold mb-6">System Settings</h2>
          <div className="space-y-6">
            <div className="grid md:grid-cols-2 gap-6">
              <div>
                <label className="block text-sm font-medium mb-2">
                  Maximum file upload size (MB)
                </label>
                <Input type="number" defaultValue="100" />
              </div>
              <div>
                <label className="block text-sm font-medium mb-2">
                  API rate limit (requests/minute)
                </label>
                <Input type="number" defaultValue="60" />
              </div>
              <div>
                <label className="block text-sm font-medium mb-2">
                  Session timeout (minutes)
                </label>
                <Input type="number" defaultValue="30" />
              </div>
              <div>
                <label className="block text-sm font-medium mb-2">
                  Max team members per project
                </label>
                <Input type="number" defaultValue="50" />
              </div>
            </div>
            <Button>Save Settings</Button>
          </div>
        </Card>
      </main>
    </div>
  )
}
