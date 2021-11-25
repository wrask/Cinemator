<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class SearchDropdown extends Component
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
    private const SEARCH_MOVIE_API_REQUEST = 'search/movie?query=';

    /**
     * Minimum search string length
     *
     * @var int
     */
    private const MINIMUM_SEARCH_STRING_LENGTH = 2;

    /**
     * Search results number
     *
     * @var int
     */
    private const SEARCH_RESULTS_NUMBER = 7;

    public string $search = '';

    /**
     * Renders search results
     */
    public function render(): View|Factory|Application
    {
        $searchResults = [];

        if (strlen($this->search) >= self::MINIMUM_SEARCH_STRING_LENGTH) {
            $searchResults = Http::withToken(config(self::TMDB_TOKEN))
                ->get(self::TMDB_V3_ENDPOINT . self::SEARCH_MOVIE_API_REQUEST . $this->search)
                ->json('results');
        }

        return view('livewire.search-dropdown', [
            'searchResults' => collect($searchResults)->take(self::SEARCH_RESULTS_NUMBER),
        ]);
    }
}
