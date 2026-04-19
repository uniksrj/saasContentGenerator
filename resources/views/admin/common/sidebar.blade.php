<style>
    .sidebar {
        background: linear-gradient(180deg, #4361ee 0%, #3f37c9 100%);
        color: white;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
        transition: width 0.3s;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        overflow-y: auto;
    }
    
    .sidebar-header {
        padding: 20px 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .sidebar-header h3 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
    }
    
    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 20px 0;
    }
    
    .sidebar-menu li {
        margin-bottom: 5px;
    }
    
    .sidebar-menu a {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.2s;
        border-left: 3px solid transparent;
        white-space: nowrap;
        overflow: hidden;
    }
    
    .sidebar-menu a:hover, .sidebar-menu a.active {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
        border-left: 3px solid white;
    }
    
    .sidebar-menu i {
        width: 25px;
        font-size: 1.1rem;
        margin-right: 10px;
        text-align: center;
    }
    
    .menu-divider {
        height: 1px;
        background-color: rgba(255, 255, 255, 0.1);
        margin: 15px 0;
    }
    
    .menu-label {
        padding: 10px 15px;
        font-size: 0.75rem;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.5);
        letter-spacing: 1px;
        white-space: nowrap;
        overflow: hidden;
    }
    
    .badge {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 0.7rem;
        margin-left: auto;
    }
    
</style>

<div class="sidebar">
    <div class="sidebar-header">
        <h3><i class="fas fa-cogs me-2"></i>Admin Panel</h3>
    </div>
    
    <ul class="sidebar-menu">
        <li class="menu-label">Main</li>
        <li>
            <a href="/admin" class="{{ request()->is('admin') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span class="menu-text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="/admin/drafts" class="{{ request()->is('admin/drafts*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i>
                <span class="menu-text">Review Drafts</span>
            </a>
        </li>
        <li>
            <a href="/adduser" class="{{ request()->is('adduser') ? 'active' : '' }}">
                <i class="fas fa-plus-circle"></i>
                <span class="menu-text">Add New Post</span>
            </a>
        </li>
        <li>
            <a href="/admin/comments">
                <i class="fas fa-comments"></i>
                <span class="menu-text">Comments</span>
                <span class="badge">5</span>
            </a>
        </li>
        
        <li class="menu-divider"></li>
        <li class="menu-label">Content</li>
        <li>
            <a href="/admin/categories">
                <i class="fas fa-folder"></i>
                <span class="menu-text">Categories</span>
            </a>
        </li>
        <li>
            <a href="/admin/tags">
                <i class="fas fa-tags"></i>
                <span class="menu-text">Tags</span>
            </a>
        </li>
        <li>
            <a href="/admin/media">
                <i class="fas fa-images"></i>
                <span class="menu-text">Media Library</span>
            </a>
        </li>
        
        <li class="menu-divider"></li>
        <li class="menu-label">Users</li>
        <li>
            <a href="/admin/users">
                <i class="fas fa-users"></i>
                <span class="menu-text">All Users</span>
            </a>
        </li>
        <li>
            <a href="/admin/profile">
                <i class="fas fa-user-cog"></i>
                <span class="menu-text">My Profile</span>
            </a>
        </li>
        
        <li class="menu-divider"></li>
        <li class="menu-label">Settings</li>
        <li>
            <a href="/admin/settings">
                <i class="fas fa-cog"></i>
                <span class="menu-text">Site Settings</span>
            </a>
        </li>
        <li>
            <a href="/admin/notifications">
                <i class="fas fa-bell"></i>
                <span class="menu-text">Notifications</span>
                <span class="badge">3</span>
            </a>
        </li>
        <li>
            <a href="/admin/analytics">
                <i class="fas fa-chart-bar"></i>
                <span class="menu-text">Analytics</span>
            </a>
        </li>
    </ul>
</div>
