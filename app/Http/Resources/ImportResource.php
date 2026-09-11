<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Import;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Import $resource
 */
class ImportResource extends JsonResource
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
            'external_import_id' => $this->resource->external_import_id,
            'sent_at' => $this->resource->sent_at,
            'status' => $this->resource->status,
            'total_offers' => $this->resource->total_offers,
            'processed_offers' => $this->resource->processed_offers,
            'error' => $this->resource->errors,
            'created_at' => $this->resource->created_at,
            'completed_at' => $this->resource->completed_at,
        ];
    }
}
