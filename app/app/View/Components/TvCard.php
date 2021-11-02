<?php

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class TvCard extends Component
{
    /**
     * @var Collection
     */
    public Collection $tvShow;

    /**
     * TvCard constructor.
     *
     * @param Collection $tvShow
     */
    public function __construct(
        Collection $tvShow
    ) {
        $this->tvShow = $tvShow;
    }

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
