<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\NodeLink;

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
