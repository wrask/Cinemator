<?php

namespace App\View\Components;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class TvCard extends Component
{
    public function __construct(
        public readonly Collection $tvShow,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Factory|Application
    {
        return view('components.tv-card');
    }
}
