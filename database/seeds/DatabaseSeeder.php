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
        $this->call(UsersTableSeeder::class);
        $this->call(UserGroupsTableSeeder::class);
        $this->call(UserGroupLinksTableSeeder::class);
        $this->call(NodesTableSeeder::class);
        $this->call(NodeLinksTableSeeder::class);
        $this->call(AdminUsersTableSeeder::class);
        $this->call(AreasTableSeeder::class);
        $this->call(UserProfileSettingsTableSeeder::class);
        $this->call(UserProfileSettingLinksTableSeeder::class);
    }
}
