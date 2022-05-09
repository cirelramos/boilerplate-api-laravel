<?php

namespace App\Http\Controllers;


use App\Core\Base\Traits\TextUtilsTraits;
use Cirelramos\Response\Traits\ResponseTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\Info(
 *     title="Boilerplate",
 *     version="1.0.0",
 * )
 */

/**
 * @OA\Post(
 * path="/oauth/login",
 * summary="Sign in",
 * description="Login by email, password",
 * operationId="authLogin",
 * tags={"Auth"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"email","password"},
 *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
 *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
 *       @OA\Property(property="persistent", type="boolean", example="true"),
 *    ),
 * ),
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
 * )
 */

/**
 * @OA\Schema(
 *     schema="200",
 *     @OA\Property(
 *       property="success",
 *       type="string",
 *       example="success request"
 *     ),
 *     @OA\Property(
 *       property="code",
 *       type="integer",
 *       example="200"
 *     ),
 *  ),
 */

/**
 * @OA\Schema(
 *     schema="401",
 *     @OA\Property(
 *       property="error",
 *       type="string",
 *       example="Non authenticated"
 *     ),
 *     @OA\Property(
 *       property="code",
 *       type="integer",
 *       example="401"
 *     ),
 *  ),
 */

/**
 * @OA\Schema(
 *     schema="403",
 *     @OA\Property(
 *       property="error",
 *       type="string",
 *       example="This #MODEL is not allowed for you"
 *     ),
 *     @OA\Property(
 *       property="code",
 *       type="integer",
 *       example="403"
 *     ),
 *  ),
 */

/**
 * @OA\Schema(
 *     schema="404",
 *     @OA\Property(
 *       property="error",
 *       type="string",
 *       example="URL not found"
 *     ),
 *     @OA\Property(
 *       property="code",
 *       type="integer",
 *       example="404"
 *     ),
 *  ),
 */

/**
 * @OA\Schema(
 *     schema="405",
 *     @OA\Property(
 *       property="error",
 *       type="string",
 *       example="The specified method is invalid"
 *     ),
 *     @OA\Property(
 *       property="code",
 *       type="integer",
 *       example="405"
 *     ),
 *  ),
 */

/**
 * @OA\Schema(
 *     schema="422",
 *     @OA\Property(
 *       property="error",
 *       type="string",
 *       example="validation exception"
 *     ),
 *     @OA\Property(
 *       property="code",
 *       type="integer",
 *       example="422"
 *     ),
 *  ),
 */

/**
 * @OA\Schema(
 *     schema="500",
 *     @OA\Property(
 *       property="error",
 *       type="string",
 *       example="Internal server error"
 *     ),
 *     @OA\Property(
 *       property="code",
 *       type="integer",
 *       example="500"
 *     ),
 *  ),
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use ResponseTrait;
    use TextUtilsTraits;
}
