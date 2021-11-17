<?php

namespace App\ViewModels;

use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;
use Carbon\Carbon;

class MoviesViewModel extends ViewModel
{
    /**
     * Date format
     *
     * @var string
     */
    const DATE_FORMAT = 'M d, Y';

    /**
     * Images api endpoint
     *
     * @var string
     */
    const IMAGES_API_ENDPOINT = 'https://image.tmdb.org/t/p/w500/';

    /**
     * MoviesViewModel constructor.
     *
     * @param array $popularMovies
     * @param array $nowPlayingMovies
     * @param array $genres
     */
    public function __construct(
        public array $popularMovies,
        public array $nowPlayingMovies,
        public array $genres,
    ) {}

    /**
     * Gets popular movies
     *
     * @return Collection
     */
    public function popularMovies(): Collection
    {
        return $this->getFormattedMovies($this->popularMovies);
    }

    /**
     * Gets now playing movies
     *
     * @return Collection
     */
    public function nowPlayingMovies(): Collection
    {
        return $this->getFormattedMovies($this->nowPlayingMovies);
    }

    /**
     * Gets formatted movies
     *
     * @param array $movies
     * @return Collection
     */
    private function getFormattedMovies(array $movies): Collection
    {
        return collect($movies)->map(function($movie) {
            $formattedGenres = collect($movie['genre_ids'])->mapWithKeys(function ($value) {
                return [$value => $this->genres()->get($value)];
            })->implode(', ');

            return collect($movie)->merge([
                'poster_path' => self::IMAGES_API_ENDPOINT . $movie['poster_path'],
                'vote_average' => $movie['vote_average'] * 10 . '%',
                'release_date' => Carbon::parse($movie['release_date'])->format(DATE_FORMAT),
                'genres' => $formattedGenres,
            ])->only([
                'poster_path', 'id', 'genre_ids',
                'title', 'vote_average', 'overview',
                'release_date', 'genres',
            ]);
        });
    }

    /**
     * Gets genres
     *
     * @return Collection
     */
    public function genres(): Collection
    {
        return collect($this->genres)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });
    }
}
