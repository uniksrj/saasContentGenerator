import { Card } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Trash2 } from 'lucide-react'

const teamMembers = [
  { id: 1, name: 'John Doe', email: 'john@example.com', role: 'Owner', joined: 'Created' },
  { id: 2, name: 'Jane Smith', email: 'jane@example.com', role: 'Admin', joined: '2 months ago' },
  { id: 3, name: 'Bob Johnson', email: 'bob@example.com', role: 'Editor', joined: '1 month ago' },
]

export default function SettingsPage() {
  return (
    <main className="p-6 space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold mb-2">Settings</h1>
        <p className="text-foreground/70">Manage your account and preferences</p>
      </div>

      {/* Profile Settings */}
      <Card className="bg-card border border-border p-8">
        <h2 className="text-2xl font-bold mb-6">Profile Settings</h2>
        <div className="space-y-6">
          <div className="flex items-center gap-6 pb-6 border-b border-border">
            <div className="w-20 h-20 rounded-full bg-primary/20 flex items-center justify-center text-2xl">
              JD
            </div>
            <div>
              <h3 className="text-lg font-semibold mb-2">Profile Picture</h3>
              <p className="text-foreground/60 mb-4">Upload a new profile picture</p>
              <Button variant="outline">Upload Photo</Button>
            </div>
          </div>

          <div className="grid md:grid-cols-2 gap-6">
            <div>
              <label className="block text-sm font-medium mb-2">First Name</label>
              <Input type="text" defaultValue="John" />
            </div>
            <div>
              <label className="block text-sm font-medium mb-2">Last Name</label>
              <Input type="text" defaultValue="Doe" />
            </div>
            <div className="md:col-span-2">
              <label className="block text-sm font-medium mb-2">Email</label>
              <Input type="email" defaultValue="john@example.com" />
            </div>
            <div className="md:col-span-2">
              <label className="block text-sm font-medium mb-2">Bio</label>
              <textarea
                className="w-full px-4 py-2 rounded-lg bg-secondary border border-border text-foreground"
                rows={4}
                placeholder="Tell us about yourself..."
              />
            </div>
          </div>
          <Button>Save Changes</Button>
        </div>
      </Card>

      {/* Team Management */}
      <Card className="bg-card border border-border p-8">
        <div className="flex items-center justify-between mb-6">
          <h2 className="text-2xl font-bold">Team Members</h2>
          <Button size="sm">Invite Member</Button>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr className="border-b border-border">
                <th className="px-4 py-3 text-left text-sm font-semibold">Name</th>
                <th className="px-4 py-3 text-left text-sm font-semibold">Email</th>
                <th className="px-4 py-3 text-left text-sm font-semibold">Role</th>
                <th className="px-4 py-3 text-left text-sm font-semibold">Joined</th>
                <th className="px-4 py-3 text-center text-sm font-semibold">Action</th>
              </tr>
            </thead>
            <tbody>
              {teamMembers.map((member, i) => (
                <tr key={member.id} className="border-b border-border hover:bg-secondary/30 transition">
                  <td className="px-4 py-3 font-medium">{member.name}</td>
                  <td className="px-4 py-3 text-sm text-foreground/70">{member.email}</td>
                  <td className="px-4 py-3">
                    <select className="text-sm px-2 py-1 rounded bg-secondary border border-border text-foreground">
                      <option>Owner</option>
                      <option>Admin</option>
                      <option>Editor</option>
                      <option>Viewer</option>
                    </select>
                  </td>
                  <td className="px-4 py-3 text-sm text-foreground/70">{member.joined}</td>
                  <td className="px-4 py-3 text-center">
                    {member.role !== 'Owner' && (
                      <button className="p-1 hover:bg-destructive/20 rounded transition text-destructive">
                        <Trash2 size={18} />
                      </button>
                    )}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </Card>

      {/* Notification Preferences */}
      <Card className="bg-card border border-border p-8">
        <h2 className="text-2xl font-bold mb-6">Notification Preferences</h2>
        <div className="space-y-4">
          {[
            { label: 'Email Notifications', checked: true },
            { label: 'Article Published', checked: true },
            { label: 'Comment Notifications', checked: true },
            { label: 'Weekly Summary', checked: false },
            { label: 'Product Updates', checked: true },
          ].map((pref, i) => (
            <label key={i} className="flex items-center gap-3 cursor-pointer">
              <input type="checkbox" defaultChecked={pref.checked} className="rounded" />
              <span>{pref.label}</span>
            </label>
          ))}
        </div>
        <Button className="mt-6">Save Preferences</Button>
      </Card>

      {/* Security Settings */}
      <Card className="bg-card border border-border p-8">
        <h2 className="text-2xl font-bold mb-6">Security Settings</h2>
        <div className="space-y-4">
          <div className="border-b border-border pb-4">
            <h3 className="font-semibold mb-2">Password</h3>
            <p className="text-sm text-foreground/60 mb-4">
              Last changed 3 months ago
            </p>
            <Button variant="outline">Change Password</Button>
          </div>
          <div className="border-b border-border pb-4">
            <h3 className="font-semibold mb-2">Two-Factor Authentication</h3>
            <p className="text-sm text-foreground/60 mb-4">
              Add an extra layer of security to your account
            </p>
            <Button variant="outline">Enable 2FA</Button>
          </div>
          <div>
            <h3 className="font-semibold mb-2">Active Sessions</h3>
            <p className="text-sm text-foreground/60 mb-4">
              Manage your active sessions across devices
            </p>
            <Button variant="outline">View Sessions</Button>
          </div>
        </div>
      </Card>

      {/* Danger Zone */}
      <Card className="bg-destructive/5 border border-destructive/20 p-8">
        <h2 className="text-2xl font-bold text-destructive mb-6">Danger Zone</h2>
        <div className="space-y-4">
          <div className="border-b border-destructive/20 pb-4">
            <h3 className="font-semibold mb-2">Delete Account</h3>
            <p className="text-sm text-foreground/60 mb-4">
              Permanently delete your account and all associated data
            </p>
            <Button variant="destructive">Delete Account</Button>
          </div>
        </div>
      </Card>
    </main>
  )
}
