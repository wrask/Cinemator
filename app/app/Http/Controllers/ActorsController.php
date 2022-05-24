<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
    private const TMDB_TOKEN = 'services.tmdb.token';

    /**
     * TMDB version 3 endpoint
     *
     * @var string
     */
    private const TMDB_V3_ENDPOINT = 'https://api.themoviedb.org/3/';

    /**
     * Page request parameter
     *
     * @var string
     */
    private const PAGE_REQUEST_PARAMETER = '?page=';

    /**
     * Popular movies api request
     *
     * @var string
     */
    private const POPULAR_ACTORS_API_REQUEST = 'person/popular';

    /**
     * Person api request
     *
     * @var string
     */
    private const PERSON_API_REQUEST = 'person/';

    /**
     * Max actors pages number
     *
     * @var int
     */
    private const MAX_ACTORS_PAGES_NUMBER = 500;

    /**
     * No content response code
     *
     * @var int
     */
    private const NO_CONTENT_RESPONSE_CODE = 204;

    /**
     * External ids api parameter
     *
     * @var string
     */
    private const EXTERNAL_IDS_API_PARAMETER = '/external_ids';

    /**
     * Combined credits api parameter
     *
     * @var string
     */
    private const COMBINED_CREDITS_API_PARAMETER = '/combined_credits';

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

        $actorsViewModel = new ActorsViewModel(
            popularActors: $popularActors,
            page: $page
        );

        return view('actors.index', $actorsViewModel);
    }

    /**
     * Show actor controller
     */
    public function show(int $actorId): View|Factory|Application
    {
        $actor = Http::withToken(config(self::TMDB_TOKEN))
            ->get(self::TMDB_V3_ENDPOINT . self::PERSON_API_REQUEST . $actorId)
            ->json();

        if (isResponseValid($actor)) {
            return abort(404);
        }

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

        $actorViewModel = new ActorViewModel(
            actor: $actor,
            social: $social,
            credits: $credits
        );

        return view('actors.show', $actorViewModel);
    }
}
