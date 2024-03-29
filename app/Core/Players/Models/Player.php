<?php

namespace App\Core\Players\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Cirelramos\Cache\Models\CacheModel;

/**
 *
 */
class Player extends CacheModel
{
    use SoftDeletes;

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
        'renew_at',
        'renew_next',
    ];

    public const TAG_CACHE_MODEL = 'TAG_CACHE_PLAYER_';
}
