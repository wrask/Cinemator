<?php

use Carbon\Carbon;

const DATE_FORMAT = 'M d, Y';

if (!function_exists('getReleaseDate')) {
    function getReleaseDate(array $movie): string
    {
        return Carbon::parse($movie['release_date'])->format(DATE_FORMAT);
    }
}
