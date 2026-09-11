<?php

declare(strict_types=1);

namespace App\Services;

use App\Exception\OfferExpiredException;
use App\Exception\OfferHasNoAvailableUnitsException;
use App\Http\Requests\Offers\ReservationRequest;
use App\Models\Offer;
use App\Models\Reservation;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OfferService
{
    public function __construct(
        public PropertyService $propertyService
    ) {}

    public function findOfferById(string $id): ?Offer
    {
        return Offer::query()
            ->find($id);
    }

    public function createOrUpdateFromData(Supplier $supplier, array $offerData): Offer
    {
        $offerRecord = Offer::query()
            ->where('external_id', $offerData['external_id'])
            ->where('supplier_id', $supplier->getKey())
            ->first();


        if ($offerRecord === null) {
            $property = $this->propertyService->resolvePropertyFromData($offerData['property']);

            $offerRecord = new Offer();
            $offerRecord->external_id = $offerData['external_id'];
            $offerRecord->property()->associate($property);
            $offerRecord->supplier()->associate($supplier);
        }

        $offerRecord->check_in = Carbon::createFromFormat('Y-m-d', $offerData['check_in']);
        $offerRecord->check_out = Carbon::createFromFormat('Y-m-d', $offerData['check_out']);
        $offerRecord->max_guests = $offerData['max_guests'];
        $offerRecord->price = $offerData['price'];
        $offerRecord->currency = $offerData['currency'];
        $offerRecord->expires_at = Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $offerData['expires_at']);
        $offerRecord->available_units = $offerData['available_units'];
        $offerRecord->save();

        return $offerRecord;
    }

    public function createReservation(Offer $offer, ReservationRequest $request): Reservation
    {
        $lockToken = 'offer-' . $offer->getKey();

        return Cache::lock($lockToken, 60)->block(5, function () use ($offer, $request) {
            $offer->refresh();

            if ($offer->available_units === 0) {
                throw new OfferHasNoAvailableUnitsException();
            }

            if (now()->isAfter($offer->expires_at)) {
                throw new OfferExpiredException();
            }

            $reservation = DB::transaction(function () use ($offer, $request) {
                $data = $request->validated();
                $reservation = new Reservation();
                $reservation->offer()->associate($offer);
                $reservation->customer_name = $data['customer_name'];
                $reservation->customer_email = $data['customer_email'];
                $reservation->client_reference = $data['client_reference'];
                $reservation->save();

                $offer->available_units--;
                $offer->save();

                return $reservation;
            });

            return $reservation;
        });
    }
}
