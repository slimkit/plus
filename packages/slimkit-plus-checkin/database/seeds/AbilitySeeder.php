<?php

namespace SlimKit\PlusCheckIn\Seeds;

use Zhiyi\Plus\Models\Ability;
use Illuminate\Database\Seeder;

class AbilitySeeder extends Seeder
{
    /**
     * Run Abilitys Node Insert Data Method.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function run()
    {
        Ability::create([
            'name' => 'admin: checkin config',
            'display_name' => '签到管理',
            'description' => '用户是否拥有后台管理签到权限',
        ]);
    }
}
