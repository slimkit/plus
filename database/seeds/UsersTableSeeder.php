<?php

use Zhiyi\Plus\Models\Role;
use Zhiyi\Plus\Models\User;
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
        $this->call(RoleSeeder::class);
        $this->createFounderUser();
    }

    /**
     * Insert the founder information.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function createFounderUser()
    {
        $user = User::create(['name' => 'root', 'password' => bcrypt('root')]);
        $user->roles()->sync(
            Role::all()
        );
    }
}
