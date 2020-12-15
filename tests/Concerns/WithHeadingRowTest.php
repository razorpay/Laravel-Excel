<?php

namespace RZP\Maatwebsite\Excel\Tests\Concerns;

use Illuminate\Database\Eloquent\Model;
use RZP\Maatwebsite\Excel\Concerns\Importable;
use RZP\Maatwebsite\Excel\Concerns\ToArray;
use RZP\Maatwebsite\Excel\Concerns\ToModel;
use RZP\Maatwebsite\Excel\Concerns\WithHeadingRow;
use RZP\Maatwebsite\Excel\Tests\Data\Stubs\Database\User;
use RZP\Maatwebsite\Excel\Tests\TestCase;
use PHPUnit\Framework\Assert;

class WithHeadingRowTest extends TestCase
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
    public function can_import_each_row_to_model_with_heading_row()
    {
        $import = new class implements ToModel, WithHeadingRow {
            use Importable;

            /**
             * @param array $row
             *
             * @return Model
             */
            public function model(array $row): Model
            {
                return new User([
                    'name'     => $row['name'],
                    'email'    => $row['email'],
                    'password' => 'secret',
                ]);
            }
        };

        $import->import('import-users-with-headings.xlsx');

        $this->assertDatabaseHas('users', [
            'name'  => 'Patrick Brouwers',
            'email' => 'patrick@maatwebsite.nl',
        ]);

        $this->assertDatabaseHas('users', [
            'name'  => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
        ]);
    }

    /**
     * @test
     */
    public function can_import_each_row_to_model_with_different_heading_row()
    {
        $import = new class implements ToModel, WithHeadingRow {
            use Importable;

            /**
             * @param array $row
             *
             * @return Model
             */
            public function model(array $row): Model
            {
                return new User([
                    'name'     => $row['name'],
                    'email'    => $row['email'],
                    'password' => 'secret',
                ]);
            }

            /**
             * @return int
             */
            public function headingRow(): int
            {
                return 4;
            }
        };

        $import->import('import-users-with-different-heading-row.xlsx');

        $this->assertDatabaseHas('users', [
            'name'  => 'Patrick Brouwers',
            'email' => 'patrick@maatwebsite.nl',
        ]);

        $this->assertDatabaseHas('users', [
            'name'  => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
        ]);
    }

    /**
     * @test
     */
    public function can_import_to_array_with_heading_row()
    {
        $import = new class implements ToArray, WithHeadingRow {
            use Importable;

            /**
             * @param array $array
             */
            public function array(array $array)
            {
                Assert::assertEquals([
                    [
                        'name'  => 'Patrick Brouwers',
                        'email' => 'patrick@maatwebsite.nl',
                    ],
                    [
                        'name'  => 'Taylor Otwell',
                        'email' => 'taylor@laravel.com',
                    ],
                ], $array);
            }
        };

        $import->import('import-users-with-headings.xlsx');
    }
}
