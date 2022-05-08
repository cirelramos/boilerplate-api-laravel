<?php

use App\Core\Players\Models\Player;
use App\Core\Teams\Models\Team;
use App\Core\Teams\Models\TeamHasPlayer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class TeamsHasPlayersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Team::truncate();
        Team::flushEventListeners();

        $newTeamsHasPlayers = [];

        $newTeamsHasPlayers[] = [
            'id_team'   => Team::all()->last()?->id_team,
            'id_player' => Player::all()->first()?->id_team,
        ];
        $newTeamsHasPlayers[] = [
            'id_team'   => Team::all()->first()?->id_team,
            'id_player' => Player::all()->last()?->id_team,
        ];
        $newTeamsHasPlayers[] = [
            'id_team'   => Team::all()->random()?->first()?->id_team,
            'id_player' => Player::all()->random()?->last()?->id_team,
        ];

        TeamHasPlayer::insert($newTeamsHasPlayers);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
