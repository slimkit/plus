<?php

use App\Models\AdminUser;
use Illuminate\Database\Seeder;

class AdminUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminUser::create(['create_user_id' => 1, 'user_id' => 1]);
        AdminUser::create(['create_user_id' => 1, 'user_id' => 2]);
    }
}
