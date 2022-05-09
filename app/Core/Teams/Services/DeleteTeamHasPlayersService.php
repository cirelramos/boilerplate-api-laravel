<?php

namespace App\Core\Teams\Services;


use App\Core\Teams\Models\Team;
use App\Core\Teams\Models\TeamHasPlayer;

/**
 *
 */
class DeleteTeamHasPlayersService
{

    public function execute(Team $team): void
    {
        TeamHasPlayer::where('id_team', $team->id_team)->delete();
    }
}
