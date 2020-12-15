<?php

namespace RZP\Maatwebsite\Excel\Tests\Data\Stubs;

use Illuminate\Support\Collection;
use RZP\Maatwebsite\Excel\Concerns\Exportable;
use RZP\Maatwebsite\Excel\Concerns\FromCollection;
use RZP\Maatwebsite\Excel\Concerns\RegistersEventListeners;
use RZP\Maatwebsite\Excel\Concerns\ShouldAutoSize;
use RZP\Maatwebsite\Excel\Concerns\WithEvents;
use RZP\Maatwebsite\Excel\Concerns\WithTitle;
use RZP\Maatwebsite\Excel\Events\BeforeWriting;
use RZP\Maatwebsite\Excel\Tests\TestCase;
use RZP\Maatwebsite\Excel\Writer;

class SheetWith100Rows implements FromCollection, WithTitle, ShouldAutoSize, WithEvents
{
    use Exportable, RegistersEventListeners;

    /**
     * @var string
     */
    private $title;

    /**
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $collection = new Collection;
        for ($i = 0; $i < 100; $i++) {
            $row = new Collection();
            for ($j = 0; $j < 5; $j++) {
                $row[] = $this->title() . '-' . $i . '-' . $j;
            }

            $collection->push($row);
        }

        return $collection;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * @param BeforeWriting $event
     */
    public static function beforeWriting(BeforeWriting $event)
    {
        TestCase::assertInstanceOf(Writer::class, $event->writer);
    }
}
