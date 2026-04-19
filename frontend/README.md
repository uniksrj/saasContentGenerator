# ContentHub - AI Content Management SaaS Platform

A modern, production-ready SaaS platform for managing content projects, topics, and articles with intelligent scoring and billing integration.

## Features

### Core Functionality
- **Multi-Project Workspace**: Organize content into separate projects with full isolation
- **Topics & Scoring System**: Intelligent topic scoring based on articles, views, and engagement
- **Article Workflow**: Draft, review, and publish articles with status tracking
- **Team Collaboration**: Invite team members with role-based access control (Owner, Admin, Editor, Contributor)
- **Usage Tracking**: Real-time monitoring of resource usage with visual progress indicators

### Subscription Plans
- **Free**: 2 Projects, 50 Topics, 200 Articles, 1 Team Member
- **Pro**: Unlimited Projects/Topics, 5,000 Articles, 10 Team Members, Advanced Analytics
- **Agency**: Unlimited Everything, 100 Team Members, 24/7 Support, Custom Integrations

### Dashboard Features
- Real-time activity charts and analytics
- Usage limit monitoring with visual progress bars
- Recent items quick access
- Performance metrics and trends
- Admin dashboard for system management

## Project Structure

```
/app
  /api
    /projects          # Project CRUD operations
    /articles          # Article management and listing
    /topics            # Topic scoring and management
    /checkout          # Stripe payment processing
    /usage             # Usage tracking and limits
  /dashboard
    /page.tsx          # Main dashboard with analytics
    /projects          # Projects management pages
    /articles          # Article editor and listing
    /topics            # Topics overview and detail
    /billing           # Billing and plan management
    /settings          # Account and team settings
    /usage             # Usage monitoring page
    /layout.tsx        # Dashboard navigation sidebar
  /login               # Authentication page
  /signup              # Registration page
  /checkout/success    # Payment success page
  /admin               # Admin dashboard
  /page.tsx            # Landing page
/components/ui         # shadcn UI components
/public                # Static assets
```

## Technology Stack

- **Frontend**: Next.js 16 with React 19
- **Styling**: Tailwind CSS with custom dark theme
- **UI Components**: shadcn/ui with Recharts for visualizations
- **Charts**: Recharts for data visualization
- **Icons**: Lucide React icons
- **Database**: Mock in-memory (ready for Supabase/Neon integration)
- **Payments**: Stripe integration (API routes prepared)
- **Authentication**: Session-based (ready for Auth.js integration)

## Getting Started

### Installation

```bash
npm install
# or
yarn install
# or
pnpm install
```

### Development

```bash
npm run dev
```

Open [http://localhost:3000](http://localhost:3000) in your browser.

### Build & Deploy

```bash
npm run build
npm start
```

## API Routes

### Projects
- `GET /api/projects` - List user's projects
- `POST /api/projects` - Create new project

### Articles
- `GET /api/articles` - List articles (with filters for status, project)
- `POST /api/articles` - Create new article

### Topics
- `GET /api/topics` - List topics with scoring
- `POST /api/topics` - Create new topic

### Billing & Checkout
- `POST /api/checkout` - Create checkout session
- `GET /api/checkout` - Get available plans

### Usage Tracking
- `GET /api/usage` - Get user's usage metrics and limits
- `PUT /api/usage` - Update usage data

## Key Pages

### Public Pages
- `/` - Landing page with pricing and features
- `/login` - Sign in page
- `/signup` - Registration page

### Dashboard Pages
- `/dashboard` - Main dashboard with analytics
- `/dashboard/projects` - Projects management
- `/dashboard/articles` - Article listing and management
- `/dashboard/topics` - Topics overview with scoring
- `/dashboard/billing` - Billing and plan selection
- `/dashboard/settings` - Account, team, and security settings
- `/dashboard/usage` - Resource usage monitoring

### Detail Pages
- `/dashboard/projects/[id]` - Project details with topics and articles
- `/dashboard/articles/[id]` - Article editor with rich text and SEO
- `/dashboard/topics/[id]` - Topic details with performance charts

### Admin
- `/admin` - Admin dashboard with system status and user management
- `/checkout/success` - Payment confirmation page

## Authentication

Currently uses mock authentication. To implement real authentication:

1. **Option 1: Supabase Auth**
   ```bash
   npm install @supabase/supabase-js @supabase/auth-helpers-nextjs
   ```
   Connect Supabase integration for built-in auth.

2. **Option 2: Auth.js (NextAuth.js v5)**
   ```bash
   npm install next-auth
   ```

## Database Integration

Currently uses in-memory mock data. To add persistent storage:

1. **Supabase (Recommended)**
   - Connect Supabase integration
   - Update API routes to query PostgreSQL

2. **Neon**
   - Install `@neondatabase/serverless`
   - Update API routes accordingly

3. **Firebase**
   - Install Firebase SDK
   - Update API routes with Firestore queries

## Stripe Integration

The checkout API is prepared for Stripe. To enable payments:

1. Get Stripe API keys from Stripe Dashboard
2. Install `stripe` package: `npm install stripe`
3. Update `/app/api/checkout/route.ts` with real Stripe implementation
4. Add environment variables: `STRIPE_SECRET_KEY`, `STRIPE_PUBLISHABLE_KEY`

## Design System

### Colors
- **Primary**: Purple-based accent color for CTAs and highlights
- **Background**: Deep black (#1a1a1a) for dark mode
- **Card**: Dark gray (#1f1f1f) for container backgrounds
- **Border**: Medium gray (#333333) for dividers
- **Text**: Off-white (#f5f5f5) for primary text

### Typography
- **Sans**: Geist font family
- **Mono**: Geist Mono for code
- **Sizing**: Responsive text scaling with Tailwind utilities

### Components
- Buttons: Multiple variants (default, outline, ghost, destructive)
- Cards: Flexible containers with borders
- Inputs: Styled form fields with validation support
- Tables: Responsive tables with hover states
- Charts: Recharts for data visualization

## Performance Optimizations

- Server-side rendering for fast initial load
- Image optimization with Next.js Image component
- Code splitting and lazy loading
- CSS-in-JS with Tailwind for minimal bundle size
- Memoized components to prevent unnecessary re-renders

## Deployment

### Vercel (Recommended)
```bash
vercel deploy
```

### Docker
```bash
docker build -t contenthub .
docker run -p 3000:3000 contenthub
```

### Other Platforms
Build and deploy the `.next` output directory with Node.js runtime.

## Environment Variables

Create a `.env.local` file:

```env
# Stripe (when enabled)
STRIPE_SECRET_KEY=sk_test_...
STRIPE_PUBLISHABLE_KEY=pk_test_...

# Supabase (when enabled)
NEXT_PUBLIC_SUPABASE_URL=https://...supabase.co
NEXT_PUBLIC_SUPABASE_ANON_KEY=...

# Session
SESSION_SECRET=your-secret-key-here
```

## Future Enhancements

- [ ] Real database integration (Supabase/Neon)
- [ ] Complete Stripe payment processing
- [ ] Advanced analytics and reporting
- [ ] AI-powered content suggestions
- [ ] Real-time collaboration features
- [ ] Content calendar and scheduling
- [ ] Team workspace templates
- [ ] Custom domain support
- [ ] API documentation with OAuth
- [ ] Mobile app integration

## License

MIT License - feel free to use this for your own projects.

## Support

For questions or issues, contact support@contenthub.io or open an issue on GitHub.

## Contributing

Contributions are welcome! Please follow our code style and submit pull requests.

---

Built with Next.js, React, and Tailwind CSS. Deployed on Vercel.
