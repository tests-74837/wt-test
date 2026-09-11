<?php

namespace App\Http\Resources;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Property $resource
 */
class PropertyResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->resource->code,
            'name' => $this->resource->name,
            'city' => $this->resource->city,
            'best_offer' => OfferResource::make($this->resource->bestOffer)->toArray($request),
        ];
    }
}
