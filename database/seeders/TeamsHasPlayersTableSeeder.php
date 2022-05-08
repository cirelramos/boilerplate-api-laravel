<?php

namespace Database\Seeders;

use App\Core\Players\Models\Player;
use App\Core\Teams\Models\Team;
use App\Core\Teams\Models\TeamHasPlayer;
use Carbon\Carbon;
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
        TeamHasPlayer::truncate();
        TeamHasPlayer::flushEventListeners();

        $newTeamsHasPlayers = [];

        $newTeamsHasPlayers[] = [
            'id_team'    => Team::all()->last()?->id_team,
            'id_player'  => Player::all()->first()?->id_player,
            'created_at' => Carbon::now(),
        ];
        $newTeamsHasPlayers[] = [
            'id_team'    => Team::all()->first()?->id_team,
            'id_player'  => Player::all()->last()?->id_player,
            'created_at' => Carbon::now(),
        ];
        $newTeamsHasPlayers[] = [
            'id_team'    => Team::all()->random()?->first()?->id_team,
            'id_player'  => Player::all()->random()?->first()?->id_player,
            'created_at' => Carbon::now(),
        ];

        TeamHasPlayer::insert($newTeamsHasPlayers);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
