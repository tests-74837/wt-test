<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'customer_name' => $this->resource->customer_name,
            'customer_email' => $this->resource->customer_email,
            'offer' => $this->when(
                $this->resource->offer !== null,
                OfferResource::make($this->resource->offer)->toArray($request)
            ),
        ];
    }
}
