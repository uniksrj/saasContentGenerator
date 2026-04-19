<script>
    var csrfToken = "{{ csrf_token() }}";
</script>
@vite(['resources/css/app.css', 'resources/js/app.js'])
@vite('resources/js/admin.js')
@vite('resources/js/editpage.js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<style>    
    .admin-dashboard {
        display: flex;
        min-height: 100vh;
        background: #f8f9fa;
    }

    .main-content {
        flex: 1;
        margin-left: 250px;
        padding: 20px;
        width: calc(100% - 250px);
        transition: all 0.3s ease;
    }
    
    .admin-content {
        display: block;
        margin: 0;
        padding: 0;
    }

    .admin-content > * {
        max-width: none;
        padding-right: 0;
        padding-left: 0;
        margin-top: 0;
    }

    .addPost {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #3498db;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .add_post {
        background: linear-gradient(135deg, #3498db, #2980b9);
        border: none;
        border-radius: 8px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
    }

    .add_post:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-left: 4px solid #3498db;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #7f8c8d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .stat-icon {
        font-size: 2rem;
        color: #3498db;
        margin-bottom: 1rem;
    }
    
    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-top: 2rem;
    }

    .table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
    }

    .table-header h3 {
        margin: 0;
        font-weight: 600;
    }

    .table-responsive {
        border-radius: 0 0 12px 12px;
        overflow-x: auto;
    }

    .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        font-weight: 700;
        color: #2c3e50;
        padding: 1rem;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .table tbody tr:hover td {
        background: #f8f9fa;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-edit {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: white;
        cursor: pointer;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
        color: white;
    }

    .btn-publish {
        background: linear-gradient(135deg, #1f8f4d, #27ae60);
        color: white;
    }

    .btn-publish:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4);
        color: white;
    }
    
    #message {
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 1050;
        min-width: 300px;
    }

    .alert-success {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
    }

    .alert-danger {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: white;
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-published {
        background: #2ecc71;
        color: white;
    }

    .badge-draft {
        background: #f39c12;
        color: white;
    }
    
    .search-filter {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding-left: 2.5rem;
        border-radius: 25px;
        border: 2px solid #e9ecef;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #7f8c8d;
    }
    
    .flex-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .flex-buttons {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .flex-search {
        display: flex;
        gap: 1rem;
        align-items: center;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #7f8c8d;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #bdc3c7;
    }
    
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 15px;
            width: 100%;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .flex-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .flex-buttons {
            width: 100%;
        }

        .add_post {
            width: 100%;
        }

        .table-responsive {
            font-size: 0.9rem;
        }

        .btn-action {
            display: block;
            margin-bottom: 0.5rem;
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .main-content {
            padding: 10px;
        }

        .search-filter {
            padding: 1rem;
        }

        .flex-search {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>

@include('admin.common.header')

<div class="admin-dashboard">
    @include('admin.common.sidebar')
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="flex-header">
            <div>
                <h1 class="h2 mb-1 addPost">Dashboard Overview</h1>
                <p class="text-muted mb-0">Welcome back! Here's what's happening with your blog today.</p>
            </div>
            <div class="flex-buttons">
                <form action="/adduser">
                    <button class="btn btn-primary add_post">
                        <i class="fas fa-plus-circle me-2"></i>Add New Post
                    </button>
                </form>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['posts'] ?? 0) }}</div>
                <div class="stat-label">Total Posts</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['published'] ?? 0) }}</div>
                <div class="stat-label">Published</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['drafts'] ?? 0) }}</div>
                <div class="stat-label">Drafts Pending Review</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['views'] ?? 0) }}</div>
                <div class="stat-label">Total Views</div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="search-filter">
            <div class="flex-search">
                <div class="search-box" style="flex: 1;">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" placeholder="Search posts..." id="searchInput">
                </div>
                <select class="form-select" style="max-width: 200px;">
                    <option>All Status</option>
                    <option>Published</option>
                    <option>Draft</option>
                </select>
            </div>
        </div>

        <!-- Message Alert -->
        <div id="message"></div>

        <!-- Posts Table -->
        <div class="table-container">
            <div class="table-header">
                <h3><i class="fas fa-list me-2"></i>Recent Posts</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Author</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details_arr as $key => $value)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <div style="width: 35px; height: 35px; background: #3498db; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 0.8rem;">
                                        {{ substr($value->name, 0, 2) }}
                                    </div>
                                    {{ $value->name }}
                                </div>
                            </td>
                            <td style="font-weight: 600;">{{ Str::limit($value->title, 50) }}</td>
                            <td>{{ Str::limit($value->description, 70) }}</td>
                            <td>
                                <small style="color: #6c757d;">
                                    <i class="far fa-calendar me-1"></i>
                                    {{ $value->created_date ? \Carbon\Carbon::parse($value->created_date)->format('M d, Y') : 'N/A' }}
                                </small>
                            </td>
                            @php
                                $isPublished = (($value->status ?? null) === 'published') || ((int) ($value->is_published ?? 0) === 1);
                            @endphp
                            <td>
                                <span class="status-badge {{ $isPublished ? 'badge-published' : 'badge-draft' }}">
                                    {{ $isPublished ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 10px;">
                                    @if (!$isPublished)
                                    <a href="{{ route('admin.drafts.preview', $value->id) }}" class="btn-action btn-edit" target="_blank">
                                        <i class="fas fa-eye me-1"></i>Preview
                                    </a>
                                    <form method="POST" action="{{ route('admin.drafts.publish', $value->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-action btn-publish">
                                            <i class="fas fa-upload me-1"></i>Publish
                                        </button>
                                    </form>
                                    @endif
                                    <a href="/editPage/{{$value->id}}" class="btn-action btn-edit">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <button type="button" class="btn-action btn-delete delete_data" data-id="{{ $value->id }}">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h4>No Posts Found</h4>
                                    <p>Get started by creating your first blog post!</p>
                                    <form action="/adduser">
                                        <button class="btn btn-primary add_post" style="margin-top: 1rem;">
                                            <i class="fas fa-plus-circle me-2"></i>Create First Post
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete confirmation
        document.querySelectorAll('.delete_data').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const postId = this.getAttribute('data-id');
                const postTitle = this.closest('tr').querySelector('td:nth-child(2)').textContent;
                
                if (confirm(`Are you sure you want to delete "${postTitle.trim()}"?`)) {
                    deletePost(postId);
                }
            });
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }

        // Show success message if exists
        @if(session('success'))
        showMessage('{{ session('success') }}', 'success');
        @endif

        @if(session('error'))
        showMessage('{{ session('error') }}', 'error');
        @endif
    });

    function deletePost(postId) {
        const button = document.querySelector(`.delete_data[data-id="${postId}"]`);
        const row = button.closest('tr');
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
        button.disabled = true;
        
        fetch(`/delete_data/${postId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Post deleted successfully!', 'success');
                // Remove row with fade out effect
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                setTimeout(() => {
                    row.remove();
                    // Update stats
                    updateStats();
                }, 300);
            } else {
                throw new Error(data.message || 'Failed to delete post');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error deleting post: ' + error.message, 'error');
            button.innerHTML = '<i class="fas fa-trash me-1"></i>Delete';
            button.disabled = false;
        });
    }

    function showMessage(message, type) {
        const messageDiv = document.getElementById('message');
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        
        messageDiv.innerHTML = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            const alert = messageDiv.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            }
        }, 5000);
    }

    function updateStats() {
        // Update total posts count
        const postCount = document.querySelectorAll('tbody tr:not([style*="display: none"])').length;
        const statNumber = document.querySelector('.stat-card:first-child .stat-number');
        if (statNumber) {
            statNumber.textContent = postCount;
        }
    }
</script>

@include('admin.common.footer')
