<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieFavorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'imdbID'];

    // Relationship: A favorite belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: A favorite movie is linked by imdbID to a Movie
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'imdbID', 'imdbID');
    }
    
}
