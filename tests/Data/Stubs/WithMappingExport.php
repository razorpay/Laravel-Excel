<?php

namespace RZP\Maatwebsite\Excel\Tests\Data\Stubs;

use Illuminate\Support\Collection;
use RZP\Maatwebsite\Excel\Concerns\Exportable;
use RZP\Maatwebsite\Excel\Concerns\FromCollection;
use RZP\Maatwebsite\Excel\Concerns\WithMapping;

class WithMappingExport implements FromCollection, WithMapping
{
    use Exportable;

    /**
     * @return Collection
     */
    public function collection()
    {
        return collect([
            ['A1', 'B1', 'C1'],
            ['A2', 'B2', 'C2'],
        ]);
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        return [
            'mapped-' . $row[0],
            'mapped-' . $row[1],
            'mapped-' . $row[2],
        ];
    }
}
