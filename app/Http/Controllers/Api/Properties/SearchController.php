<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Properties;

use App\Http\Controllers\Controller;
use App\Http\Requests\Properties\SearchRequest;
use App\Http\Resources\PropertyResource;
use App\Services\PropertyService;

class SearchController extends Controller
{
    public function __invoke(SearchRequest $request, PropertyService $service)
    {
        return PropertyResource::collection(
            $service->searchProperties($request)
        );
    }
}
