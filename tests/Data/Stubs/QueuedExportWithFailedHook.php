<?php

namespace RZP\Maatwebsite\Excel\Tests\Data\Stubs;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use RZP\Maatwebsite\Excel\Concerns\Exportable;
use RZP\Maatwebsite\Excel\Concerns\FromCollection;
use RZP\Maatwebsite\Excel\Concerns\WithMapping;
use RZP\Maatwebsite\Excel\Tests\Data\Stubs\Database\User;
use PHPUnit\Framework\Assert;

class QueuedExportWithFailedHook implements FromCollection, WithMapping
{
    use Exportable;

    /**
     * @var bool
     */
    public $failed = false;

    /**
     * @return Collection
     */
    public function collection()
    {
        return collect([
            new User([
                'firstname' => 'Patrick',
                'lastname'  => 'Brouwers',
            ]),
        ]);
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function map($user): array
    {
        throw new Exception('we expect this');
    }

    /**
     * @param Exception $exception
     */
    public function failed(Exception $exception)
    {
        Assert::assertEquals('we expect this', $exception->getMessage());

        app()->bind('queue-has-failed', function () {
            return true;
        });
    }
}
