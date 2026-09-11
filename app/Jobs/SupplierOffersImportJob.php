<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\ImportStatus;
use App\Models\Import;
use App\Services\ImportingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SupplierOffersImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private Import $import
    ) {}

    public function handle(ImportingService $service): void
    {
        if ($this->import->status !== ImportStatus::PENDING->value) {
            return;
        }

        $service->handleImport($this->import);
    }

    public function failed(Throwable $exception = null): void
    {
        $this->import->status = ImportStatus::FAILED->value;
        $this->import->errors .= "Importing job filed: " . $exception->getMessage();
        $this->import->save();
    }
}
