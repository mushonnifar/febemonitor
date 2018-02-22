<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //
        app('db')->table('users')->insert([
            'id' => 1,
            'name' => 'administrator',
            'username' => 'admin',
            'password' => '$10$CumfBfCSJ5vPreF1vLylU.1FN9gFSUgmKustGm.FxoHq4QHH/xT/6',
            'email' => 'admin@mail.com',
        ]);
    }

}
