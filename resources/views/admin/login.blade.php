<meta name="robots" content="noindex,nofollow,noarchive">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<div class="container">
        <div class="content-wrapper">
            <div class="right-content">
                <h1>Hi, I'm Admin</h1>
                <img src="{{ asset('assets/admin.webp') }}" width="590px" height="340px" alt="man with hill view">
            </div>
            <div class="left-content">
                <h1>Admin Login Page</h1>
                <form action="/login" method="POST">
                    @csrf
                    <label for="username">User Name</label>
                    <input type="text" name="username" class="username" placeholder="Enter your username...">
                    
                    <label for="password">Password</label>
                    <input type="password" name="password" class="password" placeholder="Enter your password...">
                    
                    <button type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>  
