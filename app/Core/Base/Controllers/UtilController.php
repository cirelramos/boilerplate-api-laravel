<?php

namespace App\Core\Base\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class UtilController extends Controller
{

    /**
     * @OA\Get(
     *   path="/util/clear_cache",
     *   summary="clear cache query and other process",
     *   tags={"Utils"},
     *   security={
     *     {"Content-Language":{}},
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
     *
     * )
     *
     */
    public function clear_cache()
    {
        $clearCache = Artisan::call('cache:clear');
        $tagDefault = config('constants.cache_tag_name');
        Cache::tags($tagDefault)->flush();
        return $this->successResponseWithMessage(['clear_cache' => $clearCache], 'cache clean');
    }
}
