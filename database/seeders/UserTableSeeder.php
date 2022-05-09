<?php

namespace Database\Seeders;

use App\Core\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class UserTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        User::flushEventListeners();

        $newUsers[] = [
            'name'      => 'user 1',
            'email'     => 'user1@gmail.com',
            'confirmed' => true,
            'password'  => bcrypt('123456'),
        ];
        $newUsers[] = [
            'name'      => 'user 2',
            'email'     => 'user2@gmail.com',
            'confirmed' => true,
            'password'  => bcrypt('123456'),
        ];
        $newUsers[] = [
            'name'      => 'user 3',
            'email'     => 'user3@gmail.com',
            'confirmed' => true,
            'password'  => bcrypt('123456'),
        ];

        User::insert($newUsers);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
