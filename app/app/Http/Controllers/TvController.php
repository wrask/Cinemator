<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\ViewModels\TvViewModel;
use App\ViewModels\TvShowViewModel;
use Illuminate\Support\Facades\Http;

class TvController extends Controller
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
     * TV popular api request
     *
     * @var string
     */
    private const TV_POPULAR_API_REQUEST = 'tv/popular';

    /**
     * TV top rated api request
     *
     * @var string
     */
    private const TV_TOP_RATED_API_REQUEST = 'tv/top_rated';

    /**
     * Genre tv list api api request
     *
     * @var string
     */
    private const GENRE_TV_LIST_API_REQUEST = 'genre/tv/list';

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
     * Display the specified resource
     */
    public function index(): View|Factory|Application
    {
        $popularTv = Http::withToken(config(self::TMDB_TOKEN))
            ->get(self::TMDB_V3_ENDPOINT . self::TV_POPULAR_API_REQUEST)
            ->json('results');

        $topRatedTv = Http::withToken(config(self::TMDB_TOKEN))
            ->get(self::TMDB_V3_ENDPOINT . self::TV_TOP_RATED_API_REQUEST)
            ->json('results');

        $genres = Http::withToken(config(self::TMDB_TOKEN))
            ->get(self::TMDB_V3_ENDPOINT . self::GENRE_TV_LIST_API_REQUEST)
            ->json('genres');

        $tvViewModel = new TvViewModel(
            popularTv: $popularTv,
            topRatedTv: $topRatedTv,
            genres: $genres,
        );

        return view('tv.index', $tvViewModel);
    }

    /**
     * Display the specified resource
     */
    public function show(int $id): View|Factory|Application
    {
        $tvShow = Http::withToken(config(self::TMDB_TOKEN))
            ->get(
                self::TMDB_V3_ENDPOINT .
                'tv/' . $id .
                self::APPEND_TO_RESPONSE .
                implode(',', self::REQUEST_PARAMETERS_TO_APPEND))
            ->json();

        if (isResponseValid($tvShow)) {
            return abort(404);
        }

        $tvShowViewModel = new TvShowViewModel(tvShow: $tvShow);

        return view('tv.show', $tvShowViewModel);
    }
}
