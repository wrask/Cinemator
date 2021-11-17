<?php

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class TvCard extends Component
{
    /**
     * TvCard constructor.
     *
     * @param Collection $tvShow
     */
    public function __construct(
        public Collection $tvShow,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        return view('components.tv-card');
    }
}
