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
        $this->call(UserProfileSettingsTableSeeder::class);
        $this->call(UserProfileSettingLinksTableSeeder::class);
        $this->call(CommonConfigSeeder::class);

        // 把地区放在最后，因为耗时较长.
        $this->call(AreasTableSeeder::class);
    }
}
