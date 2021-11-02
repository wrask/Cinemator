<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use App\ViewModels\ActorsViewModel;
use App\ViewModels\ActorViewModel;

class ActorsController extends Controller
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
     * Page request parameter
     *
     * @var string
     */
    const PAGE_REQUEST_PARAMETER = '?page=';

    /**
     * Popular movies api request
     *
     * @var string
     */
    const POPULAR_ACTORS_API_REQUEST = 'person/popular';

    /**
     * Person api request
     *
     * @var string
     */
    const PERSON_API_REQUEST = 'person/';

    /**
     * Max actors pages number
     *
     * @var int
     */
    const MAX_ACTORS_PAGES_NUMBER = 500;

    /**
     * No content response code
     *
     * @var int
     */
    const NO_CONTENT_RESPONSE_CODE = 204;

    /**
     * External ids api parameter
     *
     * @var string
     */
    const EXTERNAL_IDS_API_PARAMETER = '/external_ids';

    /**
     * Combined credits api parameter
     *
     * @var string
     */
    const COMBINED_CREDITS_API_PARAMETER = '/combined_credits';

    /**
     * @param int $page
     * @return Application|Factory|View
     */
    public function index(int $page = 1): View|Factory|Application
    {
        @abort_if($page > self::MAX_ACTORS_PAGES_NUMBER, self::NO_CONTENT_RESPONSE_CODE);

        $popularActors = Http::withToken(config(self::TMDB_TOKEN))
            ->get(
                self::TMDB_V3_ENDPOINT .
                self::POPULAR_ACTORS_API_REQUEST .
                self::PAGE_REQUEST_PARAMETER .
                $page
            )
            ->json('results');

        $actorsViewModel = new ActorsViewModel($popularActors, $page);

        return view('actors.index', $actorsViewModel);
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
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show actor controller
     *
     * @param int $actorId
     * @return Application|Factory|View
     */
    public function show(int $actorId): View|Factory|Application
    {
        $actor = Http::withToken(config(self::TMDB_TOKEN))
            ->get(self::TMDB_V3_ENDPOINT . self::PERSON_API_REQUEST . $actorId)
            ->json();

        $social = Http::withToken(config(self::TMDB_TOKEN))
            ->get(
                self::TMDB_V3_ENDPOINT .
                self::PERSON_API_REQUEST .
                $actorId .
                self::EXTERNAL_IDS_API_PARAMETER
            )
            ->json();

        $credits = Http::withToken(config(self::TMDB_TOKEN))
            ->get(
                self::TMDB_V3_ENDPOINT .
                self::PERSON_API_REQUEST .
                $actorId .
                self::COMBINED_CREDITS_API_PARAMETER
            )
            ->json();

        $actorViewModel = new ActorViewModel($actor, $social, $credits);

        return view('actors.show', $actorViewModel);
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
     * @param  \Illuminate\Http\Request  $request
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
