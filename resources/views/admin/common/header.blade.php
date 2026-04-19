@if (session('message'))
    <div class="alert alert-success text-center" id="alert_message" role="alert">
        <span>{{ session('message') }}</span>
    </div>
@endif

<div class="myblock">
    <nav class="navbar" style="justify-content: space-around !important; padding: 20px;">
        <div class="logo-text">
                    <img src="{{ asset('assets/unikmusewhite.webp') }}" alt="Unik Muse">
                </div>
        <div class="s_block">
            <input class="form-control" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-light" type="submit">Search</button>
            <span class="social-icons">
                <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
            </span>
            <a href="/logout" class="btn btn-danger">Logout</a>
        </div>
    </nav>
</div>
<script>
    // Auto-hide alert message
    setTimeout(function() {
        const alert = document.getElementById('alert_message');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 5000);
</script>
