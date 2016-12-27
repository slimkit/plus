<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name' => 'Seven', 'phone' => '18781993583', 'password' => bcrypt('123456')]);
        User::create(['name' => 'Wayne', 'phone' => '18781993584', 'password' => bcrypt('123456')]);
    }
}
