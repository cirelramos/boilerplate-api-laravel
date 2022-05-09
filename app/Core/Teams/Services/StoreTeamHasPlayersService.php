<?php

namespace App\Core\Teams\Services;

use App\Core\Countries\Models\CountryRegionPivot;
use App\Core\Teams\Models\Team;
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

        CountryRegionPivot::insert($players->toArray());
    }

    private function mapSetPlayer(Team $team): callable
    {
        return static function ($player, $key) use ($team) {
            $newPlayer[ 'id_player' ]  = $player[ 'identifier' ];
            $newPlayer[ 'id_region' ]  = $team->id_team;
            $newPlayer[ 'created_at' ] = Carbon::now();

            return $newPlayer;
        };
    }

}
