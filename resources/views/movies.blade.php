<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Movies List</title>
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

    <div class="container mt-4">
        <!-- Filter Section -->
        <div class="mb-4">
            <form id="movie-filter-form">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="searchType" id="byWord" value="word" checked>
                    <label class="form-check-label" for="byWord">By Word</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="searchType" id="byImdbID" value="imdbID">
                    <label class="form-check-label" for="byImdbID">By IMDb ID</label>
                </div>

                <input type="text" class="form-control mt-2" id="search-query" placeholder="Type your search here..." />
                <button type="submit" class="btn btn-primary mt-2">Search</button>
                <button type="button" class="btn btn-secondary mt-2" id="reset-filters">Reset</button>
            </form>
        </div>

        <!-- Movies Container -->
        <div class="row" id="movies-container">
            <!-- Movies will be loaded here -->
        </div>

        <!-- Load more button -->
        <div class="load-more-btn mb-5">
            <button id="load-more" class="btn btn-primary">Load More</button>
        </div>
    </div>

<script>
    let page = 1; // Initial page number
    let loading = false; // Prevent multiple requests at once
    let searchType = 'word'; // Default search type
    let searchQuery = ''; // Default search query

    // Load movies with optional filters (search type and search query)
    function loadMovies() {
        if (loading) {
            return;
        }
        loading = true;
        $.ajax({
            url: 'http://localhost:7777/api-data',
            type: 'GET',
            data: {
                page: page,
                searchType: searchType,
                searchQuery: searchQuery
            },
            dataType: 'json',
            success: function(response) {
                let moviesContainer = $('#movies-container');

                let data = response.Search;

                if (data && data.length > 0) {
                    data.forEach(function(movie) {
                        // Check if the movie is favorited
                        let isFavorited = movie.is_favorited;
                        let favoriteSection = '';

                        if (isFavorited) {
                            favoriteSection = `<button class="btn btn-danger remove-favorite" data-imdbid="${movie.imdbID}">Remove from Favorites</button>`;
                        } else {
                            favoriteSection = `<button class="btn btn-warning add-favorite" data-imdbid="${movie.imdbID}">Add to Favorites</button>`;
                        }

                        // Create the movie card
                        let movieCard = `
                            <div class="col-md-3 mb-4">
                                <div class="card movie-card">
                                    <img src="${movie.Poster}" class="card-img-top" alt="${movie.Title}">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="/movies/${movie.imdbID}">${movie.Title}</a>
                                        </h5>
                                        <p class="card-text">Year: ${movie.Year}</p>
                                        <p id="favorite-movie-button-${movie.imdbID}" class="float-end">
                                            ${favoriteSection}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `;
                        moviesContainer.append(movieCard);
                    });

                    // Increment the page number for the next request
                    page++;
                } else {
                    $('#load-more').text('No More Movies').attr('disabled', true);
                }

                loading = false;

                // Attach event handlers for favorite/unfavorite buttons
                attachFavoriteHandlers();
            },
            error: function() {
                alert('An error occurred while fetching movie data.');
                loading = false;
            }
        });
    }


    $(document).ready(function() {
        // Load initial movies
        loadMovies();

        // Event handler for the "Load More" button
        $('#load-more').click(function() {
            loadMovies();
        });

        // Event handler for the filter form submission
        $('#movie-filter-form').submit(function(e) {
            e.preventDefault();

            // Get the selected search type (by IMDb ID or by word)
            searchType = $('input[name="searchType"]:checked').val();
            // Get the search query
            searchQuery = $('#search-query').val();

            // Reset the movies container and page count, then load filtered movies
            $('#movies-container').empty();
            page = 1;
            loadMovies();
        });

        // Event listener for the reset button
        $('#reset-filters').click(function() {
            // Reset the radio buttons to the default (By Word)
            $('#byWord').prop('checked', true);
            $('#search-query').val(''); // Clear the search input

            // Reload the page
            location.reload();
        });
        
    });

    $(document).ready(function() {
        // Attach favorite handlers only once
        attachFavoriteHandlers();

        // Function to attach favorite handlers
        function attachFavoriteHandlers() {
            // Use event delegation on the parent container
            $('#movies-container').on('click', '.add-favorite', function(e) {
                e.preventDefault();
                let imdbID = $(this).data('imdbid');
                let $button = $(this);
                
                // Disable the button to prevent multiple clicks
                $button.prop('disabled', true);

                $.ajax({
                    url: '/favorites/add/' + imdbID,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        imdbID: imdbID
                    },
                    success: function(response) {
                        alert(response.message);
                        $(`#favorite-movie-button-${imdbID}`).html(`
                            <button class="btn btn-danger remove-favorite" data-imdbid="${imdbID}">Remove from Favorites</button>
                        `);
                    },
                    error: function(response) {
                        alert(response.responseJSON.message);
                    },
                    complete: function() {
                        // Re-enable the button after the request is complete
                        $button.prop('disabled', false);
                    }
                });
            });

            $('#movies-container').on('click', '.remove-favorite', function(e) {
                e.preventDefault();
                let imdbID = $(this).data('imdbid');
                let $button = $(this);
                
                // Disable the button to prevent multiple clicks
                $button.prop('disabled', true);

                $.ajax({
                    url: '/favorites/remove/' + imdbID,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}",
                        imdbID: imdbID
                    },
                    success: function(response) {
                        alert(response.message);
                        $(`#favorite-movie-button-${imdbID}`).html(`
                            <button class="btn btn-warning add-favorite" data-imdbid="${imdbID}">Add to Favorites</button>
                        `);
                    },
                    error: function(response) {
                        alert(response.responseJSON.message);
                    },
                    complete: function() {
                        // Re-enable the button after the request is complete
                        $button.prop('disabled', false);
                    }
                });
            });
        }
    });

</script>


</body>
</html>
