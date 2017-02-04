<?php

use Zhiyi\Plus\Models\NodeLink;
use Illuminate\Database\Seeder;

class NodeLinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NodeLink::create(['node_id' => 1, 'user_group_id' => 1]);
    }
}
