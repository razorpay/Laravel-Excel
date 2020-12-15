<?php

namespace RZP\Maatwebsite\Excel\Tests\Concerns;

use Illuminate\Database\Eloquent\Model;
use RZP\Maatwebsite\Excel\Concerns\Importable;
use RZP\Maatwebsite\Excel\Concerns\ToArray;
use RZP\Maatwebsite\Excel\Concerns\ToModel;
use RZP\Maatwebsite\Excel\Concerns\WithLimit;
use RZP\Maatwebsite\Excel\Concerns\WithStartRow;
use RZP\Maatwebsite\Excel\Tests\Data\Stubs\Database\User;
use RZP\Maatwebsite\Excel\Tests\TestCase;
use PHPUnit\Framework\Assert;

class WithLimitTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'testing']);
    }

    /**
     * @test
     */
    public function can_import_a_limited_section_of_rows_to_model_with_different_start_row()
    {
        $import = new class implements ToModel, WithStartRow, WithLimit {
            use Importable;

            /**
             * @param array $row
             *
             * @return Model
             */
            public function model(array $row): Model
            {
                return new User([
                    'name'     => $row[0],
                    'email'    => $row[1],
                    'password' => 'secret',
                ]);
            }

            /**
             * @return int
             */
            public function startRow(): int
            {
                return 5;
            }

            /**
             * @return int
             */
            public function limit(): int
            {
                return 1;
            }
        };

        $import->import('import-users-with-different-heading-row.xlsx');

        $this->assertDatabaseHas('users', [
            'name'  => 'Patrick Brouwers',
            'email' => 'patrick@maatwebsite.nl',
        ]);

        $this->assertDatabaseMissing('users', [
            'name'  => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
        ]);
    }

    /**
     * @test
     */
    public function can_import_to_array_with_limit()
    {
        $import = new class implements ToArray, WithLimit {
            use Importable;

            /**
             * @param array $array
             */
            public function array(array $array)
            {
                Assert::assertEquals([
                    [
                        'Patrick Brouwers',
                        'patrick@maatwebsite.nl',
                    ],
                ], $array);
            }

            /**
             * @return int
             */
            public function limit(): int
            {
                return 1;
            }
        };

        $import->import('import-users.xlsx');
    }
}
