<?php

namespace RZP\Maatwebsite\Excel;

use Illuminate\Support\Collection;
use RZP\Maatwebsite\Excel\Concerns\ToArray;
use RZP\Maatwebsite\Excel\Concerns\ToCollection;
use RZP\Maatwebsite\Excel\Concerns\ToModel;
use RZP\Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use RZP\Maatwebsite\Excel\Concerns\WithMappedCells;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MappedReader
{
    /**
     * @param WithMappedCells $import
     * @param Worksheet       $worksheet
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function map(WithMappedCells $import, Worksheet $worksheet)
    {
        $mapped = [];
        foreach ($import->mapping() as $name => $coordinate) {
            $cell = Cell::make($worksheet, $coordinate);

            $mapped[$name] = $cell->getValue(
                null,
                $import instanceof WithCalculatedFormulas
            );
        }

        if ($import instanceof ToModel) {
            $model = $import->model($mapped);

            if ($model) {
                $model->saveOrFail();
            }
        }

        if ($import instanceof ToCollection) {
            $import->collection(new Collection($mapped));
        }

        if ($import instanceof ToArray) {
            $import->array($mapped);
        }
    }
}
