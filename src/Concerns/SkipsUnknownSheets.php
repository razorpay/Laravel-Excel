<?php

namespace RZP\Maatwebsite\Excel\Concerns;

interface SkipsUnknownSheets
{
    /**
     * @param string|int $sheetName
     */
    public function onUnknownSheet($sheetName);
}
