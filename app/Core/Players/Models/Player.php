<?php

namespace App\Core\Players\Models;

use Cirelramos\Cache\Models\CacheModel;

/**
 *
 */
class Player extends CacheModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table      = 'players';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_player';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'url',
        'photo',
        'rank',
        'active',
        'renew',
    ];

    public const TAG_CACHE_MODEL = 'TAG_CACHE_PLAYER_';
}
