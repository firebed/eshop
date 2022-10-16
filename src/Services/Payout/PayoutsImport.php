<?php

namespace Eshop\Services\Payout;

use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeImport;

abstract class PayoutsImport implements WithMapping, WithBatchInserts, WithChunkReading, WithEvents
{
    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $after) {
                $sheet = $after->getReader()->getActiveSheet();

                if ($this->skipFirstRow()) {
                    $sheet->removeRow(1);
                }

                if ($this->skipLastRow()) {
                    $sheet->removeRow($sheet->getHighestRow());
                }
            }
        ];
    }

    protected function skipFirstRow(): bool
    {
        return true;
    }

    protected function skipLastRow(): bool
    {
        return true;
    }
}