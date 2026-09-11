<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Import;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImportResource;
use App\Services\ImportingService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ViewController extends Controller
{
    public function __invoke(string $id, ImportingService $service)
    {
        $import = $service->findImportById($id);

        if ($import === null) {
            throw new NotFoundHttpException();
        }

        return ImportResource::make($import);
    }
}
