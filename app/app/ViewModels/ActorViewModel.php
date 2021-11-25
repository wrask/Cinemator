<?php

namespace App\ViewModels;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class ActorViewModel extends ViewModel
{
    /**
     * Date format
     *
     * @var string
     */
    private const DATE_FORMAT = 'M d, Y';

    /**
     * Twitter url
     *
     * @var string
     */
    private const TWITTER_URL = 'https://twitter.com/';

    /**
     * Facebook url
     *
     * @var string
     */
    private const FACEBOOK_URL = 'https://facebook.com/';

    /**
     * Instagram url
     *
     * @var string
     */
    private const INSTAGRAM_URL = 'https://instagram.com/';

    /**
     * Known for movies number
     *
     * @var int
     */
    private const KNOWN_FOR_MOVIES_NUMBER = 5;

    /**
     * Future release date
     *
     * @var string
     */
    private const FUTURE_RELEASE_DATE = 'Future';

    /**
     * Release date format
     *
     * @var string
     */
    private const RELEASE_DATE_FORMAT = 'Y';

    /**
     * Untitled movie name
     *
     * @var string
     */
    private const UNTITLED_MOVIE_NAME = 'Untitled';

    /**
     * Actor image path
     *
     * @var string
     */
    private const ACTOR_IMAGE_PATH = 'https://image.tmdb.org/t/p/w300/';

    /**
     * Actor image placeholder path
     *
     * @var string
     */
    private const ACTOR_IMAGE_PLACEHOLDER_PATH = 'https://via.placeholder.com/300x450';

    /**
     * Poster image path
     *
     * @var string
     */
    private const POSTER_IMAGE_PATH = 'https://image.tmdb.org/t/p/w300';

    /**
     * Poster placeholder path
     *
     * @var string
     */
    private const POSTER_PLACEHOLDER_PATH = 'https://via.placeholder.com/185x278';

    /**
     * Movie media type
     *
     * @var string
     */
    private const MOVIE_MEDIA_TYPE = 'movie';

    public function __construct(
        public readonly  array $actor,
        public readonly array $social,
        public readonly array $credits,
    ) {}

    /**
     * Gets actors collection
     */
    public function actor(): Collection
    {
        return collect($this->actor)->merge([
            'birthday' => Carbon::parse($this->actor['birthday'])->format(self::DATE_FORMAT),
            'age' => Carbon::parse($this->actor['birthday'])->age,
            'profile_path' => $this->actor['profile_path']
                ? self::ACTOR_IMAGE_PATH . $this->actor['profile_path']
                : self::ACTOR_IMAGE_PLACEHOLDER_PATH,
        ])->only([
            'birthday', 'age', 'profile_path', 'name',
            'id', 'homepage', 'place_of_birth', 'biography'
        ]);
    }

    /**
     * Gets social collection
     */
    public function social(): Collection
    {
        return collect($this->social)->merge([
            'twitter' => $this->social['twitter_id'] ? self::TWITTER_URL . $this->social['twitter_id'] : null,
            'facebook' => $this->social['facebook_id'] ? self::FACEBOOK_URL . $this->social['facebook_id'] : null,
            'instagram' => $this->social['instagram_id'] ? self::INSTAGRAM_URL . $this->social['instagram_id'] : null,
        ])->only([
            'facebook', 'instagram', 'twitter',
        ]);
    }

    /**
     * Gets knownForMovies collection
     */
    public function knownForMovies(): Collection
    {
        $castMovies = collect($this->credits)->get('cast');

        return collect($castMovies)->sortByDesc('popularity')->take(self::KNOWN_FOR_MOVIES_NUMBER)->map(function($movie) {
            if (isset($movie['title'])) {
                $title = $movie['title'];
            } elseif (isset($movie['name'])) {
                $title = $movie['name'];
            } else {
                $title = 'Untitled';
            }

            return collect($movie)->merge([
                'poster_path' => $movie['poster_path']
                    ? self::POSTER_IMAGE_PATH . $movie['poster_path']
                    : self::POSTER_PLACEHOLDER_PATH,
                'title' => $title,
                'linkToPage' => $movie['media_type'] === 'movie' ?
                    route('movies.show', $movie['id']) :
                    route('tv.show', $movie['id'])
            ])->only([
                'poster_path', 'title', 'id', 'media_type', 'linkToPage',
            ]);
        });
    }

    /**
     * Gets credits collection
     */
    public function credits(): Collection
    {
        $castMovies = collect($this->credits)->get('cast');

        return collect($castMovies)->map(function($movie) {
            if (isset($movie['release_date'])) {
                $releaseDate = $movie['release_date'];
            } elseif (isset($movie['first_air_date'])) {
                $releaseDate = $movie['first_air_date'];
            } else {
                $releaseDate = '';
            }

            if (isset($movie['title'])) {
                $title = $movie['title'];
            } elseif (isset($movie['name'])) {
                $title = $movie['name'];
            } else {
                $title = self::UNTITLED_MOVIE_NAME;
            }

            return collect($movie)->merge([
                'release_date' => $releaseDate,
                'release_year' => isset($releaseDate) ?
                    Carbon::parse($releaseDate)->format(self::RELEASE_DATE_FORMAT) :
                    self::FUTURE_RELEASE_DATE,
                'title' => $title,
                'character' => $movie['character'] ?? '',
                'linkToPage' => $movie['media_type'] == self::MOVIE_MEDIA_TYPE ?
                    route('movies.show', $movie['id']) :
                    route('tv.show', $movie['id']),
            ])->only([
                'release_date', 'release_year', 'title', 'character', 'linkToPage',
            ]);
        })->sortByDesc('release_date');
    }
}
