<?php

namespace RZP\Maatwebsite\Excel\Concerns;

use Illuminate\Database\Query\Builder;

interface FromQuery
{
    /**
     * @return Builder
     */
    public function query();
}
