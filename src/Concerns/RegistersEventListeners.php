<?php

namespace RZP\Maatwebsite\Excel\Concerns;

use RZP\Maatwebsite\Excel\Events\AfterImport;
use RZP\Maatwebsite\Excel\Events\AfterSheet;
use RZP\Maatwebsite\Excel\Events\BeforeExport;
use RZP\Maatwebsite\Excel\Events\BeforeImport;
use RZP\Maatwebsite\Excel\Events\BeforeSheet;
use RZP\Maatwebsite\Excel\Events\BeforeWriting;
use RZP\Maatwebsite\Excel\Events\ImportFailed;

trait RegistersEventListeners
{
    /**
     * @return array
     */
    public function registerEvents(): array
    {
        $listeners = [];

        if (method_exists($this, 'beforeExport')) {
            $listeners[BeforeExport::class] = [static::class, 'beforeExport'];
        }

        if (method_exists($this, 'beforeWriting')) {
            $listeners[BeforeWriting::class] = [static::class, 'beforeWriting'];
        }

        if (method_exists($this, 'beforeImport')) {
            $listeners[BeforeImport::class] = [static::class, 'beforeImport'];
        }

        if (method_exists($this, 'afterImport')) {
            $listeners[AfterImport::class] = [static::class, 'afterImport'];
        }

        if (method_exists($this, 'importFailed')) {
            $listeners[ImportFailed::class] = [static::class, 'importFailed'];
        }

        if (method_exists($this, 'beforeSheet')) {
            $listeners[BeforeSheet::class] = [static::class, 'beforeSheet'];
        }

        if (method_exists($this, 'afterSheet')) {
            $listeners[AfterSheet::class] = [static::class, 'afterSheet'];
        }

        return $listeners;
    }
}
