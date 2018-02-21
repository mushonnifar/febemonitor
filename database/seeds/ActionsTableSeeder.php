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
                'name' => 'create'
            ],
            [
                'name' => 'read',
            ],
            [
                'name' => 'update',
            ],
            [
                'name' => 'delete',
            ],
            [
                'name' => 'print',
            ],
            [
                'name' => 'approve',
            ]
        ]);
    }

}
