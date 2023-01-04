<?php

namespace App\Core\Teams\Models;

use App\Core\Players\Models\Player;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cirelramos\Cache\Models\CacheModel;

/**
 *
 */
class Team extends CacheModel
{
    use SoftDeletes;

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

    public const TAG_CACHE_MODEL = 'TAG_CACHE_TEAM_';

    public function players()
    {
        return $this->belongsToMany(Player::class, 'teams_has_players', 'id_team', 'id_player')
            ->whereNull('teams_has_players.deleted_at');
    }
}
