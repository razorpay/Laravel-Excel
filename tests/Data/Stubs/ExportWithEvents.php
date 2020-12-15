<?php

namespace RZP\Maatwebsite\Excel\Tests\Data\Stubs;

use RZP\Maatwebsite\Excel\Concerns\Exportable;
use RZP\Maatwebsite\Excel\Concerns\WithEvents;
use RZP\Maatwebsite\Excel\Events\AfterSheet;
use RZP\Maatwebsite\Excel\Events\BeforeExport;
use RZP\Maatwebsite\Excel\Events\BeforeSheet;
use RZP\Maatwebsite\Excel\Events\BeforeWriting;

class ExportWithEvents implements WithEvents
{
    use Exportable;

    /**
     * @var callable
     */
    public $beforeExport;

    /**
     * @var callable
     */
    public $beforeWriting;

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
            BeforeExport::class  => $this->beforeExport ?? function () {
            },
            BeforeWriting::class => $this->beforeWriting ?? function () {
            },
            BeforeSheet::class   => $this->beforeSheet ?? function () {
            },
            AfterSheet::class    => $this->afterSheet ?? function () {
            },
        ];
    }
}
