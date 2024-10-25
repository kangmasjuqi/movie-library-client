<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/movies', [\App\Http\Controllers\MovieController::class, 'index'])->middleware('auth')->name('movies.index');
Route::get('/movies/favorites', [\App\Http\Controllers\MovieController::class, 'myFavoriteMovies'])->middleware('auth')->name('movies.favorites');
Route::get('/movies/{imdbID}', [\App\Http\Controllers\MovieController::class, 'showMovieDetail'])->middleware('auth')->name('movies.show');

Route::post('/favorites/add/{imdbID}', [\App\Http\Controllers\MovieController::class, 'addFavorite'])->middleware('auth')->name('favorites.add');
Route::delete('/favorites/remove/{imdbID}', [\App\Http\Controllers\MovieController::class, 'removeFavorite'])->middleware('auth')->name('favorites.remove');

Route::get('/api-data', [\App\Http\Controllers\ApiController::class, 'getApiData']);
