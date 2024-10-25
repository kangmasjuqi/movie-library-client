<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Favorite Movies List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .navbar { justify-content: center; }
        .movie-card { transition: transform 0.3s; }
        .movie-card:hover { transform: scale(1.05); }
        .card-img-top { height: 300px; object-fit: cover; }
        .load-more-btn { text-align: center; margin-top: 20px; }
        .favorited-badge {
            background-color: #28a745; /* Bootstrap success color */
            color: white; /* White text */
            padding: 0.5rem 0.75rem; /* Padding for better visibility */
            border-radius: 0.25rem; /* Rounded corners */
            display: inline-block; /* Ensure it's displayed inline */
            margin-left: 10px; /* Space from the favorite button */
        }        
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    
    @include('topmenu')

    <div class="container mt-5">
        <h2>My Favorite Movies ({{ $favoriteMovies->count() }})</h2>

        @if($favoriteMovies->isEmpty())
            <p>You have not favorited any movies yet.</p>
        @else
            <div class="row">
                @foreach($favoriteMovies as $favorite)
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <img src="{{ $favorite->movie->Poster }}" class="card-img-top" alt="{{ $favorite->movie->Title }}">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="/movies/{{ $favorite->movie->imdbID }}">
                                        {{ $favorite->movie->Title }}</a>
                                </h5>
                                <p class="card-text">Year: {{ $favorite->movie->Year }}</p>
                                <div class="favorite-movie-button mt-3">
                                    <button class="btn btn-danger remove-favorite" data-imdbid="{{ $favorite->movie->imdbID }}">Remove from Favorites</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Script -->
    <script type="text/javascript">
        $(document).ready(function() {
            // Handle removing from favorite for each button using class selector
            $('.remove-favorite').click(function(e) {
                e.preventDefault();
                var imdbID = $(this).data('imdbid');

                $.ajax({
                    url: "{{ route('favorites.remove', '') }}/" + imdbID,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}",
                        imdbID: imdbID
                    },
                    success: function(response) {
                        alert(response.message);
                        location.reload(); // Reload page to update button state
                    },
                    error: function(response) {
                        alert(response.responseJSON.message);
                    }
                });
            });
        });
    </script>

</body>
</html>