<?php

namespace RZP\Maatwebsite\Excel\Tests\Concerns;

use RZP\Maatwebsite\Excel\Concerns\Exportable;
use RZP\Maatwebsite\Excel\Concerns\FromArray;
use RZP\Maatwebsite\Excel\Tests\TestCase;

class FromArrayTest extends TestCase
{
    /**
     * @test
     */
    public function can_export_from_array()
    {
        $export = new class implements FromArray {
            use Exportable;

            /**
             * @return array
             */
            public function array(): array
            {
                return [
                    ['test', 'test'],
                    ['test', 'test'],
                ];
            }
        };

        $response = $export->store('from-array-store.xlsx');

        $this->assertTrue($response);

        $contents = $this->readAsArray(__DIR__ . '/../Data/Disks/Local/from-array-store.xlsx', 'Xlsx');

        $this->assertEquals($export->array(), $contents);
    }
}
