<?php

namespace App\Core\Teams\Models;

use Cirelramos\Cache\Models\CacheModel;

/**
 *
 */
class Team extends CacheModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table      = 'teams';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_team';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'url',
        'photo',
        'logo',
        'rank',
        'active',
    ];
}
