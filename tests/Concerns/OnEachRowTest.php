<?php

namespace RZP\Maatwebsite\Excel\Tests\Concerns;

use RZP\Maatwebsite\Excel\Concerns\Importable;
use RZP\Maatwebsite\Excel\Concerns\OnEachRow;
use RZP\Maatwebsite\Excel\Row;
use RZP\Maatwebsite\Excel\Tests\TestCase;
use PHPUnit\Framework\Assert;

class OnEachRowTest extends TestCase
{
    /**
     * @test
     */
    public function can_import_each_row_individually()
    {
        $import = new class implements OnEachRow {
            use Importable;

            public $called = 0;

            /**
             * @param Row $row
             */
            public function onRow(Row $row)
            {
                foreach ($row->getCellIterator() as $cell) {
                    Assert::assertEquals('test', $cell->getValue());
                }

                Assert::assertEquals([
                    'test', 'test',
                ], $row->toArray());

                $this->called++;
            }
        };

        $import->import('import.xlsx');

        $this->assertEquals(2, $import->called);
    }
}
