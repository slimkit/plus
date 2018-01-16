<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\Seeds;

use Zhiyi\Plus\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'tester',
            'display_name' => '测试员',
            'description' => '测试工作人员',
            'non_delete' => 1,
        ]);
        Role::create([
            'name' => 'developer',
            'display_name' => '开发者',
            'description' => '项目开发人员',
            'non_delete' => 1,
        ]);
    }
}
