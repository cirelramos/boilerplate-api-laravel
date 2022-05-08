<?php

namespace App\Core\Teams\Models;

use Cirelramos\Cache\Models\CacheModel;

/**
 *
 */
class TeamHasPlayer extends CacheModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table      = 'teams_has_players';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id_team',
        'id_player',
    ];
}
