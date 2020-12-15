<?php

namespace RZP\Maatwebsite\Excel;

use RZP\Maatwebsite\Excel\Concerns\Importable;
use RZP\Maatwebsite\Excel\Concerns\WithLimit;
use RZP\Maatwebsite\Excel\Concerns\WithMapping;
use RZP\Maatwebsite\Excel\Concerns\WithStartRow;
use RZP\Maatwebsite\Excel\Imports\HeadingRowFormatter;

class HeadingRowImport implements WithStartRow, WithLimit, WithMapping
{
    use Importable;

    /**
     * @var int
     */
    private $headingRow;

    /**
     * @param int $headingRow
     */
    public function __construct(int $headingRow = 1)
    {
        $this->headingRow = $headingRow;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return $this->headingRow;
    }

    /**
     * @return int
     */
    public function limit(): int
    {
        return 1;
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        return HeadingRowFormatter::format($row);
    }
}
