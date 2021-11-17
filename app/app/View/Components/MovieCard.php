<?php

namespace App\View\Components;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class MovieCard extends Component
{
    /**
     * Create a new component instance.
     *
     * @param Collection $movie
     */
    public function __construct(
        public Collection $movie,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.movie-card');
    }
}
