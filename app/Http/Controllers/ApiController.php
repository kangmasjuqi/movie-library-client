<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiService;

use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function getApiData(Request $request)
    {
        // Get the filter type and query from the request
        $searchType = $request->input('searchType', 'word'); // Default to 'word' search
        $searchQuery = $request->input('searchQuery', 'family'); // Default search term
        
        $page = $request->input('page', 1); // Default to page 1 if not provided
        $perPage = 5; // Number of movies per page

        if($searchQuery == null) {
            $searchQuery = 'family';
        }
    
        // Fetch movies using the API service, with the search key and pagination
        $movies = $this->apiService->getData($searchType, $searchQuery, $page, $perPage);
    
        // Get the authenticated user, if logged in
        $user = Auth::user();
    
        $result_movies = [];

        if($searchType == 'word') {
            if (isset($movies['Search'])) {
                foreach ($movies['Search'] as $movie) {
                    $movie['is_favorited'] = $user && $user->favoriteMovies()->where('imdbID', $movie['imdbID'])->exists();
                    $result_movies[] = $movie;
                }
            }    
        }
        else {
            if(!$movies) {
                return response()->json([]);
            }
            // got only 1 movie data
            $movies['is_favorited'] = $user && $user->favoriteMovies()->where('imdbID', $movies['imdbID'])->exists();
            $result_movies[] = $movies;
        }
    
        // Update the 'Search' key in the movies array
        $movies['Search'] = $result_movies;
    
        // Return the response as JSON
        return response()->json($movies);
    }
    
}
