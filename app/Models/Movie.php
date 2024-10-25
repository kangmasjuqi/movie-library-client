<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'Title', 'Year', 'Rated', 'Released', 'Runtime', 'Genre', 'Director', 
        'Writer', 'Actors', 'Plot', 'Language', 'Country', 'Awards', 'Poster', 
        'imdbID', 'Type', 'Metascore', 'imdbRating', 'imdbVotes', 'BoxOffice', 
        'Production', 'Website'
    ];

    // Relationship: A movie can be favorited by many users
    public function favorites()
    {
        return $this->hasMany(MovieFavorite::class, 'imdbID', 'imdbID');
    }

    // Check if a movie is favorited by a specific user
    public function isFavoritedBy($userId)
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }
}
