<?php

namespace App\Core\Teams\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class StoreTeamRequest extends FormRequest
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
     *     schema="StoreTeamRequest",
     *     @OA\Property(
     *       property="name",
     *       example="Team Perez"
     *     ),
     *     @OA\Property(
     *       property="url",
     *       example="https://team-perez.cirelramos.com"
     *     ),
     *     @OA\Property(
     *       property="photo",
     *       example="https://team-perez.cirelramos.com/img/photo.png"
     *     ),
     *     @OA\Property(
     *       property="logo",
     *       example="https://team-perez.cirelramos.com/img/logo.png"
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
     *       property="countries",
     *       type="array",
     *       @OA\Items(
     *          ref="#/components/schemas/StoreTeamHasPlayersRequest"
     *        )
     *     ),
     *  ),
     */

    /**
     * @OA\Schema(
     *     schema="StoreTeamHasPlayersRequest",
     *     @OA\Property(
     *       property="identifier",
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
            'logo'  => 'required|max:150',
            'rank'   => 'required|numeric|min:1|max:10',
            'active' => 'required|numeric|min:0|max:1',

            'players'                 => 'nullable|array|min:1',
            'players.*'               => 'required',
            'players.*.identifier'    => 'required|integer',
        ];
    }
}
