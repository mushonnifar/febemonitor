<?php

use Illuminate\Database\Seeder;

class ActionsTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //
        app('db')->table('actions')->insert([
            [
                'id' => 1,
                'name' => 'create'
            ],
            [
                'id' => 2,
                'name' => 'read',
            ],
            [
                'id' => 3,
                'name' => 'update',
            ],
            [
                'id' => 4,
                'name' => 'delete',
            ],
            [
                'id' => 5,
                'name' => 'print',
            ],
            [
                'id' => 6,
                'name' => 'approve',
            ]
        ]);
    }

}
