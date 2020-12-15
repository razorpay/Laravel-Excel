<?php

namespace RZP\Maatwebsite\Excel\Tests\Data\Stubs;

use Illuminate\Database\Query\Builder;
use RZP\Maatwebsite\Excel\Concerns\Exportable;
use RZP\Maatwebsite\Excel\Concerns\FromQuery;
use RZP\Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use RZP\Maatwebsite\Excel\Tests\Data\Stubs\Database\User;

class FromUsersQueryExport implements FromQuery, WithCustomChunkSize
{
    use Exportable;

    /**
     * @return Builder
     */
    public function query()
    {
        return User::query();
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 10;
    }
}
