<?php

namespace App\Core\Teams\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="RegionRapi",
 *     required={"identifier","name", "countries",*    },
 *     @OA\Property(
 *       property="identifier",
 *       description="RegionRapi identifier",
 *       example="3"
 *     ),
 *     @OA\Property(
 *       property="name",
 *       description="Name of region",
 *       example="The Caribbean"
 *     ),
 *     @OA\Property(
 *       property="countries",
 *       description="countries of region",
 *       type="array",
 *       @OA\Items(
 *              ref="#/components/schemas/Country"
 *        )
 *     ),
 *  ),
 */


/**
 *   @OA\Schema(
 *     schema="Country",
 *     required={"identifier","name","iso"},
 *     @OA\Property(
 *       property="identifier",
 *       format="int32",
 *       description="ID elements identifier",
 *       example="305"
 *     ),
 *     @OA\Property(
 *       property="name",
 *       description="Name of contry",
 *       example="United States"
 *     ),
 *     @OA\Property(
 *       property="iso",
 *       description="ISO Alpha 2",
 *       example="US"
 *     )
 *   ),
 */

class RegionRapiResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id_region'         => $this->id_region,
            'name'              => $this->name,
            'countries_regions' => CountryRegionResource::collection($this->whenLoaded('countriesRegions')),
        ];
    }
}
