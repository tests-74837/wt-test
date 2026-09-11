<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ImportStatus;
use App\Exception\ImportCreationException;
use App\Http\Requests\Import\CreateImportRequest;
use App\Jobs\SupplierOffersImportJob;
use App\Models\Import;
use Carbon\Carbon;
use Illuminate\Bus\Dispatcher;

class ImportingService
{
    public function __construct(
        private SupplierService $supplierService,
        private OfferService    $offerService,
        private Dispatcher      $jobsDispatcher,
    ) {}

    public function createImport(CreateImportRequest $request): Import
    {
        $data = $request->validated();

        $supplier = $this->supplierService->getSupplierByName($data['supplier']);

        if ($supplier === null) {
            throw new ImportCreationException("Supplier with name {$data['name']} not found!");
        }

        $import = new Import();
        $import->sent_at = Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $data['sent_at']);
        $import->external_import_id = $data['external_import_id'];
        $import->total_offers = count($data['offers']);
        $import->data = $data;
        $import->status = ImportStatus::PENDING->value;
        $import->supplier()->associate($supplier);
        $import->save();

        $this->jobsDispatcher->dispatch(
            new SupplierOffersImportJob($import->withoutRelations())
        );

        return $import;
    }

    public function handleImport(Import $import): void
    {
        $offers = $import->data['offers'] ?? [];

        $import->status = ImportStatus::PROCESSING->value;
        $import->save();

        foreach ($offers as $offerData) {
            try {
                $this->offerService->createOrUpdateFromData($import->supplier, $offerData);

                $import->processed_offers++;
                $import->save();
            } catch (\Throwable $e) {
                $import->failed_offers++;
                $import->errors .= "Failed to import offer " . $offerData['external_id'] . ': ' . $e->getMessage() . "\n";
                $import->save();
            }
        }

        if ($import->failed_offers === 0) {
            $import->status = ImportStatus::COMPLETED->value;
            $import->completed_at = now();
        } else {
            $import->status = ImportStatus::FAILED->value;
            $import->completed_at = now();
        }
        $import->save();
    }

    public function findImportById(string $id): ?Import
    {
        return Import::query()
            ->find($id);
    }
}
