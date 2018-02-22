<?php

use Illuminate\Database\Seeder;

class RoutesTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        app('db')->table('routes')->insert([
            [
                'id' => 1,
                'name' => 'user'
            ],
            [
                'id' => 2,
                'name' => 'action',
            ],
            [
                'id' => 3,
                'name' => 'role',
            ],
            [
                'id' => 4,
                'name' => 'menu',
            ],
            [
                'id' => 5,
                'name' => 'userrole',
            ],
            [
                'id' => 6,
                'name' => 'rolemenu',
            ],
            [
                'id' => 7,
                'name' => 'rolemenuaction',
            ],
            [
                'id' => 8,
                'name' => 'menu',
            ],
            [
                'id' => 9,
                'name' => 'roleroute',
            ],
            [
                'id' => 10,
                'name' => 'rolerouteaction',
            ],
        ]);
    }

}
