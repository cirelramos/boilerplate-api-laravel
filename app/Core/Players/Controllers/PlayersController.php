<?php

namespace App\Core\Players\Controllers;

use App\Core\Countries\Resources\RegionRapiResource;
use App\Core\Players\Collections\PlayersCollection;
use App\Core\Players\Models\Player;
use App\Core\Players\Requests\StorePlayerRequest;
use App\Core\Players\Resources\PlayerResource;
use Cirelramos\Cache\Services\GetCacheService;
use Cirelramos\Cache\Services\SetCacheService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Cirelramos\ErrorNotification\Services\CatchNotificationService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
class PlayersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *   path="/players",
     *   summary="Show players list ",
     *   tags={"Player"},
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
     *     {"passport": {},  "Content-Language":{}}
     *   },
     *
     *   @OA\Response(response=200, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/Player"),},),
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
    public function index(Request $request): JsonResponse
    {
        $players = Player::query()->orderByRequest()->paginateFromCacheByRequest();

        $playersCollection = new PlayersCollection($players);

        $data[ 'players' ] = $playersCollection;

        return $this->successResponseWithMessage($data);
    }

    public function create()
    {
    }

    /**
     * Public method to save a new player
     *
     * @param StorePlayerRequest $request
     * @return  JsonResponse
     */
    /**
     * @OA\Post(
     *   path="/players",
     *   summary="Store a new player record",
     *   tags={"Player"},
     *
     *   @OA\RequestBody(
     *       @OA\JsonContent(
     *          allOf={
     *             @OA\Schema(ref="#/components/schemas/StorePlayerRequest"),
     *          },
     *      ),
     *    ),
     *   security={
     *     {"passport": {},  "Content-Language":{}}
     *   },
     *
     *   @OA\Response(response=200, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/Player"),},),
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
    public function store(StorePlayerRequest $request): ?JsonResponse
    {
        $successMessage = translateText('OPERATION_SUCCESSFUL_MESSAGE');
        $errorMessage   = translateText('AN_ERROR_HAS_OCCURRED_MESSAGE');

        try {
            DB::beginTransaction();

            $player = new Player();
            $player->fill($request->validated());
            $player->saveWithCache();
            DB::commit();

            $data[ 'player' ] = new PlayerResource($player);

            return $this->successResponseWithMessage($data, $successMessage, Response::HTTP_CREATED);

        } catch (Exception $exception) {
            DB::rollBack();

            CatchNotificationService::error([
                                                'exception' => $exception,
                                                'usersId'   => Auth::id(),
                                            ]);

            return $this->errorCatchResponse($exception, $errorMessage);
        }
    }

    /**
     * @OA\Get(
     *   path="/players/{player}",
     *   summary="Show specific Player ",
     *   tags={"Player"},
     *   @OA\Parameter(
     *     name="player",
     *     in="path",
     *     description="identifier Player",
     *     required=true,
     *   ),
     *   security={
     *     {"passport": {},  "Content-Language":{}}
     *   },
     *
     *   @OA\Response(response=200, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/Player"),},),
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
     * @param Player $player
     * @return JsonResponse
     * @throws Exception
     */
    public function show($player): JsonResponse
    {
        $tag       = Player::TAG_CACHE_MODEL;
        $customKey = "player_show";
        $customKey .= "_" . $player;
        $dataCache = GetCacheService::execute($customKey, $tag);

        if (empty($dataCache) === false) {
            return $dataCache;
        }

        $player = Player::query()->where('id_player', $player)->first();

        if ($player === null) {
            $exception = new ModelNotFoundException();
            $exception->setModel(Player::class);
            throw $exception;
        }

        $data[ 'player' ] = new PlayerResource($player);

        $response = $this->successResponseWithMessage($data);

        SetCacheService::execute($customKey, $response, $tag);

        return $response;
    }

    /**
     * @OA\Put(
     *   path="/players/{player}",
     *   summary="Update a record for a player",
     *   tags={"Player"},
     *   @OA\Parameter(
     *     name="player",
     *     in="path",
     *     description="identifier Player",
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
     *     {"passport": {},  "Content-Language":{}}
     *   },
     *
     *   @OA\Response(response=200, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/Player"),},),
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
     * @param Player $player
     * @param StorePlayerRequest $request
     * @return JsonResponse|null
     */
    public function update(Player $player, StorePlayerRequest $request): ?JsonResponse
    {
        $successMessage = translateText('SUCCESSFUL_UPDATE_MESSAGE');
        $errorMessage   = translateText('AN_ERROR_HAS_OCCURRED_MESSAGE');

        try {
            DB::beginTransaction();

            $player->fill($request->validated());
            $player->saveWithCache();
            DB::commit();

            $data[ 'player' ] = new PlayerResource($player);

            return $this->successResponseWithMessage($data, $successMessage);

        } catch (Exception $exception) {
            DB::rollBack();

            CatchNotificationService::error([
                                                'exception' => $exception,
                                                'usersId'   => Auth::id(),
                                            ]);

            return $this->errorCatchResponse($exception, $errorMessage);
        }
    }

    /**
     * @OA\Delete(
     *   path="/players/{player}",
     *   summary="Deleted specific Player ",
     *   tags={"Player"},
     *   @OA\Parameter(
     *     name="player",
     *     in="path",
     *     description="identifier Player",
     *     required=true,
     *   ),
     *   security={
     *     {"passport": {},  "Content-Language":{}}
     *   },
     *
     *   @OA\Response(response=200, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/Player"),},),
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
    public function destroy(Player $player): ?JsonResponse
    {
        $successMessage = translateText('OPERATION_SUCCESSFUL_MESSAGE');
        $errorMessage   = translateText('AN_ERROR_HAS_OCCURRED_MESSAGE');

        try {
            DB::beginTransaction();

            $player->deleteWithCache();
            DB::commit();

            $data[ 'player' ] = new RegionRapiResource($player);

            return $this->successResponseWithMessage($data, $successMessage);

        } catch (Exception $exception) {
            DB::rollBack();

            CatchNotificationService::error([
                                                'exception' => $exception,
                                                'usersId'   => Auth::id(),
                                            ]);

            return $this->errorCatchResponse($exception, $errorMessage);
        }
    }
}
