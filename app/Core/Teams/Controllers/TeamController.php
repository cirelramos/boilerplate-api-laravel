<?php

namespace App\Core\Teams\Controllers;

use App\Core\Players\Models\Player;
use App\Core\Players\Resources\PlayerResource;
use App\Core\Teams\Collections\TeamsCollection;
use App\Core\Teams\Models\Team;
use App\Core\Teams\Requests\StoreTeamRequest;
use App\Core\Teams\Resources\TeamResource;
use App\Core\Teams\Services\DeleteTeamHasPlayersService;
use App\Core\Teams\Services\StoreTeamHasPlayersService;
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
class TeamController extends Controller
{
    public function __construct(
        private StoreTeamHasPlayersService  $storeTeamHasPlayersService,
        private DeleteTeamHasPlayersService $deleteTeamHasPlayersService
    ) {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *   path="/teams",
     *   summary="Show teams list ",
     *   tags={"Team"},
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
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/Team"),},),
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
        $teams = Team::query()->orderByRequest()->paginateFromCacheByRequest();

        $teamsCollection = new TeamsCollection($teams);

        $data[ 'teams' ] = $teamsCollection;

        return $this->successResponseWithMessage($data);
    }

    /**
     * @OA\Get(
     *   path="/teams/create",
     *   summary="data to store Team",
     *   tags={"Team"},
     *   security={
     *     {"passport": {},  "Content-Language":{}}
     *   },
     *
     *   @OA\Response(response=200, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/200"),},),
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
    public function create()
    {
        $tag       = Player::TAG_CACHE_MODEL;
        $customKey = "team_create";
        $dataCache = GetCacheService::execute($customKey, $tag);

        if (empty($dataCache) === false) {
            return $dataCache;
        }

        $players = Player::all();
        $data['players'] = PlayerResource::collection($players);

        $response = $this->successResponseWithMessage($data);

        SetCacheService::execute($customKey, $response, $tag);
    }

    /**
     * Public method to save a new team
     *
     * @param StoreTeamRequest $request
     * @return  JsonResponse
     */
    /**
     * @OA\Post(
     *   path="/teams",
     *   summary="Store a new team record",
     *   tags={"Team"},
     *
     *   @OA\RequestBody(
     *       @OA\JsonContent(
     *          allOf={
     *             @OA\Schema(ref="#/components/schemas/StoreTeamRequest"),
     *          },
     *      ),
     *    ),
     *   security={
     *     {"passport": {},  "Content-Language":{}}
     *   },
     *
     *   @OA\Response(response=200, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/Team"),},),
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
    public function store(StoreTeamRequest $request): ?JsonResponse
    {
        $successMessage = translateText('OPERATION_SUCCESSFUL_MESSAGE');
        $errorMessage   = translateText('AN_ERROR_HAS_OCCURRED_MESSAGE');

        try {
            DB::beginTransaction();

            $team = new Team();
            $team->fill($request->validated());
            $team->saveWithCache();
            $this->storeTeamHasPlayersService->execute($team, $request);
            $team->saveWithCache();
            DB::commit();

            $data[ 'team' ] = new TeamResource($team);

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
     *   path="/teams/{team}",
     *   summary="Show specific Team ",
     *   tags={"Team"},
     *   @OA\Parameter(
     *     name="team",
     *     in="path",
     *     description="identifier Team",
     *     required=true,
     *   ),
     *   security={
     *     {"passport": {},  "Content-Language":{}}
     *   },
     *
     *   @OA\Response(response=200, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/Team"),},),
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
     * @param Team $team
     * @return JsonResponse
     * @throws Exception
     */
    public function show($team): JsonResponse
    {
        $tag       = Team::TAG_CACHE_MODEL;
        $customKey = "team_show";
        $customKey .= "_" . $team;
        $dataCache = GetCacheService::execute($customKey, $tag);

        if (empty($dataCache) === false) {
            return $dataCache;
        }

        $team = Team::query()->where('id_team', $team)->first();

        if ($team === null) {
            $exception = new ModelNotFoundException();
            $exception->setModel(Team::class);
            throw $exception;
        }

        $data[ 'team' ] = new TeamResource($team);

        $response = $this->successResponseWithMessage($data);

        SetCacheService::execute($customKey, $response, $tag);

        return $response;
    }

    /**
     * @OA\Put(
     *   path="/teams/{team}",
     *   summary="Update a record for a team",
     *   tags={"Team"},
     *   @OA\Parameter(
     *     name="team",
     *     in="path",
     *     description="identifier Team",
     *     required=true,
     *   ),
     *
     *   @OA\RequestBody(
     *       @OA\JsonContent(
     *          allOf={
     *             @OA\Schema(ref="#/components/schemas/StoreTeamRequest"),
     *          },
     *      ),
     *    ),
     *
     *   security={
     *     {"passport": {},  "Content-Language":{}}
     *   },
     *
     *   @OA\Response(response=200, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/Team"),},),
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
     * @param Team $team
     * @param StoreTeamRequest $request
     * @return JsonResponse|null
     */
    public function update(Team $team, StoreTeamRequest $request): ?JsonResponse
    {
        $successMessage = translateText('SUCCESSFUL_UPDATE_MESSAGE');
        $errorMessage   = translateText('AN_ERROR_HAS_OCCURRED_MESSAGE');

        try {
            DB::beginTransaction();

            $team->fill($request->validated());
            $team->saveWithCache();
            $this->deleteTeamHasPlayersService->execute($team);
            $this->storeTeamHasPlayersService->execute($team, $request);
            $team->saveWithCache();
            DB::commit();

            $data[ 'team' ] = new TeamResource($team);

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
     *   path="/teams/{team}",
     *   summary="Deleted specific Team ",
     *   tags={"Team"},
     *   @OA\Parameter(
     *     name="team",
     *     in="path",
     *     description="identifier Team",
     *     required=true,
     *   ),
     *   security={
     *     {"passport": {},  "Content-Language":{}}
     *   },
     *
     *   @OA\Response(response=200, description="",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/Team"),},),
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
    public function destroy(Team $team): ?JsonResponse
    {
        $successMessage = translateText('OPERATION_SUCCESSFUL_MESSAGE');
        $errorMessage   = translateText('AN_ERROR_HAS_OCCURRED_MESSAGE');

        try {
            DB::beginTransaction();

            $this->deleteTeamHasPlayersService->execute($team);
            $team->deleteWithCache();
            DB::commit();

            $data[ 'team' ] = new TeamResource($team);

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
