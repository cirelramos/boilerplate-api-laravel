<?php

namespace App\Core\Teams\Controllers;

use App\Core\Teams\Collections\RegionRapiCollection;
use App\Core\Teams\Resources\RegionRapiResource;
use App\Core\Teams\Models\RegionRapi;
use App\Core\Teams\Services\DeleteCountriesOfRegionService;
use App\Core\Teams\Services\FormatCountriesToCreateRegionService;
use App\Core\Teams\Services\GetCountriesService;
use App\Core\Teams\Services\StoreCountriesOfRegionService;
use Illuminate\Support\Facades\DB;
use Cirelramos\ErrorNotification\Services\CatchNotificationService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Core\Teams\Requests\StoreRegionRapiRequest;

class RegionRapiController extends Controller
{
    public function __construct(
        private StoreCountriesOfRegionService $storeCountriesOfRegionService,
        private DeleteCountriesOfRegionService $deleteCountriesOfRegionService,
        private GetCountriesService $getCountriesService,
        private FormatCountriesToCreateRegionService $formatCountriesToCreateRegionService
    ) {
        $this->middleware('check.external_access');
    }

    /**
     * @OA\Get(
     *   path="/region_rapi",
     *   summary="Show regions list ",
     *   tags={"Region Rapi"},
     *   @OA\Parameter(
     *     name="page_pagination",
     *     in="query",
     *     description="page pagination",
     *   ),
     *   @OA\Parameter(
     *     name="size_pagination",
     *     in="query",
     *     description="size pagination",
     *   ),
     *   security={
     *     {"Key-access": {}, "client_credentials": {}, "user_ip":{},  "Content-Language":{}},
     *     {"passport": {}, "user_ip":{},  "Content-Language":{}}
     *   },
     *
     *   @OA\Response(response=200, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/RegionRapi"),},),
     *   ),
     *   @OA\Response(response=401, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/401"),},),
     *   ),
     *   @OA\Response(response=403, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/403"),},),
     *   ),
     *   @OA\Response(response=404, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/404"),},),
     *   ),
     *   @OA\Response(response=405, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/405"),},),
     *   ),
     *   @OA\Response(response=422, description="",
     *      @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/422"),},),
     *   ),
     *   @OA\Response(response=500, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/500"),},),
     *   ),
     *
     * )
     *
     */
    public function index(Request $request)
    {
        $regions = RegionRapi::with(['countriesRegions.country'])
            ->paginateByRequest();

        $regionsCollection = new RegionRapiCollection($regions);

        $data[ 'regions' ] = $regionsCollection;

        return $this->successResponseWithMessage($data);
    }

    public function create()
    {
        $countries = $this->getCountriesService->execute();

        $data[ 'countries' ] = $countries;

        return $this->successResponseWithMessage($data);

    }

