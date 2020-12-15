<?php

namespace RZP\Maatwebsite\Excel\Tests\Data\Stubs;

use RZP\Maatwebsite\Excel\Concerns\Importable;
use RZP\Maatwebsite\Excel\Concerns\WithEvents;
use RZP\Maatwebsite\Excel\Events\AfterImport;
use RZP\Maatwebsite\Excel\Events\AfterSheet;
use RZP\Maatwebsite\Excel\Events\BeforeImport;
use RZP\Maatwebsite\Excel\Events\BeforeSheet;

class ImportWithEvents implements WithEvents
{
    use Importable;

    /**
     * @var callable
     */
    public $beforeImport;

    /**
     * @var callable
     */
    public $beforeSheet;

    /**
     * @var callable
     */
    public $afterSheet;

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => $this->beforeImport ?? function () {
            },
            AfterImport::class => $this->afterImport ?? function () {
            },
            BeforeSheet::class => $this->beforeSheet ?? function () {
            },
            AfterSheet::class => $this->afterSheet ?? function () {
            },
        ];
    }
}
