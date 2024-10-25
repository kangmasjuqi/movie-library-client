<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\ApiService;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Movie;
use App\Models\MovieFavorite;

class MovieController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }    
    
    /**
     * Show the movies list.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('movies');
    }

    // Method to show movie details by imdbID
    public function showMovieDetail($imdbID)
    {
        $user = Auth::user();

        // Check if the movie already exists in the movies table
        $movie = Movie::where('imdbID', $imdbID)->first();

        if(!$movie) {
            // fetch from API
            $movie = resolve(ApiService::class)->getDetailData($imdbID);

            // then create movie 
            $this->createMovie($imdbID);
        }
    
        // Check if the movie is favorited by the user
        $isFavorited = false;
        $favorite = null;
    
        if ($user) {
            $favorite = $user->favoriteMovies()->where('imdbID', $imdbID)->first();
            if ($favorite) {
                $isFavorited = true;
            }
        }
    
        // Pass $movie, $isFavorited, and $favorite to the view
        return view('movie-detail', [
            'movie' => $movie,
            'isFavorited' => $isFavorited,
            'favorite' => $favorite
        ]);
    }    

    public function myFavoriteMovies()
    {
        // Get the currently authenticated user
        $user = Auth::user();
        
        // Fetch the user's favorite movies (through the relationship)
        $favoriteMovies = $user->favoriteMovies()->orderBy('id', 'desc')->with('movie')->get();
        
        // Pass the favorite movies to the view
        return view('favorites', compact('favoriteMovies'));
    }

    // Method to favorite a movie
    public function addFavorite(Request $request, $imdbID)
    {
        $user = Auth::user();
    
        // Check if the movie is already favorited by the user
        $favorite = $user->favoriteMovies()->where('imdbID', $imdbID)->first();
    
        if ($request->isMethod('post')) {
            if ($favorite) {
                return response()->json(['message' => 'Movie already in favorites'], 200);
            }
    
            // Create movie first if it doesn't exist
            $this->createMovie($imdbID);
    
            // Add movie to user's favorites
            MovieFavorite::create([
                'user_id' => $user->id,
                'imdbID' => $imdbID,
            ]);
    
            return response()->json(['message' => 'Movie added to favorites']);
        } elseif ($request->isMethod('delete')) {
            if (!$favorite) {
                return response()->json(['message' => 'Movie not in favorites'], 404);
            }
    
            // Remove movie from user's favorites
            $favorite->delete();
    
            return response()->json(['message' => 'Movie removed from favorites']);
        }
    
        return response()->json(['message' => 'Invalid request'], 400);
    }    

    public function removeFavorite(Request $request, $imdbID)
    {
        $user = Auth::user();
    
        // Find the favorite entry and delete it
        $favorite = $user->favoriteMovies()->where('imdbID', $imdbID)->first();
    
        if ($favorite) {
            $favorite->delete();
            return response()->json(['message' => 'Movie removed from favorites']);
        }
    
        return response()->json(['message' => 'Movie not found in favorites'], 404);
    }
       
    private function createMovie($imdbID)
    {
        // Fetch movie details from the API
        $movieData = resolve(ApiService::class)->getDetailData($imdbID);
    
        // Check if the movie already exists in the movies table
        $movie = Movie::where('imdbID', $imdbID)->first();
    
        // If the movie doesn't exist, store the movie data first
        if (!$movie) {

            // Ensure the Year is valid
            $year = isset($movieData['Year']) && is_numeric($movieData['Year']) ? intval($movieData['Year']) : 0;

            // Format the Released date to 'Y-m-d' format
            $releasedDate = null;
            if (isset($movieData['Released'])) {
                $releasedDate = date('Y-m-d', strtotime($movieData['Released']));
            }        

            $movie = Movie::create([
                'Title' => $movieData['Title'],
                'Year' => $year,
                'Rated' => $movieData['Rated'],
                'Released' => $releasedDate,
                'Runtime' => $movieData['Runtime'],
                'Genre' => $movieData['Genre'],
                'Director' => $movieData['Director'],
                'Writer' => $movieData['Writer'],
                'Actors' => $movieData['Actors'],
                'Plot' => $movieData['Plot'],
                'Language' => $movieData['Language'],
                'Country' => $movieData['Country'],
                'Awards' => $movieData['Awards'],
                'Poster' => $movieData['Poster'],
                'imdbID' => $movieData['imdbID'],
                'Type' => $movieData['Type'],
                'Metascore' => $movieData['Metascore'],
                'imdbRating' => $movieData['imdbRating'],
                'imdbVotes' => str_replace(['.', ','], '', $movieData['imdbVotes']),
                'BoxOffice' => $movieData['BoxOffice'] ?? null,
                'Production' => $movieData['Production'] ?? null,
                'Website' => $movieData['Website'] ?? null,
            ]);
        }        
    } 
}
