<?php

namespace App\Core\Countries\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class StoreRegionRapiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * @OA\Schema(
     *     schema="StoreRegionRapi",
     *     @OA\Property(
     *       property="name",
     *       example="Region name"
     *     ),
     *     @OA\Property(
     *       property="countries",
     *       type="array",
     *       @OA\Items(
     *          ref="#/components/schemas/StoreRegionRapiCountry"
     *        )
     *     ),
     *  ),
     */

    /**
     * @OA\Schema(
     *     schema="StoreRegionRapiCountry",
     *     @OA\Property(
     *       property="id_country",
     *       example=1
     *     ),
     *  ),
     */

    public function rules()
    {
        return [
            'name' => 'required|string',

            'countries'                 => 'required|array|min:1',
            'countries.*'               => 'required',
            'countries.*.id_country'    => 'required|integer',
        ];
    }
}
