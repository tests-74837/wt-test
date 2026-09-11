<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Offers\Reservations;

use App\Exception\OfferExpiredException;
use App\Exception\OfferHasNoAvailableUnitsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Offers\ReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Services\OfferService;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateController extends Controller
{
    public function __invoke(string $id, ReservationRequest $request, OfferService $service)
    {
        $offer = $service->findOfferById($id);

        if ($offer === null) {
            throw new NotFoundHttpException();
        }

        try {
            $reservation = $service->createReservation($offer, $request);

            return ReservationResource::make($reservation);
        } catch (OfferHasNoAvailableUnitsException $e) {
            return response([
                'success' => false,
                'error' => 'This offer not available',
            ], 406);
        } catch (OfferExpiredException $e) {
            return response([
                'success' => false,
                'error' => 'This offer expired',
            ], 406);
        } catch (LockTimeoutException $e){
            return response([
                'success' => false,
                'error' => 'This offer is locked',
            ], 423);
        }
    }
}
