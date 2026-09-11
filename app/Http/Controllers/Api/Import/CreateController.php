<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Import;

use App\Http\Controllers\Controller;
use App\Http\Requests\Import\CreateImportRequest;
use App\Services\ImportingService;

class CreateController extends Controller
{
    public function __invoke(CreateImportRequest $request, ImportingService $service)
    {
        $import = $service->createImport($request);

        return response([
            'id' => $import->getKey(),
            'status' => $import->status,
        ], 202);
    }
}
