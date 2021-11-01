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
    const DATE_FORMAT = 'M d, Y';

    /**
     * Twitter url
     *
     * @var string
     */
    const TWITTER_URL = 'https://twitter.com/';


    /**
     * Facebook url
     *
     * @var string
     */
    const FACEBOOK_URL = 'https://facebook.com/';

    /**
     * Instagram url
     *
     * @var string
     */
    const INSTAGRAM_URL = 'https://instagram.com/';

    /**
     * Known for movies number
     *
     * @var int
     */
    const KNOWN_FOR_MOVIES_NUMBER = 5;

    public $actor;

    public $social;

    public $credits;

    public function __construct(
        $actor,
        $social,
        $credits
    ) {
        $this->actor = $actor;
        $this->social = $social;
        $this->credits = $credits;
    }

    /**
     * Gets social collection
     *
     * @return Collection
     */
    public function actor(): Collection
    {
        return collect($this->actor)->merge([
            'birthday' => Carbon::parse($this->actor['birthday'])->format(self::DATE_FORMAT),
            'age' => Carbon::parse($this->actor['birthday'])->age,
            'profile_path' => $this->actor['profile_path']
                ? 'https://image.tmdb.org/t/p/w300/'.$this->actor['profile_path']
                : 'https://via.placeholder.com/300x450',
        ])->only([
            'birthday', 'age', 'profile_path', 'name', 'id', 'homepage', 'place_of_birth', 'biography'
        ]);
    }

    /**
     * Gets social collection
     *
     * @return Collection
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
     *
     * @return Collection
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
                    ? 'https://image.tmdb.org/t/p/w185' . $movie['poster_path']
                    : 'https://via.placeholder.com/185x278',
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
     *
     * @return Collection
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
                $title = 'Untitled';
            }

            return collect($movie)->merge([
                'release_date' => $releaseDate,
                'release_year' => isset($releaseDate) ? Carbon::parse($releaseDate)->format('Y') : 'Future',
                'title' => $title,
                'character' => $movie['character'] ?? '',
                'linkToPage' => $movie['media_type'] == 'movie' ?
                    route('movies.show', $movie['id']) :
                    route('tv.show', $movie['id']),
            ])->only([
                'release_date', 'release_year', 'title', 'character', 'linkToPage',
            ]);
        })->sortByDesc('release_date');
    }
}
