<?php

use Zhiyi\Plus\Models\Node;
use Illuminate\Database\Seeder;

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
