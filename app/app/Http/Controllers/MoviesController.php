<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class MoviesController extends Controller
{
    /**
     * TMDB token
     *
     * @var string
     */
    const TMDB_TOKEN = 'services.tmdb.token';

    /**
     * TMDB version 3 endpoint
     *
     * @var string
     */
    const TMDB_V3_ENDPOINT = 'https://api.themoviedb.org/3/';

    /**
     * Popular movies api request
     *
     * @var string
     */
    const POPULAR_MOVIES_API_REQUEST = 'movie/popular';

    /**
     * Now playing movies api request
     *
     * @var string
     */
    const NOW_PLAYING_MOVIES_API_REQUEST = '/movie/now_playing';

    /**
     * Genres movies list api request
     *
     * @var string
     */
    const GENRES_LIST_API_REQUEST = 'genre/movie/list';

    /**
     * Append to request
     *
     * @var string
     */
    const APPEND_TO_RESPONSE = '?append_to_response=';

    /**
     * Request parameters to append
     *
     * @var array
     */
    const REQUEST_PARAMETERS_TO_APPEND = [
        'credits',
        'videos',
        'images',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws \Exception
     */
    public function index()
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

        $genres = collect($genres)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });

//        dump($nowPlayingMovies);

        return view('index', [
            'popularMovies' => $popularMovies,
            'nowPlayingMovies' => $nowPlayingMovies,
            'genres' => $genres,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function show(int $id)
    {
        $movie = Http::withToken(config(self::TMDB_TOKEN))
            ->get(
                self::TMDB_V3_ENDPOINT .
                'movie/' . $id .
                self::APPEND_TO_RESPONSE .
                implode(',', self::REQUEST_PARAMETERS_TO_APPEND))
            ->json();

//        dump($movie);

        return view('show', [
            'movie' => $movie
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function edit(int $id): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, int $id): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy(int $id): void
    {
        //
    }
}
