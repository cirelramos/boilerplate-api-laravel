<?php

namespace App\Core\Teams\Services;

use App\Core\Teams\Models\Team;
use App\Core\Teams\Models\TeamHasPlayer;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 *
 */
class StoreTeamHasPlayersService
{

    public function execute(Team $team, Request $request): void
    {
        $players = $request->input('players');
        $players = collect($players);

        $players = $players->map($this->mapSetPlayer($team));

        TeamHasPlayer::insertWithCache($players->toArray());
    }

    private function mapSetPlayer(Team $team): callable
    {
        return static function ($player, $key) use ($team) {
            $newPlayer[ 'id_player' ]  = $player[ 'identifier' ];
            $newPlayer[ 'id_team' ]  = $team->id_team;
            $newPlayer[ 'created_at' ] = Carbon::now();

            return $newPlayer;
        };
    }

}
