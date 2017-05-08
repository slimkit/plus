<?php

namespace Zhiyi\Plus\Services\Admin;

use Closure;
use Illuminate\Support\Collection;
use Zhiyi\Plus\Models\CommonConfig;

class Forms
{
    /**
     * 保存一个表单配置.
     *
     * @param FormCreate $form
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function save(FormCreate $form)
    {
        return $form->saveToDatabase();
    }

    public function find(callable $query): Collection
    {
        $forms = CommonConfig::byNamespace('admin-forms')->where($query)
            ->get();

        return $forms->map(
            Closure::bind(function (CommonConfig $form) {
                return $this->formatForm($form);
            }, $this)
        );
    }

    /**
     * 获取全部表单配置.
     *
     * @return \Illuminate\Support\Collection
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function all(): Collection
    {
        return $this->find(function () {
            // Not.
        });
    }

    /**
     * Format form.
     *
     * @param CommonConfig $form
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function formatForm(CommonConfig $form): array
    {
        $value = json_decode($form->value, true);

        return [
            'name' => $form->name,
            'type' => array_get($value, 'type'),
            'save' => array_get($value, 'save'),
            'data' => array_get($value, 'data'),
            'form' => array_get($value, 'form'),
        ];
    }
}
