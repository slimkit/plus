<?php

declare(strict_types=1);

namespace SlimKit\Plus\Packages\Blog\Seeds;

use Illuminate\Database\Seeder;
use function Zhiyi\Plus\setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        setting('blog')->set('create-need-review', false);
    }
}
