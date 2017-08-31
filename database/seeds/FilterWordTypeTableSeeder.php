<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\FilterWordType;

class FilterWordTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['禁止关键词'];
        foreach ($names as $name) {
            $count = FilterWordType::where('name', $name)->count();
            if (! $count) {
                FilterWordType::create(['name' => $name]);
            }
        }
    }
}
