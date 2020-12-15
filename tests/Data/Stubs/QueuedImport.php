<?php

namespace RZP\Maatwebsite\Excel\Tests\Data\Stubs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use RZP\Maatwebsite\Excel\Concerns\Importable;
use RZP\Maatwebsite\Excel\Concerns\ToModel;
use RZP\Maatwebsite\Excel\Concerns\WithBatchInserts;
use RZP\Maatwebsite\Excel\Concerns\WithChunkReading;
use RZP\Maatwebsite\Excel\Tests\Data\Stubs\Database\Group;

class QueuedImport implements ShouldQueue, ToModel, WithChunkReading, WithBatchInserts
{
    use Importable;

    /**
     * @param array $row
     *
     * @return Model|null
     */
    public function model(array $row)
    {
        return new Group([
            'name' => $row[0],
        ]);
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }
}
