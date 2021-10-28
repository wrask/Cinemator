<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class MovieCard extends Component
{
    /**
     * @var array
     */
    public array $movie;

    /**
     * @var Collection
     */
    public Collection $genres;

    /**
     * Create a new component instance.
     *
     * @param array $movie
     * @param Collection $genres
     */
    public function __construct(
        array $movie,
        Collection $genres
    ) {
        $this->movie = $movie;
        $this->genres = $genres;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function render()
    {
        return view('components.movie-card');
    }
}
