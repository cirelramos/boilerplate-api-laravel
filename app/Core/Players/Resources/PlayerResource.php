<?php

namespace App\Core\Players\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Player",
 *     @OA\Property(
 *       property="identifier",
 *       description="identifier player",
 *       example="1"
 *     ),
 *     @OA\Property(
 *       property="name",
 *       description="Name of player",
 *       example="Jose Perez"
 *     ),
 *     @OA\Property(
 *       property="url",
 *       description="url of player",
 *       example="https://jose-perez.cirelramos.com"
 *     ),
 *     @OA\Property(
 *       property="photo",
 *       description="photo of player",
 *       example="https://jose-perez.cirelramos.com/img/photo.png"
 *     ),
 *     @OA\Property(
 *       property="rank",
 *       description="rank of player",
 *       example=10
 *     ),
 *     @OA\Property(
 *       property="active",
 *       description="active player",
 *       example=1
 *     ),
 *     @OA\Property(
 *       property="renew",
 *       description="renew player",
 *       example=1
 *     ),
 *  ),
 */
class PlayerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'identifier' => $this->id_player,
            'name'       => $this->name,
            'url'        => $this->url,
            'photo'      => $this->photo,
            'rank'       => $this->rank,
            'active'     => $this->active,
            'renew'      => $this->renew,
        ];
    }
}