    /**
     * Public method to save a new region in RAPI
     *
     * @param StoreRegionRapiRequest $request
     * @return  JsonResponse
     */
    /**
     * @OA\Post(
     *   path="/region_rapi",
     *   summary="Create a new region record in RAPI",
     *   tags={"Region Rapi"},
     *
     *   @OA\RequestBody(
     *       @OA\JsonContent(
     *          allOf={
     *             @OA\Schema(ref="#/components/schemas/StoreRegionRapi"),
     *          },
     *      ),
     *    ),
     *   security={
     *     {"Key-access": {}, "client_credentials": {}, "user_ip":{},  "Content-Language":{}},
     *     {"passport": {}, "user_ip":{},  "Content-Language":{}}
     *   },
     *
     *   @OA\Response(response=200, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/RegionRapi"),},),
     *   ),
     *   @OA\Response(response=401, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/401"),},),
     *   ),
     *   @OA\Response(response=403, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/403"),},),
     *   ),
     *   @OA\Response(response=404, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/404"),},),
     *   ),
     *   @OA\Response(response=405, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/405"),},),
     *   ),
     *   @OA\Response(response=422, description="",
     *      @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/422"),},),
     *   ),
     *   @OA\Response(response=500, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/500"),},),
     *   ),
     *
     * )
     *
     */
    public function store(StoreRegionRapiRequest $request)
    {
        $successMessage = translateText('OPERATION_SUCCESSFUL_MESSAGE');
        $errorMessage   = translateText('AN_ERROR_HAS_OCCURRED_MESSAGE');

        try {
            DB::beginTransaction();

            $regionRapi = new RegionRapi();
            $regionRapi->fill($request->validated());
            $regionRapi->save();
            $this->storeCountriesOfRegionService->execute($regionRapi, $request);
            $regionRapi->saveWithCache();
            DB::commit();

            $regionRapi->load(['countriesRegions.country']);

            $data[ 'region' ] = new RegionRapiResource($regionRapi);

            return $this->successResponseWithMessage($data, $successMessage, Response::HTTP_CREATED);

        } catch (Exception $exception) {
            DB::rollBack();

            CatchNotificationService::error([
                'exception' => $exception,
                'usersId'   => Auth::id(),
            ]);

            return $this->errorCatchResponse($exception, $errorMessage, Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    /**
     * @OA\Get(
     *   path="/region_rapi/{region_rapi_id}",
     *   summary="Show specific region rapi ",
     *   tags={"Region Rapi"},
     *   @OA\Parameter(
     *     name="region_rapi_id",
     *     in="path",
     *     description="Region Rapi ID",
     *     required=true,
     *   ),
     *   security={
     *     {"Key-access": {}, "client_credentials": {}, "user_ip":{},  "Content-Language":{}},
     *     {"passport": {}, "user_ip":{},  "Content-Language":{}}
     *   },
     *
     *   @OA\Response(response=200, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/RegionRapi"),},),
     *   ),
     *   @OA\Response(response=401, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/401"),},),
     *   ),
     *   @OA\Response(response=403, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/403"),},),
     *   ),
     *   @OA\Response(response=404, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/404"),},),
     *   ),
     *   @OA\Response(response=405, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/405"),},),
     *   ),
     *   @OA\Response(response=422, description="",
     *      @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/422"),},),
     *   ),
     *   @OA\Response(response=500, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/500"),},),
     *   ),
     *
     * )
     * @param RegionRapi $regionRapi
     * @return JsonResponse
     */
    public function show(RegionRapi $regionRapi)
    {
        $regionRapi->load(['countriesRegions.country']);

        $data[ 'region' ] = new RegionRapiResource($regionRapi);

        return $this->successResponseWithMessage($data);
    }

    /**
     * @OA\Put(
     *   path="/region_rapi/{region_rapi_id}",
     *   summary="Update a record for a region in RAPI",
     *   tags={"Region Rapi"},
     *   @OA\Parameter(
     *     name="region_rapi_id",
     *     in="path",
     *     description="Region Rapi ID",
     *     required=true,
     *   ),
     *
     *   @OA\RequestBody(
     *       @OA\JsonContent(
     *          allOf={
     *             @OA\Schema(ref="#/components/schemas/StoreRegionRapi"),
     *          },
     *      ),
     *    ),
     *
     *   security={
     *     {"Key-access": {}, "client_credentials": {}, "user_ip":{},  "Content-Language":{}},
     *     {"passport": {}, "user_ip":{},  "Content-Language":{}}
     *   },
     *
     *   @OA\Response(response=200, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/RegionRapi"),},),
     *   ),
     *   @OA\Response(response=401, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/401"),},),
     *   ),
     *   @OA\Response(response=403, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/403"),},),
     *   ),
     *   @OA\Response(response=404, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/404"),},),
     *   ),
     *   @OA\Response(response=405, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/405"),},),
     *   ),
     *   @OA\Response(response=422, description="",
     *      @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/422"),},),
     *   ),
     *   @OA\Response(response=500, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/500"),},),
     *   ),
     *
     * )
     * @param StoreRegionRapiRequest $request
     * @param RegionRapi             $regionRapi
     * @return JsonResponse
     */
    public function update(RegionRapi $regionRapi, StoreRegionRapiRequest $request)
    {
        $successMessage = translateText('SUCCESSFUL_UPDATE_MESSAGE');
        $errorMessage   = translateText('AN_ERROR_HAS_OCCURRED_MESSAGE');

        try {
            DB::beginTransaction();

            $regionRapi->fill($request->validated());
            $regionRapi->save();
            $this->deleteCountriesOfRegionService->execute($regionRapi);
            $this->storeCountriesOfRegionService->execute($regionRapi, $request);
            $regionRapi->saveWithCache();
            DB::commit();

            $regionRapi->load(['countriesRegions.country']);

            $data[ 'region' ] = new RegionRapiResource($regionRapi);

            return $this->successResponseWithMessage($data, $successMessage);

        } catch (Exception $exception) {
            DB::rollBack();

            CatchNotificationService::error([
                'exception' => $exception,
                'usersId'   => Auth::id(),
            ]);

            return $this->errorCatchResponse($exception, $errorMessage, Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    /**
     * @OA\Delete(
     *   path="/region_rapi/{region_rapi_id}",
     *   summary="Deleted specific region rapi ",
     *   tags={"Region Rapi"},
     *   @OA\Parameter(
     *     name="region_rapi_id",
     *     in="path",
     *     description="Region Rapi ID",
     *     required=true,
     *   ),
     *   security={
     *     {"Key-access": {}, "client_credentials": {}, "user_ip":{},  "Content-Language":{}},
     *     {"passport": {}, "user_ip":{},  "Content-Language":{}}
     *   },
     *
     *   @OA\Response(response=200, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/RegionRapi"),},),
     *   ),
     *   @OA\Response(response=401, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/401"),},),
     *   ),
     *   @OA\Response(response=403, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/403"),},),
     *   ),
     *   @OA\Response(response=404, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/404"),},),
     *   ),
     *   @OA\Response(response=405, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/405"),},),
     *   ),
     *   @OA\Response(response=422, description="",
     *      @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/422"),},),
     *   ),
     *   @OA\Response(response=500, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/500"),},),
     *   ),
     *
     * )
     *
     */
    public function destroy(RegionRapi $regionRapi)
    {
        $successMessage = translateText('OPERATION_SUCCESSFUL_MESSAGE');
        $errorMessage   = translateText('AN_ERROR_HAS_OCCURRED_MESSAGE');

        try {
            DB::beginTransaction();

            $this->deleteCountriesOfRegionService->execute($regionRapi);
            $regionRapi->deleteWithCache();
            DB::commit();

            $data[ 'region' ] = new RegionRapiResource($regionRapi);

            return $this->successResponseWithMessage($data, $successMessage);

        } catch (Exception $exception) {
            DB::rollBack();

            CatchNotificationService::error([
                'exception' => $exception,
                'usersId'   => Auth::id(),
            ]);

            return $this->errorCatchResponse($exception, $errorMessage, Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}
