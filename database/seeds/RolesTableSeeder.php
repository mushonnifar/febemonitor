<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('roles')->insert([
            'id' => 1,
            'name' => 'admin',
            'description' => 'administrator',
        ]);
    }
}
