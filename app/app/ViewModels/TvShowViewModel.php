<?php

namespace App\ViewModels;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class TvShowViewModel extends ViewModel
{
    /**
     * Date format
     *
     * @var string
     */
    private const DATE_FORMAT = 'M d, Y';

    /**
     * Cast number
     *
     * @var int
     */
    private const CAST_NUMBER = 5;

    /**
     * Tv show images number
     *
     * @var int
     */
    private const TV_SHOW_IMAGES_NUMBER = 9;

    /**
     * Tv show poster path
     *
     * @var string
     */
    private const TV_SHOW_POSTER_PATH = 'https://image.tmdb.org/t/p/w500/';

    /**
     * Tv show placeholder path
     *
     * @var string
     */
    private const TV_SHOW_PLACEHOLDER_PATH = 'https://via.placeholder.com/500x750';

    /**
     * Profile path
     *
     * @var string
     */
    private const PROFILE_PATH = 'https://image.tmdb.org/t/p/w300';

    /**
     * Profile placeholder path
     *
     * @var string
     */
    private const PROFILE_PLACEHOLDER_PATH = 'https://via.placeholder.com/300x450';

    public function __construct(
        public readonly array $tvShow,
    ) {}

    /**
     * Gets Tv shows collection
     */
    public function tvShow(): Collection
    {
        return collect($this->tvShow)->merge([
            'poster_path' => $this->tvShow['poster_path'] ?? null
                ? self::TV_SHOW_POSTER_PATH . $this->tvShow['poster_path']
                : self::TV_SHOW_PLACEHOLDER_PATH,
            'vote_average' => $this->tvShow['vote_average'] * 10 . '%',
            'first_air_date' => Carbon::parse($this->tvShow['first_air_date'])->format(self::DATE_FORMAT),
            'genres' => collect($this->tvShow['genres'])->pluck('name')->flatten()->implode(', '),
            'cast' => collect($this->tvShow['credits']['cast'])->take(self::CAST_NUMBER)
                ->map(fn($cast) => collect($cast)->merge([
                'profile_path' => $cast['profile_path']
                    ? self::PROFILE_PATH . $cast['profile_path']
                    : self::PROFILE_PLACEHOLDER_PATH,
            ])),
            'images' => collect($this->tvShow['images']['backdrops'])->take(self::TV_SHOW_IMAGES_NUMBER),
        ])->only([
            'poster_path', 'id', 'genres', 'name',
            'vote_average', 'overview', 'first_air_date',
            'credits', 'videos', 'images', 'crew',
            'cast', 'images', 'created_by'
        ]);
    }
}
