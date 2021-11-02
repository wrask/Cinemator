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
    const DATE_FORMAT = 'M d, Y';

    /**
     * Cast number
     *
     * @var int
     */
    const CAST_NUMBER = 5;

    /**
     * Tv show images number
     *
     * @var int
     */
    const TV_SHOW_IMAGES_NUMBER = 9;

    /**
     * @var array
     */
    public array $tvShow;

    /**
     * TvShowViewModel constructor.
     *
     * @param array $tvShow
     */
    public function __construct(
        array $tvShow
    ) {
        $this->tvShow = $tvShow;
    }

    /**
     * Gets Tv shows collection
     *
     * @return Collection
     */
    public function tvShow(): Collection
    {
        return collect($this->tvShow)->merge([
            'poster_path' => $this->tvShow['poster_path']
                ? 'https://image.tmdb.org/t/p/w500/' . $this->tvShow['poster_path']
                : 'https://via.placeholder.com/500x750',
            'vote_average' => $this->tvShow['vote_average'] * 10 . '%',
            'first_air_date' => Carbon::parse($this->tvShow['first_air_date'])->format(self::DATE_FORMAT),
            'genres' => collect($this->tvShow['genres'])->pluck('name')->flatten()->implode(', '),
            'cast' => collect($this->tvShow['credits']['cast'])->take(self::CAST_NUMBER)->map(function($cast) {
                return collect($cast)->merge([
                    'profile_path' => $cast['profile_path']
                        ? 'https://image.tmdb.org/t/p/w300' . $cast['profile_path']
                        : 'https://via.placeholder.com/300x450',
                ]);
            }),
            'images' => collect($this->tvShow['images']['backdrops'])->take(self::TV_SHOW_IMAGES_NUMBER),
        ])->only([
            'poster_path', 'id', 'genres', 'name',
            'vote_average', 'overview', 'first_air_date',
            'credits', 'videos', 'images', 'crew',
            'cast', 'images', 'created_by'
        ]);
    }
}
