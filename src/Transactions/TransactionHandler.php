<?php

namespace RZP\Maatwebsite\Excel\Transactions;

interface TransactionHandler
{
    /**
     * @param callable $callback
     *
     * @return mixed
     */
    public function __invoke(callable $callback);
}
