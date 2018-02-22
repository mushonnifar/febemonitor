<?php

use Illuminate\Database\Seeder;

class RolehasrouteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('rolehasroute')->insert([
            [
                'id' => 1,
                'role_id' => 1,
                'route_id' => 1
            ],
            [
                'id' => 2,
                'role_id' => 1,
                'route_id' => 2
            ],
            [
                'id' => 3,
                'role_id' => 1,
                'route_id' => 3
            ],
            [
                'id' => 4,
                'role_id' => 1,
                'route_id' => 4
            ],
            [
                'id' => 5,
                'role_id' => 1,
                'route_id' => 5
            ],
            [
                'id' => 6,
                'role_id' => 1,
                'route_id' => 6
            ],
            [
                'id' => 7,
                'role_id' => 1,
                'route_id' => 7
            ],
            [
                'id' => 8,
                'role_id' => 1,
                'route_id' => 8
            ],
            [
                'id' => 9,
                'role_id' => 1,
                'route_id' => 9
            ],
            [
                'id' => 10,
                'role_id' => 1,
                'route_id' => 10
            ],
        ]);
    }
}
