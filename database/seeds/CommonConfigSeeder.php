<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Services\Storage;
use Zhiyi\Plus\Models\CommonConfig;

class CommonConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedStorage();
    }

    /**
     * 储存配置信息.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function seedStorage()
    {
        /*
         * 插入默认储存引擎配置
         */
        app(Storage::class)->setEngines([]);

        // 插入默认选择
        CommonConfig::create([
            'name' => 'select',
            'namespace' => 'storage',
            'value' => 'local',
        ]);
    }
}
