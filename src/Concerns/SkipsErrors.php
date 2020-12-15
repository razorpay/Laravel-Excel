<?php

namespace RZP\Maatwebsite\Excel\Concerns;

use Illuminate\Support\Collection;
use RZP\Maatwebsite\Excel\Validators\Failure;
use Throwable;

trait SkipsErrors
{
    /**
     * @var Failure[]
     */
    protected $errors = [];

    /**
     * @param Throwable $e
     */
    public function onError(Throwable $e)
    {
        $this->errors[] = $e;
    }

    /**
     * @return Throwable[]|Collection
     */
    public function errors(): Collection
    {
        return new Collection($this->errors);
    }
}
