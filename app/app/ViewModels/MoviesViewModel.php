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
    private const DATE_FORMAT = 'M d, Y';

    /**
     * Images api endpoint
     *
     * @var string
     */
    private const IMAGES_API_ENDPOINT = 'https://image.tmdb.org/t/p/w500/';

    public function __construct(
        public readonly array $popularMovies,
        public readonly array $nowPlayingMovies,
        public readonly array $genres,
    ) {}

    /**
     * Gets popular movies
     */
    public function popularMovies(): Collection
    {
        return $this->getFormattedMovies($this->popularMovies);
    }

    /**
     * Gets now playing movies
     */
    public function nowPlayingMovies(): Collection
    {
        return $this->getFormattedMovies($this->nowPlayingMovies);
    }

    /**
     * Gets formatted movies
     */
    private function getFormattedMovies(array $movies): Collection
    {
        return collect($movies)->map(function($movie) {
            $formattedGenres = collect($movie['genre_ids'])
                ->mapWithKeys(fn($value) => [$value => $this->genres()->get($value)])->implode(', ');

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
     */
    public function genres(): Collection
    {
        return collect($this->genres)->mapWithKeys(fn($genre) => [$genre['id'] => $genre['name']]);
    }
}
