<?php

namespace Database\Seeders;

use App\Core\Players\Models\Player;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class PlayersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Player::truncate();
        Player::flushEventListeners();

        $newPlayers = [];

        $newPlayers[] = [
            'name'       => 'Alejandro smith',
            'url'        => 'http://alejandro-smith.cirelramos.com',
            'photo'      => '',
            'rank'       => 6,
            'active'     => 1,
            'renew'      => 1,
            'renew_next' => Carbon::now()->addMonths(12),
            'created_at' => Carbon::now(),
        ];
        $newPlayers[] = [
            'name'       => 'Jose Perez',
            'url'        => 'http://jose-perez.cirelramos.com',
            'photo'      => '',
            'rank'       => 10,
            'active'     => 1,
            'renew'      => 0,
            'renew_next' => null,
            'created_at' => Carbon::now(),
        ];
        $newPlayers[] = [
            'name'       => 'Carlos Gutierrez',
            'url'        => 'http://carlos-gutierrez.cirelramos.com',
            'photo'      => '',
            'rank'       => 1,
            'active'     => 0,
            'renew'      => 1,
            'renew_next' => Carbon::now()->addMonths(12),
            'created_at' => Carbon::now(),
        ];

        Player::insert($newPlayers);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
