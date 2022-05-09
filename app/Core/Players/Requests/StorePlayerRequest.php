<?php

namespace App\Core\Players\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class StorePlayerRequest extends FormRequest
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
     *     schema="StorePlayerRequest",
     *     @OA\Property(
     *       property="name",
     *       example="Jose Perez"
     *     ),
     *     @OA\Property(
     *       property="url",
     *       example="https://jose-perez.cirelramos.com"
     *     ),
     *     @OA\Property(
     *       property="photo",
     *       example="https://jose-perez.cirelramos.com/img/photo.png"
     *     ),
     *     @OA\Property(
     *       property="rank",
     *       example=10
     *     ),
     *     @OA\Property(
     *       property="active",
     *       example=1
     *     ),
     *     @OA\Property(
     *       property="renew",
     *       example=1
     *     ),
     *  ),
     */

    public function rules()
    {
        return [
            'name'   => 'required|string|max:150',
            'url'    => 'required|max:150',
            'photo'  => 'required|max:150',
            'rank'   => 'required|numeric|min:1|max:10',
            'active' => 'required|numeric|min:0|max:1',
            'renew'  => 'required|numeric|min:0|max:1',
        ];
    }
}
