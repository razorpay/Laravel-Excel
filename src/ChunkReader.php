<?php

namespace RZP\Maatwebsite\Excel;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use RZP\Maatwebsite\Excel\Concerns\WithChunkReading;
use RZP\Maatwebsite\Excel\Concerns\WithEvents;
use RZP\Maatwebsite\Excel\Concerns\WithLimit;
use RZP\Maatwebsite\Excel\Concerns\WithProgressBar;
use RZP\Maatwebsite\Excel\Events\BeforeImport;
use RZP\Maatwebsite\Excel\Files\TemporaryFile;
use RZP\Maatwebsite\Excel\Imports\HeadingRowExtractor;
use RZP\Maatwebsite\Excel\Jobs\AfterImportJob;
use RZP\Maatwebsite\Excel\Jobs\QueueImport;
use RZP\Maatwebsite\Excel\Jobs\ReadChunk;
use Throwable;

class ChunkReader
{
    /**
     * @param  WithChunkReading  $import
     * @param  Reader  $reader
     * @param  TemporaryFile  $temporaryFile
     *
     * @return \Illuminate\Foundation\Bus\PendingDispatch|null
     */
    public function read(WithChunkReading $import, Reader $reader, TemporaryFile $temporaryFile)
    {
        if ($import instanceof WithEvents && isset($import->registerEvents()[BeforeImport::class])) {
            $reader->beforeImport($import);
        }

        $chunkSize  = $import->chunkSize();
        $totalRows  = $reader->getTotalRows();
        $worksheets = $reader->getWorksheets($import);

        if ($import instanceof WithProgressBar) {
            $import->getConsoleOutput()->progressStart(array_sum($totalRows));
        }

        $jobs = new Collection();
        foreach ($worksheets as $name => $sheetImport) {
            $startRow         = HeadingRowExtractor::determineStartRow($sheetImport);
            $totalRows[$name] = $sheetImport instanceof WithLimit ? $sheetImport->limit() : $totalRows[$name];

            for ($currentRow = $startRow; $currentRow <= $totalRows[$name]; $currentRow += $chunkSize) {
                $jobs->push(new ReadChunk(
                    $import,
                    $reader->getPhpSpreadsheetReader(),
                    $temporaryFile,
                    $name,
                    $sheetImport,
                    $currentRow,
                    $chunkSize
                ));
            }
        }

        $jobs->push(new AfterImportJob($import, $reader));

        if ($import instanceof ShouldQueue) {
            return QueueImport::withChain($jobs->toArray())->dispatch($import);
        }

        $jobs->each(function ($job) {
            try {
                dispatch_now($job);
            } catch (Throwable $e) {
                if (method_exists($job, 'failed')) {
                    $job->failed($e);
                }
                throw $e;
            }
        });

        if ($import instanceof WithProgressBar) {
            $import->getConsoleOutput()->progressFinish();
        }

        unset($jobs);

        return null;
    }
}
