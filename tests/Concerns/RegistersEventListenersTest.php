<?php

namespace RZP\Maatwebsite\Excel\Tests\Concerns;

use RZP\Maatwebsite\Excel\Events\AfterSheet;
use RZP\Maatwebsite\Excel\Events\BeforeExport;
use RZP\Maatwebsite\Excel\Events\BeforeImport;
use RZP\Maatwebsite\Excel\Events\BeforeSheet;
use RZP\Maatwebsite\Excel\Events\BeforeWriting;
use RZP\Maatwebsite\Excel\Reader;
use RZP\Maatwebsite\Excel\Sheet;
use RZP\Maatwebsite\Excel\Tests\Data\Stubs\BeforeExportListener;
use RZP\Maatwebsite\Excel\Tests\Data\Stubs\ExportWithEvents;
use RZP\Maatwebsite\Excel\Tests\Data\Stubs\ExportWithRegistersEventListeners;
use RZP\Maatwebsite\Excel\Tests\Data\Stubs\ImportWithRegistersEventListeners;
use RZP\Maatwebsite\Excel\Tests\TestCase;
use RZP\Maatwebsite\Excel\Writer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RegistersEventListenersTest extends TestCase
{
    /**
     * @test
     */
    public function events_get_called_when_exporting()
    {
        $event = new ExportWithRegistersEventListeners();

        $eventsTriggered = 0;

        $event::$beforeExport = function ($event) use (&$eventsTriggered) {
            $this->assertInstanceOf(BeforeExport::class, $event);
            $this->assertInstanceOf(Writer::class, $event->writer);
            $eventsTriggered++;
        };

        $event::$beforeWriting = function ($event) use (&$eventsTriggered) {
            $this->assertInstanceOf(BeforeWriting::class, $event);
            $this->assertInstanceOf(Writer::class, $event->writer);
            $eventsTriggered++;
        };

        $event::$beforeSheet = function ($event) use (&$eventsTriggered) {
            $this->assertInstanceOf(BeforeSheet::class, $event);
            $this->assertInstanceOf(Sheet::class, $event->sheet);
            $eventsTriggered++;
        };

        $event::$afterSheet = function ($event) use (&$eventsTriggered) {
            $this->assertInstanceOf(AfterSheet::class, $event);
            $this->assertInstanceOf(Sheet::class, $event->sheet);
            $eventsTriggered++;
        };

        $this->assertInstanceOf(BinaryFileResponse::class, $event->download('filename.xlsx'));
        $this->assertEquals(4, $eventsTriggered);
    }

    /**
     * @test
     */
    public function events_get_called_when_importing()
    {
        $event = new ImportWithRegistersEventListeners();

        $eventsTriggered = 0;

        $event::$beforeImport = function ($event) use (&$eventsTriggered) {
            $this->assertInstanceOf(BeforeImport::class, $event);
            $this->assertInstanceOf(Reader::class, $event->reader);
            $eventsTriggered++;
        };

        $event::$beforeSheet = function ($event) use (&$eventsTriggered) {
            $this->assertInstanceOf(BeforeSheet::class, $event);
            $this->assertInstanceOf(Sheet::class, $event->sheet);
            $eventsTriggered++;
        };

        $event::$afterSheet = function ($event) use (&$eventsTriggered) {
            $this->assertInstanceOf(AfterSheet::class, $event);
            $this->assertInstanceOf(Sheet::class, $event->sheet);
            $eventsTriggered++;
        };

        $event->import('import.xlsx');
        $this->assertEquals(3, $eventsTriggered);
    }

    /**
     * @test
     */
    public function can_have_invokable_class_as_listener()
    {
        $event = new ExportWithEvents();

        $event->beforeExport = new BeforeExportListener(function ($event) {
            $this->assertInstanceOf(BeforeExport::class, $event);
            $this->assertInstanceOf(Writer::class, $event->writer);
        });

        $this->assertInstanceOf(BinaryFileResponse::class, $event->download('filename.xlsx'));
    }
}
