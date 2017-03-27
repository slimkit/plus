<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Services\Storage;

class CommonConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedSite();
        $this->seedStorage();
    }

    /**
     * 网站基础信息
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function seedSite()
    {
        CommonConfig::create([
            // 网站标题
            [
                'name' => 'title',
                'namespace' => 'site',
                'value' => 'ThinkSNS +'
            ],
            // 网站关键词
            [
                'name' => 'keywords',
                'namespace' => 'site',
                'value' => 'ts,ts+,thinksns,plus,laravel'
            ],
            // 网站描述
            [
                'name' => 'description',
                'namespace' => 'site',
                'value' => '基于 Laravel 而生的未来中心用户核心系统'
            ],
            // 备案信息
            [
                'name' = 'icp',
                'namespace' => 'site',
                'value' => '蜀ICP备xxxxxxxx号-1'
            ]
        ]);
    }

    /**
     * 储存配置信息.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function seedStorage()
    {
        /**
         * 插入默认储存引擎配置
         */
        app(Storage::class)->setEngines([]);

        // 插入默认选择
        CommonConfig::create([
            'name' => 'select',
            'namespace' => 'storage',
            'value' => 'local'
        ]);
    }
}
