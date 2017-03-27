<?php

namespace Zhiyi\Plus\Services;

use Zhiyi\Plus\Models\CommonConfig;

class Storage
{
    /**
     * 初始化储存引擎.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct()
    {
        $this->initStorage();
    }

    /**
     * 获取默认配置.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function defaultEngines()
    {
        return [
            'local' => [
                'name' => '本地储存',
                'engine' => \Zhiyi\Plus\Storages\Engine\LocalStorage::class,
                'option' => [],
            ],
        ];
    }

    /**
     * 获取储存过程.
     *
     * @return \Zhiyi\Plus\Storages\Storage
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getStorage()
    {
        return app(\Zhiyi\Plus\Storages\Storage::class);
    }

    /**
     * 初始化储存，设置所有的储存引擎.
     *
     * @return [type] [description]
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function initStorage()
    {
        $engines = $this->getEngines();
        foreach ($engines as $engine => $value) {
            $this->getStorage()->setStorageEngine($engine, app($value['engine']));
        }
    }

    /**
     * 添加一个储存引擎到数据库.
     *
     * @param string $engine 引擎名称
     * @param string $name 引擎显示名称
     * @param string $engineClassName 引擎注入类
     * @param array $option 引擎后台配置表单参数
     * @return bool 是否添加成功
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function appendEngine(string $engine, string $name, string $engineClassName, array $option = [])
    {
        $engines = $this->getEngines();
        $engines[$engine] = [
            'name' => $name,
            'engine' => $engineClassName,
            'option' => $option,
        ];

        return $this->setEngines($engines);
    }

    /**
     * 获取储存引擎配置模型.
     *
     * @param string $engine
     * @return \Zhiyi\Plus\Models\CommonConfig
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getEngineOptionModel(string $engine): CommonConfig
    {
        $engine = CommonConfig::byNamespace('storage')->byName($engine)->first();

        if (! $engine) {
            $engine = new CommonConfig();
            $engine->namespace = 'storage';
            $engine->name = $engine;
            $engine->value = json_encode([]);
            $engine->save();
        }

        return $engine;
    }

    /**
     * 获取储存引擎配置.
     *
     * @param string $engins
     * @param array $defaultOption
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getEngineOption(string $engins, array $defaultOption = []): array
    {
        $option = json_decode($this->getEngineOptionModel($engine)->value, true);
        if (! $option || empty($option) || !is_array($option)) {
            return $defaultOption;
        }

        return $option;
    }

    /**
     * 设置储存引擎配置
     *
     * @param string $engins
     * @param array $option
     * @param array $baseOption
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setEngineOption(string $engins, array $option, array $baseOption = []): bool
    {
        $option = array_merge($baseOption, $option);
        $engineOption = $this->getEngineOptionModel();
        $engineOption->value = json_encode($option);

        return $engineOption->save();
    }

    /**
     * 设置所有的储存引擎.
     *
     * @param array $engines
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setEngines(array $engines): bool
    {
        $engines = array_merge($engines, $this->defaultEngines());
        $engine = $this->getEnginesModel();
        $engine->value = json_encode($engines);

        return $engine->save();
    }

    /**
     * 获取所有的储存引擎.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getEngines(): array
    {
        $engines = (array) json_decode($this->getEnginesModel()->value, true);

        return array_merge($engines, $this->defaultEngines());
    }

    /**
     * 获取储存引擎数据模型.
     *
     * @return \Zhiyi\Plus\Models\CommonConfig
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getEnginesModel(): CommonConfig
    {
        $engineModel = CommonConfig::byNamespace('storage')->byName('engines')->first();

        if (! $engineModel) {
            $engineModel = new CommonConfig();
            $engineModel->namespace = 'storage';
            $engineModel->name = 'engines';
            $engineModel->value = $this->defaultEngines();
            $engineModel->save();
        }

        return $engineModel;
    }
}
