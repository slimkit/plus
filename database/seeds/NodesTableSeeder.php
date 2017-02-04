<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\Node;

class NodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Node::create(['name' => '登录后台', 'route_name' => 'admin.login']);
    }
}
