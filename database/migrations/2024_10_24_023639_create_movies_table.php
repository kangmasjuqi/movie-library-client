<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('Title');
            $table->year('Year');
            $table->string('Rated')->nullable();
            $table->date('Released')->nullable();
            $table->string('Runtime')->nullable();
            $table->string('Genre')->nullable();
            $table->string('Director')->nullable();
            $table->string('Writer')->nullable();
            $table->string('Actors')->nullable();
            $table->text('Plot')->nullable();
            $table->string('Language')->nullable();
            $table->string('Country')->nullable();
            $table->string('Awards')->nullable();
            $table->string('Poster')->nullable();
            $table->string('imdbID')->unique();
            $table->string('Type');  // movie, series, episode
            $table->string('Metascore')->nullable();
            $table->decimal('imdbRating', 3, 1)->nullable();
            $table->integer('imdbVotes')->nullable();
            $table->string('BoxOffice')->nullable();
            $table->string('Production')->nullable();
            $table->string('Website')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
