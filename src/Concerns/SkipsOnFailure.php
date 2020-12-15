<?php

namespace RZP\Maatwebsite\Excel\Concerns;

use RZP\Maatwebsite\Excel\Validators\Failure;

interface SkipsOnFailure
{
    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures);
}
