<?php

namespace Database\Seeders;

use App\Core\Teams\Models\Team;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class TeamsTableSeeder extends Seeder
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

        $newTeams = [];

        $newTeams[] = [
            'name'   => 'international',
            'url'    => 'http://international-team.cirelramos.com',
            'photo'  => '',
            'logo'   => '',
            'rank'   => 8,
            'active' => 1,
            'created_at' => Carbon::now(),
        ];
        $newTeams[] = [
            'name'   => 'national',
            'url'    => 'http://national-team.cirelramos.com',
            'photo'  => '',
            'logo'   => '',
            'rank'   => 6,
            'active' => 1,
            'created_at' => Carbon::now(),
        ];
        $newTeams[] = [
            'name'   => 'regional',
            'url'    => 'http://regional-team.cirelramos.com',
            'photo'  => '',
            'logo'   => '',
            'rank'   => 2,
            'active' => 1,
            'created_at' => Carbon::now(),
        ];

        Team::insert($newTeams);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
