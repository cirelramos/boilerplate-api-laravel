<?php

use App\Core\Players\Models\Player;
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
            'name'   => 'Alejandro smith',
            'url'    => 'http://alejandro-smith.cirelramos.com',
            'photo'  => '',
            'rank'   => 5,
            'active' => 1,
            'renew'  => 1,
        ];
        $newPlayers[] = [
            'name'   => 'Jose Perez',
            'url'    => 'http://jose-perez.cirelramos.com',
            'photo'  => '',
            'rank'   => 10,
            'active' => 1,
            'renew'  => 0,
        ];
        $newPlayers[] = [
            'name'   => 'Carlos Gutierrez',
            'url'    => 'http://carlos-gutierrez.cirelramos.com',
            'photo'  => '',
            'rank'   => 1,
            'active' => 0,
            'renew'  => 1,
        ];

        Player::insert($newPlayers);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
