<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie['Title'] }} - Movie Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery script to handle favorite and unfavorite actions -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    @include('topmenu')

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <!-- Movie Poster -->
                <img src="{{ $movie['Poster'] }}" alt="{{ $movie['Title'] }}" class="img-fluid rounded">
            </div>
            <div class="col-md-8">
                <!-- Movie Details -->
                <h1>{{ $movie['Title'] }} ({{ $movie['Year'] }})</h1>

                <!-- Favorite Button -->
                <div id="favorite-movie-button" class="mt-3">
                    @if($isFavorited)
                        <!-- Show remove button if already favorited -->
                        <button class="btn btn-danger" id="remove-favorite" data-imdbid="{{ $movie['imdbID'] }}">Remove from Favorites</button>
                    @else
                        <!-- Show add button if not favorited -->
                        <button class="btn btn-warning" id="add-favorite" data-imdbid="{{ $movie['imdbID'] }}">Add to Favorites</button>
                    @endif
                </div>
                <hr/>
                <p><strong>Rated:</strong> {{ $movie['Rated'] }}</p>
                <p><strong>Released:</strong> {{ $movie['Released'] }}</p>
                <p><strong>Runtime:</strong> {{ $movie['Runtime'] }}</p>
                <p><strong>Genre:</strong> {{ $movie['Genre'] }}</p>
                <p><strong>Director:</strong> {{ $movie['Director'] }}</p>
                <p><strong>Writer:</strong> {{ $movie['Writer'] }}</p>
                <p><strong>Actors:</strong> {{ $movie['Actors'] }}</p>
                <p><strong>Plot:</strong> {{ $movie['Plot'] }}</p>
                <p><strong>Language:</strong> {{ $movie['Language'] }}</p>
                <p><strong>Country:</strong> {{ $movie['Country'] }}</p>
                <p><strong>Awards:</strong> {{ $movie['Awards'] }}</p>
                
                <!-- Movie Ratings -->
                <h5>Ratings</h5>
                <ul>
                    @if($movie['Ratings'])
                        @foreach($movie['Ratings'] as $rating)
                            <li>{{ $rating['Source'] }}: {{ $rating['Value'] }}</li>
                        @endforeach
                    @else
                        N/A
                    @endif
                </ul>

                <p><strong>Metascore:</strong> {{ $movie['Metascore'] }}</p>
                <p><strong>IMDb Rating:</strong> {{ $movie['imdbRating'] }} ({{ $movie['imdbVotes'] }} votes)</p>
                <p><strong>Box Office:</strong> {{ $movie['BoxOffice']??'' }}</p>

            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            // Handle adding to favorite
            $('#add-favorite').click(function(e) {
                e.preventDefault();
                var imdbID = $(this).data('imdbid');

                $.ajax({
                    url: "{{ route('favorites.add', '') }}/" + imdbID,
                    type: 'POST',
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

            // Handle removing from favorite
            $('#remove-favorite').click(function(e) {
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
