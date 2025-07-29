<?php

namespace AnimeSite\Http\Controllers\Api\V1;

use AnimeSite\Actions\Tariffs\CreateTariff;
use AnimeSite\Actions\Tariffs\GetTariffs;
use AnimeSite\Actions\Tariffs\UpdateTariff;
use AnimeSite\DTOs\Tariffs\TariffIndexDTO;
use AnimeSite\DTOs\Tariffs\TariffStoreDTO;
use AnimeSite\DTOs\Tariffs\TariffUpdateDTO;
use AnimeSite\Http\Controllers\Controller;
use AnimeSite\Http\Requests\Tariffs\TariffDeleteRequest;
use AnimeSite\Http\Requests\Tariffs\TariffIndexRequest;
use AnimeSite\Http\Requests\Tariffs\TariffStoreRequest;
use AnimeSite\Http\Requests\Tariffs\TariffUpdateRequest;
use AnimeSite\Http\Resources\TariffResource;
use AnimeSite\Models\Tariff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TariffController extends Controller
{
    /**
     * Get paginated list of tariffs with filtering, sorting and pagination
     *
     * @param  TariffIndexRequest  $request
     * @param  GetTariffs  $action
     * @return AnonymousResourceCollection
     */
    public function index(TariffIndexRequest $request, GetTariffs $action): AnonymousResourceCollection
    {
        $dto = TariffIndexDTO::fromRequest($request);
        $tariffs = $action->handle($dto);

        return TariffResource::collection($tariffs);
    }

    /**
     * Get detailed information about a specific tariff
     *
     * @param  Tariff  $tariff
     * @return TariffResource
     */
    public function show(Tariff $tariff): TariffResource
    {

        return new TariffResource($tariff->loadCount('subscriptions'));
    }

    /**
     * Store a newly created tariff
     *
     * @param  TariffStoreRequest  $request
     * @param  CreateTariff  $action
     * @return TariffResource
     * @authenticated
     */
    public function store(TariffStoreRequest $request, CreateTariff $action): TariffResource
    {
        $dto = TariffStoreDTO::fromRequest($request);
        $tariff = $action->handle($dto);

        return new TariffResource($tariff);
    }

    /**
     * Update the specified tariff
     *
     * @param  TariffUpdateRequest  $request
     * @param  Tariff  $tariff
     * @param  UpdateTariff  $action
     * @return TariffResource
     * @authenticated
     */
    public function update(TariffUpdateRequest $request, Tariff $tariff, UpdateTariff $action): TariffResource
    {
        $dto = TariffUpdateDTO::fromRequest($request);
        $tariff = $action->handle($tariff, $dto);

        return new TariffResource($tariff);
    }

    /**
     * Remove the specified tariff
     *
     * @param  TariffDeleteRequest  $request
     * @param  Tariff  $tariff
     * @return JsonResponse
     * @authenticated
     */
    public function destroy(TariffDeleteRequest $request, Tariff $tariff): JsonResponse
    {
        // Check if the tariff has active subscriptions
        if ($tariff->userSubscriptions()->where('is_active', true)->exists()) {
            return response()->json([
                'message' => 'Cannot delete tariff with active subscriptions',
            ], 422);
        }

        $tariff->delete();

        return response()->json([
            'message' => 'Tariff deleted successfully',
        ]);
    }
}
