<?php

namespace App\ViewModels;

use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class ActorsViewModel extends ViewModel
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
    const IMAGES_API_ENDPOINT = 'https://image.tmdb.org/t/p/w235_and_h235_face';

    /**
     * Actor avatar placeholder path
     *
     * @var string
     */
    const ACTOR_AVATAR_PLACEHOLDER_PATH = 'https://ui-avatars.com/api/?size=235&name';

    /**
     * Max actors pages number
     *
     * @var int
     */
    const MAX_ACTORS_PAGES_NUMBER = 500;

    /**
     * @var array
     */
    public array $popularActors;

    /**
     * @var int
     */
    public int $page;

    /**
     * ActorsViewModel constructor.
     *
     * @param array $popularActors
     * @param int $page
     */
    public function __construct(
        array $popularActors,
        int $page
    ) {
        $this->popularActors = $popularActors;
        $this->page = $page;
    }

    /**
     * Gets popular actors
     *
     * @return Collection
     */
    public function popularActors(): Collection
    {
        return collect($this->popularActors)->map(function($actor) {
            return collect($actor)->merge([
                'profile_path' => $actor['profile_path'] ?
                    self::IMAGES_API_ENDPOINT . $actor['profile_path'] :
                    self::ACTOR_AVATAR_PLACEHOLDER_PATH . $actor['name'],
                'known_for' => collect($actor['known_for'])->where('media_type', 'movie')->pluck('title')->union(
                    collect($actor['known_for'])->where('media_type', 'tv')->pluck('name')
                )->implode(', '),
            ])->only([
                'name', 'id', 'profile_path', 'known_for',
            ]);
        });
    }

    /**
     * Gets previous page
     *
     * @return int|null
     */
    public function previous(): ?int
    {
        return $this->page > 1 ? $this->page - 1 : null;
    }

    /**
     * Gets next page
     *
     * @return int|null
     */
    public function next(): ?int
    {
        return $this->page < self::MAX_ACTORS_PAGES_NUMBER ? $this->page + 1 : null;
    }
}
