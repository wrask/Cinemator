<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\ViewModels\TvViewModel;
use App\ViewModels\TvShowViewModel;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class TvController extends Controller
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
     * TV popular api request
     *
     * @var string
     */
    const TV_POPULAR_API_REQUEST = 'tv/popular';

    /**
     * TV top rated api request
     *
     * @var string
     */
    const TV_TOP_RATED_API_REQUEST = 'tv/top_rated';

    /**
     * Genre tv list api api request
     *
     * @var string
     */
    const GENRE_TV_LIST_API_REQUEST = 'genre/tv/list';

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
     * @return Application|Factory|View
     */
    public function index()
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

        $viewModel = new TvViewModel(
            $popularTv,
            $topRatedTv,
            $genres,
        );

        return view('tv.index', $viewModel);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @param int $id
     * @return Application|Factory|View
     */
    public function show(int $id)
    {
        $tvShow = Http::withToken(config(self::TMDB_TOKEN))
            ->get(
                self::TMDB_V3_ENDPOINT .
                'tv/' . $id .
                self::APPEND_TO_RESPONSE .
                implode(',', self::REQUEST_PARAMETERS_TO_APPEND))
            ->json();

        $tvShowViewModel = new TvShowViewModel($tvShow);

        return view('tv.show', $tvShowViewModel);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
