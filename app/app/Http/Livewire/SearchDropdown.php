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
    const SEARCH_MOVIE_API_REQUEST = 'search/movie?query=';

    /**
     * Minimum search string length
     *
     * @var int
     */
    const MINIMUM_SEARCH_STRING_LENGTH = 2;

    /**
     * Search results number
     *
     * @var int
     */
    const SEARCH_RESULTS_NUMBER = 7;

    /**
     * @var string
     */
    public string $search = '';

    /**
     * Renders search results
     *
     * @return Application|Factory|View
     */
    public function render()
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
