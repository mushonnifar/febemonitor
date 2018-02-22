<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call('UsersTableSeeder');
         $this->call('ActionsTableSeeder');
         $this->call('RoutesTableSeeder');
         $this->call('RolesTableSeeder');
         $this->call('RolehasrouteTableSeeder');
         $this->call('RoleroutehasactionTableSeeder');
    }
}
