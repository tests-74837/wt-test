<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Import;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Offer $resource
 */
class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'supplier' => $this->resource->supplier->name,
            'available_units' => $this->resource->available_units,
            'expires_at' => $this->resource->expires_at,
        ];
    }
}
