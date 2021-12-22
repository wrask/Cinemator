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
    private const CREW_MEMBERS_NUMBER = 3;

    /**
     * Cast number
     *
     * @var int
     */
    private const CAST_NUMBER = 5;

    /**
     * Images number
     *
     * @var int
     */
    private const IMAGES_NUMBER = 9;

    /**
     * Date format
     *
     * @var string
     */
    private const DATE_FORMAT = 'M d, Y';

    /**
     * Poster image placeholder url
     *
     * @var string
     */
    private const POSTER_IMAGE_PLACEHOLDER_URL = 'https://via.placeholder.com/500x750';

    /**
     * Cast image placeholder url
     *
     * @var string
     */
    private const CAST_IMAGE_PLACEHOLDER_URL = 'https://via.placeholder.com/300x450';

    /**
     * Poster images api endpoint
     *
     * @var string
     */
    private const POSTER_IMAGES_API_ENDPOINT = 'https://image.tmdb.org/t/p/w500/';

    /**
     * Cast images api endpoint
     *
     * @var string
     */
    private const CAST_IMAGES_API_ENDPOINT = 'https://image.tmdb.org/t/p/w300';

    public function __construct(
        public readonly array $movie,
    ) {}

    /**
     * Gets movies collection
     */
    public function movie(): Collection
    {
        $voteAverage = isset($this->movie['vote_average']) ? $this->movie['vote_average'] * 10 .'%' : null;
        $releaseDate = isset($this->movie['release_date']) ?
            Carbon::parse($this->movie['release_date'])->format(self::DATE_FORMAT) : null;
        $genres = isset($this->movie['genres']) ?
            collect($this->movie['genres'])->pluck('name')->flatten()->implode(', ') : null;
        $crew = isset($this->movie['credits']['crew']) ?
            collect($this->movie['credits']['crew'])->take(self::CREW_MEMBERS_NUMBER) : null;
        $cast = isset($this->movie['credits']['cast']) ? collect($this->movie['credits']['cast'])->take(self::CAST_NUMBER)
            ->map(fn($cast) => collect($cast)->merge([
                'profile_path' => $cast['profile_path']
                    ? self::CAST_IMAGES_API_ENDPOINT . $cast['profile_path']
                    : self::CAST_IMAGE_PLACEHOLDER_URL,
            ])) : null;
        $images = isset($this->movie['images']['backdrops']) ? collect($this->movie['images']['backdrops'])->take(self::IMAGES_NUMBER) : null;

        return collect($this->movie)->merge([
            'poster_path' => $this->movie['poster_path'] ?? null
                ? self::POSTER_IMAGES_API_ENDPOINT . $this->movie['poster_path']
                : self::POSTER_IMAGE_PLACEHOLDER_URL,
            'vote_average' => $voteAverage,
            'release_date' => $releaseDate,
            'genres' => $genres,
            'crew' => $crew,
            'cast' => $cast,
            'images' => $images,
        ])->only([
            'poster_path', 'id', 'genres', 'title', 'vote_average',
            'overview', 'release_date', 'credits', 'videos', 'images',
            'crew', 'cast', 'images'
        ]);
    }
}
