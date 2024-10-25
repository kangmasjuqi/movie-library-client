<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <div class="navbar-nav">
            <a class="nav-link active" aria-current="page" href="/">Home</a>
            <a class="nav-link" href="/movies">Movies</a>
            <a class="nav-link" href="/movies/favorites">Favorites</a>

            @auth
                <!-- Logout Button Styled Like a Link -->
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <a class="nav-link" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                        <span style="color:red;">Logout</span>
                    </a>
                </form>
            @else
                <a class="nav-link" href="/login">Login</a>
            @endauth
        </div>
    </div>
</nav>