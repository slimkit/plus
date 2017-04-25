<?php

namespace Zhiyi\Plus\Services;

use Zhiyi\Plus\Models\CommonConfig;

class Storage
{
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
     * 获取储存引擎选择.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getEngineSelect(): string
    {
        static $select;

        if (! $select) {
            $select = CommonConfig::byNamespace('storage')->byName('select')->value('value') ?: 'local';
        }

        return $select;
    }

    /**
     * 设置选择的储存引擎.
     *
     * @param string $engine
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setEngineSelect(string $engine): bool
    {
        return (bool) CommonConfig::byNamespace('storage')->byName('select')->update([
            'value' => $engine,
        ]);
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
     * 添加一个储存引擎到数据库.
     *
     * @param string $engine 引擎名称
     * @param string $name 引擎显示名称
     * @param string $engineClassName 引擎注入类
     * @param array $option 引擎后台配置表单参数
     * @return bool 是否添加成功
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function appendEngine(string $engine, string $name, string $engineClassName, array $option = []): bool
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
     * 删除一个储存引擎.
     *
     * @param string $engine
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function deleteEngine(string $engine): bool
    {
        $engines = $this->getEngines();

        if (isset($engines[$engine])) {
            unset($engines[$engine]);
        }

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
        $engineOptionModel = CommonConfig::byNamespace('storage')->byName($engine)->first();

        if (! $engineOptionModel) {
            $engineOptionModel = new CommonConfig();
            $engineOptionModel->namespace = 'storage';
            $engineOptionModel->name = $engine;
            $engineOptionModel->value = json_encode([]);
            $engineOptionModel->save();
        }

        return $engineOptionModel;
    }

    /**
     * 获取储存引擎配置.
     *
     * @param string $engine
     * @param array $defaultOption
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getEngineOption(string $engine, array $defaultOption = []): array
    {
        $option = json_decode($this->getEngineOptionModel($engine)->value, true);
        if (! $option || empty($option) || ! is_array($option)) {
            return $defaultOption;
        }

        return $option;
    }

    /**
     * 设置储存引擎配置.
     *
     * @param string $engine
     * @param array $option
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setEngineOption(string $engine, array $option): bool
    {
        $option = $this->testOptionInout(array_get($this->getEngines(), $engine.'.option'), $option);
        $value = json_encode($option);

        return (bool) CommonConfig::byNamespace('storage')->byName($engine)->update([
            'value' => $value,
        ]);
    }

    /**
     * 验证 option 表单字段.
     *
     * @param CommonConfig $engineOptionModel option 模型
     * @param array $option 表单数据
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testOptionInout(array $optionRoles, array $option): array
    {
        $newOption = [];
        $roles = collect($optionRoles)->keyBy('name');
        $roles->each(function ($role, $key) use ($option, &$newOption) {
            $optionValue = array_get($option, $key);
            $type = array_get($role, 'type');

            // 文本输入框
            if ($type === 'text') {
                $newOption[$key] = is_string($optionValue) ? $optionValue : array_get($role, 'value');

                return;

            // 多选
            } elseif (
                ($type === 'checkbox' && is_array($optionValue)) ||
                ($type === 'select' && array_get($role, 'multiple'))
            ) {
                $values = array_keys(array_get($role, 'items', []));
                collect($optionValue)->each(function ($value) use ($values, $key) {
                    if (! in_array($value, $values)) {
                        throw new \Exception(sprintf('验证的储存引擎表单%s储存的值不存在于规定表单中', $key), 422);
                    }
                });
                $newOption[$key] = $optionValue;

                return;

            // 单选
            } elseif (
                $type === 'radio' ||
                ($type === 'select' && ! array_get($role, 'multiple'))
            ) {
                $values = array_keys(array_get($role, 'items', []));
                if (! in_array($optionValue, $values) && $optionValue) {
                    throw new \Exception(sprintf('验证的储存引擎表单%s储存的值不存在于规定表单中', $key), 422);
                }

                $newOption[$key] = is_string($optionValue) ? $optionValue : array_get($role, 'value');

                return;
            }

            throw new \Exception(sprintf('表单%s不允许不传递'), 422);
        });

        return $newOption;
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
        $value = json_encode($engines);

        return (bool) CommonConfig::byNamespace('storage')->byName('engines')->update([
            'value' => $value,
        ]);
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
            $engineModel->value = json_encode($this->defaultEngines());
            $engineModel->save();
        }

        return $engineModel;
    }
}
