<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\Properties\SearchRequest;
use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Pagination\LengthAwarePaginator;

class PropertyService
{
    public function searchProperties(SearchRequest $request): LengthAwarePaginator
    {
        $data = $request->validated();

        $properties = Property::query()
            ->when(($data['city'] ?? null) !== null, function (Builder $builder) use ($data) {
                $builder->where('city', $data['city']);
            })
            ->whereHas('offers', function (Builder $builder) use ($data) {
                $builder->where('max_guests', '>=', $data['guests'])
                    ->where('available_units', '>', 0)
                    ->where('check_in', $data['check_in'])
                    ->where('check_out', $data['check_out'])
                    ->where('expires_at', '>', now());
            })
            ->with('bestOffer', function (Builder|HasOne $builder) use ($data) {
                $builder->where('max_guests', '>=', $data['guests'])
                    ->where('available_units', '>', 0)
                    ->where('check_in', $data['check_in'])
                    ->where('check_out', $data['check_out'])
                    ->where('expires_at', '>', now());
            })
            ->with('supplier')
            ->paginate();

        return $properties;
    }

    public function resolvePropertyFromData(array $data): Property
    {
        $existingRecord = Property::query()
            ->where('code', $data['code'])
            ->first();

        if ($existingRecord === null) {
            $existingRecord = new Property();
            $existingRecord->code = $data['code'];
        }

        $existingRecord->name = $data['name'];
        $existingRecord->city = $data['city'];
        $existingRecord->save();

        return $existingRecord;
    }
}
