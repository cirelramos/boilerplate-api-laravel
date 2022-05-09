<?php

namespace App\Core\Teams\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Team",
 *     @OA\Property(
 *       property="identifier",
 *       description="identifier team",
 *       example="1"
 *     ),
 *     @OA\Property(
 *       property="name",
 *       description="Name of team",
 *       example="Jose Perez"
 *     ),
 *     @OA\Property(
 *       property="url",
 *       description="url of team",
 *       example="https://jose-perez.cirelramos.com"
 *     ),
 *     @OA\Property(
 *       property="photo",
 *       description="photo of team",
 *       example="https://jose-perez.cirelramos.com/img/photo.png"
 *     ),
 *     @OA\Property(
 *       property="logo",
 *       description="logo of team",
 *       example="https://team-perez.cirelramos.com/img/logo.png"
 *     ),
 *     @OA\Property(
 *       property="rank",
 *       description="rank of team",
 *       example=10
 *     ),
 *     @OA\Property(
 *       property="active",
 *       description="active team",
 *       example=1
 *     ),
 *  ),
 */
class TeamResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'identifier' => $this->id_team,
            'name'       => $this->name,
            'url'        => $this->url,
            'logo'       => $this->logo,
            'photo'      => $this->photo,
            'rank'       => $this->rank,
            'active'     => $this->active,
        ];
    }
}
