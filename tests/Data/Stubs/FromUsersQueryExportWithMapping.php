<?php

namespace RZP\Maatwebsite\Excel\Tests\Data\Stubs;

use Illuminate\Database\Query\Builder;
use RZP\Maatwebsite\Excel\Concerns\Exportable;
use RZP\Maatwebsite\Excel\Concerns\FromQuery;
use RZP\Maatwebsite\Excel\Concerns\WithEvents;
use RZP\Maatwebsite\Excel\Concerns\WithMapping;
use RZP\Maatwebsite\Excel\Events\BeforeSheet;
use RZP\Maatwebsite\Excel\Tests\Data\Stubs\Database\User;

class FromUsersQueryExportWithMapping implements FromQuery, WithMapping, WithEvents
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
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class   => function (BeforeSheet $event) {
                $event->sheet->chunkSize(10);
            },
        ];
    }

    /**
     * @param User $row
     *
     * @return array
     */
    public function map($row): array
    {
        return [
            'name' => $row->name,
        ];
    }
}
