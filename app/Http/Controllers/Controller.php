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
 *   path="/oauth/token",
 *   summary="Request access token",
 *   tags={"Authentication"},
 *  	@OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *     required={"client_id", "client_secret", "grant_type"},
 *   @OA\Property(
 *     property="client_id",
 *     description="Client Id",
 *   ),
 *   @OA\Property(
 *     property="client_secret",
 *     description="Client Secret",
 *   ),
 *   @OA\Property(
 *     property="grant_type",
 *     description="Authentication type (password || client_credentials || refresh_token)",
 *   ),
 *   @OA\Property(
 *     property="username",
 *     description="User to authenticate (email)",
 *   ),
 *   @OA\Property(
 *     property="password",
 *     description="User password",
 *   ),
 *   @OA\Property(
 *     property="refresh_token",
 *     description="Token to refresh",
 *   ),
 *   @OA\Property(
 *     property="pcbl",
 *     description="free spin login",
 *   ),
 *               ),
 *           ),
 *       ),
 *   security={
 *     {"user_ip":{},  "Content-Language":{}}
 *   },
 *   @OA\Response(
 *     response=200,
 *     description="Successful operation",
 *     @OA\Schema(
 *     ),
 *   ),
 * )
 *
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
