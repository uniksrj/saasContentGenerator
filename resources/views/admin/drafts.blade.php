@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

@include('admin.common.header')

<div class="admin-dashboard" style="display:flex; min-height:100vh; background:#f8f9fa;">
    @include('admin.common.sidebar')

    <div class="main-content" style="flex:1; margin-left:250px; padding:20px; width:calc(100% - 250px);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <div>
                <h1 class="h3 mb-1">Draft Reviews</h1>
                <p class="text-muted mb-0">Review AI-generated and manual drafts before publishing.</p>
            </div>
            <a href="{{ route('addpost') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Create Post
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Source</th>
                                <th>Updated</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($drafts as $draft)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ \Illuminate\Support\Str::limit($draft->title, 80) }}</div>
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($draft->description), 120) }}</small>
                                    </td>
                                    <td><span class="badge bg-secondary text-uppercase">{{ $draft->category ?: 'technology' }}</span></td>
                                    <td>
                                        <div class="mb-1">
                                            <span class="badge bg-info text-dark text-uppercase">{{ $draft->source_type ?: 'manual' }}</span>
                                        </div>
                                        @if (!empty($draft->source_url))
                                            <a href="{{ $draft->source_url }}" target="_blank" rel="noopener" class="small">View source</a>
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ optional($draft->updated_at)->format('M d, Y h:i A') }}</td>
                                    <td><span class="badge bg-warning text-dark">Draft</span></td>
                                    <td>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <a href="{{ route('admin.drafts.preview', $draft->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>Preview
                                            </a>
                                            <a href="/editPage/{{ $draft->id }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-pen me-1"></i>Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.drafts.publish', $draft->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-upload me-1"></i>Publish
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                        No drafts waiting for review.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $drafts->links() }}
        </div>
    </div>
</div>

@include('admin.common.footer')
