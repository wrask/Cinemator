<?php

namespace App\ViewModels;

use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class ActorsViewModel extends ViewModel
{
    /**
     * Images api endpoint
     *
     * @var string
     */
    private const IMAGES_API_ENDPOINT = 'https://image.tmdb.org/t/p/w235_and_h235_face';

    /**
     * Actor avatar placeholder path
     *
     * @var string
     */
    private const ACTOR_AVATAR_PLACEHOLDER_PATH = 'https://ui-avatars.com/api/?size=235&name';

    /**
     * Max actors pages number
     *
     * @var int
     */
    private const MAX_ACTORS_PAGES_NUMBER = 500;

    public function __construct(
        public readonly array $popularActors,
        public readonly int $page,
    ) {}

    /**
     * Gets popular actors
     */
    public function popularActors(): Collection
    {
        return collect($this->popularActors)->map(fn($actor) => collect($actor)->merge([
            'profile_path' => $actor['profile_path'] ?
                self::IMAGES_API_ENDPOINT . $actor['profile_path'] :
                self::ACTOR_AVATAR_PLACEHOLDER_PATH . $actor['name'],
            'known_for' => collect($actor['known_for'])->where('media_type', 'movie')->pluck('title')->union(
                collect($actor['known_for'])->where('media_type', 'tv')->pluck('name')
            )->implode(', '),
        ])->only([
            'name', 'id', 'profile_path', 'known_for',
        ]));
    }

    /**
     * Gets previous page
     */
    public function previous(): ?int
    {
        return $this->page > 1 ? $this->page - 1 : null;
    }

    /**
     * Gets next page
     */
    public function next(): ?int
    {
        return $this->page < self::MAX_ACTORS_PAGES_NUMBER ? $this->page + 1 : null;
    }
}
