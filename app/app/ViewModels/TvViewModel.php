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
    const DATE_FORMAT = 'M d, Y';

    /**
     * Poster path
     *
     * @var string
     */
    const POSTER_PATH = 'https://image.tmdb.org/t/p/w500/';

    /**
     * TvViewModel constructor.
     *
     * @param array $popularTv
     * @param array $topRatedTv
     * @param array $genres
     */
    public function __construct(
        public array $popularTv,
        public array $topRatedTv,
        public array $genres,
    ) {}

    /**
     * Gets popular tvs collection
     *
     * @return Collection
     */
    public function popularTv(): Collection
    {
        return $this->formatTv($this->popularTv);
    }

    /**
     * Gets popular toprated tv collection
     *
     * @return Collection
     */
    public function topRatedTv(): Collection
    {
        return $this->formatTv($this->topRatedTv);
    }

    /**
     * Gets genres collection
     *
     * @return Collection
     */
    public function genres(): Collection
    {
        return collect($this->genres)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });
    }

    /**
     * Gets formatted tvs collection
     *
     * @param array $tv
     * @return Collection
     */
    private function formatTv(array $tv): Collection
    {
        return collect($tv)->map(function($tvShow) {
            $formattedGenres = collect($tvShow['genre_ids'])->mapWithKeys(function($value) {
                return [$value => $this->genres()->get($value)];
            })->implode(', ');

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
