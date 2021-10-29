<?php

namespace App\ViewModels;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class MovieViewModel extends ViewModel
{
    /**
     * Crew members number
     *
     * @var int
     */
    const CREW_MEMBERS_NUMBER = 5;

    /**
     * Cast number
     *
     * @var int
     */
    const CAST_NUMBER = 5;

    /**
     * Images number
     *
     * @var int
     */
    const IMAGES_NUMBER = 9;

    /**
     * Date format
     *
     * @var string
     */
    const DATE_FORMAT = 'M d, Y';

    /**
     * Poster image placeholder url
     *
     * @var string
     */
    const POSTER_IMAGE_PLACEHOLDER_URL = 'https://via.placeholder.com/500x750';

    /**
     * Cast image placeholder url
     *
     * @var string
     */
    const CAST_IMAGE_PLACEHOLDER_URL = 'https://via.placeholder.com/300x450';

    /**
     * Poster images api endpoint
     *
     * @var string
     */
    const POSTER_IMAGES_API_ENDPOINT = 'https://image.tmdb.org/t/p/w500/';

    /**
     * Cast images api endpoint
     *
     * @var string
     */
    const CAST_IMAGES_API_ENDPOINT = 'https://image.tmdb.org/t/p/w300';

    /**
     * @var array
     */
    public array $movie;

    /**
     * MovieViewModel constructor.
     *
     * @param array $movie
     */
    public function __construct(
        array $movie
    ) {
        $this->movie = $movie;
    }

    /**
     * Gets movies collection
     *
     * @return Collection
     */
    public function movie(): Collection
    {
        return collect($this->movie)->merge([
            'poster_path' => $this->movie['poster_path']
                ? self::POSTER_IMAGES_API_ENDPOINT . $this->movie['poster_path']
                : self::POSTER_IMAGE_PLACEHOLDER_URL,
            'vote_average' => $this->movie['vote_average'] * 10 .'%',
            'release_date' => Carbon::parse($this->movie['release_date'])->format(self::DATE_FORMAT),
            'genres' => collect($this->movie['genres'])->pluck('name')->flatten()->implode(', '),
            'crew' => collect($this->movie['credits']['crew'])->take(self::CREW_MEMBERS_NUMBER),
            'cast' => collect($this->movie['credits']['cast'])->take(self::CAST_NUMBER)->map(function($cast) {
                return collect($cast)->merge([
                    'profile_path' => $cast['profile_path']
                        ? self::CAST_IMAGES_API_ENDPOINT . $cast['profile_path']
                        : self::CAST_IMAGE_PLACEHOLDER_URL,
                ]);
            }),
            'images' => collect($this->movie['images']['backdrops'])->take(self::IMAGES_NUMBER),
        ])->only([
            'poster_path', 'id', 'genres', 'title', 'vote_average',
            'overview', 'release_date', 'credits', 'videos', 'images',
            'crew', 'cast', 'images'
        ]);
    }
}
