<?php

namespace RZP\Maatwebsite\Excel\Concerns;

use RZP\Maatwebsite\Excel\Row;

interface OnEachRow
{
    /**
     * @param Row $row
     */
    public function onRow(Row $row);
}
