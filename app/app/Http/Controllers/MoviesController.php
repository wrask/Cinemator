<?php

namespace App\Http\Controllers;

use App\ViewModels\MoviesViewModel;
use App\ViewModels\MovieViewModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;

class MoviesController extends Controller
{
    /**
     * TMDB token
     *
     * @var string
     */
    private const TMDB_TOKEN = 'services.tmdb.token';

    /**
     * TMDB version 3 endpoint
     *
     * @var string
     */
    private const TMDB_V3_ENDPOINT = 'https://api.themoviedb.org/3/';

    /**
     * Popular movies api request
     *
     * @var string
     */
    private const POPULAR_MOVIES_API_REQUEST = 'movie/popular';

    /**
     * Now playing movies api request
     *
     * @var string
     */
    private const NOW_PLAYING_MOVIES_API_REQUEST = '/movie/now_playing';

    /**
     * Genres movies list api request
     *
     * @var string
     */
    private const GENRES_LIST_API_REQUEST = 'genre/movie/list';

    /**
     * Append to request
     *
     * @var string
     */
    private const APPEND_TO_RESPONSE = '?append_to_response=';

    /**
     * Request parameters to append
     *
     * @var array
     */
    private const REQUEST_PARAMETERS_TO_APPEND = [
        'credits',
        'videos',
        'images',
    ];

    /**
     * Display a listing of the resource
     */
    public function index(): View|Factory|Application
    {
        $popularMovies = Http::withToken(config(self::TMDB_TOKEN))
            ->get(self::TMDB_V3_ENDPOINT . self::POPULAR_MOVIES_API_REQUEST)
            ->json('results');

        $nowPlayingMovies = Http::withToken(config(self::TMDB_TOKEN))
            ->get(self::TMDB_V3_ENDPOINT . self::NOW_PLAYING_MOVIES_API_REQUEST)
            ->json('results');

        $genres= Http::withToken(config(self::TMDB_TOKEN))
            ->get(self::TMDB_V3_ENDPOINT . self::GENRES_LIST_API_REQUEST)
            ->json('genres');

        $moviesViewModel = new MoviesViewModel(
            popularMovies: $popularMovies,
            nowPlayingMovies: $nowPlayingMovies,
            genres: $genres,
        );

        return view('movies.index', $moviesViewModel);
    }

    /**
     * Display the specified resource
     */
    public function show(int $id): View|Factory|Application
    {
        $movie = Http::withToken(config(self::TMDB_TOKEN))
            ->get(
                self::TMDB_V3_ENDPOINT .
                'movie/' . $id .
                self::APPEND_TO_RESPONSE .
                implode(',', self::REQUEST_PARAMETERS_TO_APPEND))
            ->json();

        if (isset($movie['success']) && !$movie['success']) {
            return abort(404);
        }

        $movieViewModel = new MovieViewModel(movie: $movie);

        return view('movies.show', $movieViewModel);
    }
}
