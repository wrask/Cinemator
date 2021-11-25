<?php

namespace App\ViewModels;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class TvViewModel extends ViewModel
{
    /**
     * Date format
     *
     * @var string
     */
    private const DATE_FORMAT = 'M d, Y';

    /**
     * Poster path
     *
     * @var string
     */
    private const POSTER_PATH = 'https://image.tmdb.org/t/p/w500/';

    public function __construct(
        public readonly array $popularTv,
        public readonly array $topRatedTv,
        public readonly array $genres,
    ) {}

    /**
     * Gets popular tvs collection
     */
    public function popularTv(): Collection
    {
        return $this->formatTv($this->popularTv);
    }

    /**
     * Gets popular top_rated tv collection
     */
    public function topRatedTv(): Collection
    {
        return $this->formatTv($this->topRatedTv);
    }

    /**
     * Gets genres collection
     */
    public function genres(): Collection
    {
        return collect($this->genres)->mapWithKeys(fn($genre) => [$genre['id'] => $genre['name']]);
    }

    /**
     * Gets formatted tvs collection
     */
    private function formatTv(array $tv): Collection
    {
        return collect($tv)->map(function($tvShow) {
            $formattedGenres = collect($tvShow['genre_ids'])
                ->mapWithKeys(fn($value) => [$value => $this->genres()->get($value)])->implode(', ');

            return collect($tvShow)->merge([
                'poster_path' => self::POSTER_PATH . $tvShow['poster_path'],
                'vote_average' => $tvShow['vote_average'] * 10 .'%',
                'first_air_date' => Carbon::parse($tvShow['first_air_date'])->format(self::DATE_FORMAT),
                'genres' => $formattedGenres,
            ])->only([
                'poster_path', 'id', 'genre_ids',
                'name', 'vote_average', 'overview',
                'first_air_date', 'genres',
            ]);
        });
    }
}
