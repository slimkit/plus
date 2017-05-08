<?php

namespace Zhiyi\Plus\Services\Admin;

use InvalidArgumentException;
use Zhiyi\Plus\Models\CommonConfig;

class Form
{
    const TYPE_FORM = 'form';
    const TYPE_URL = 'url';

    protected $root;
    protected $name;
    protected $save;
    protected $data;
    protected $type = 'form';
    protected $childrens = [];

    protected $inputTypes = ['checkbox', 'hidden', 'password', 'radio', 'text'];

    /**
     * 构造方法，设置最基础的信息.
     *
     * @param string $root
     * @param string $childrenName
     * @param string $type
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(string $rootName = '', string $childrenName = '', string $type = self::TYPE_FORM)
    {
        $this->root($rootName);
        $this->name($childrenName);
        $this->type($type);
    }

    /**
     * Set root name.
     *
     * @param string $root
     * @return $this
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function root(string $root): self
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Set children name.
     *
     * @param string $name
     * @return $this
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set form type.
     *
     * @param string $type
     * @return $this
     * @throws \InvalidArgumentException
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function type(string $type): self
    {
        if (! in_array($type, [static::TYPE_URL, static::TYPE_FORM])) {
            throw new \InvalidArgumentException('The form type must be "\Zhiyi\Plus\Services\Admin\Form::TYPE_FORM" or "\Zhiyi\Plus\Services\Admin\Form::TYPE_URL".');
        }

        $this->type = $type;

        return $this;
    }

    /**
     * Set save URL.
     *
     * @param string $saveURL
     * @return $this
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function save(string $saveURL): self
    {
        $this->save = $saveURL;

        return $this;
    }

    /**
     * Set get form data URL.
     *
     * @param string $getDataURL
     * @return $this
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function data(string $getDataURL): self
    {
        $this->data = $getDataURL;

        return $this;
    }

    /**
     * Add inpur children.
     *
     * @param array $children
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function addChildren(array $children): self
    {
        $name = array_get($children, 'name');
        $type = array_get($children, 'type');

        $this->checkNameEmpty($name);
        if (! in_array($type, $this->inputTypes)) {
            throw new InvalidArgumentException('The input type must be "\Zhiyi\Plus\Services\Admin\Form::inputTypes".');
        }

        $this->childrens[$name] = $children;

        return $this;
    }

    /**
     * Check input name empty.
     *
     * @param string $name
     * @throws \InvalidArgumentException
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function checkNameEmpty(string $name)
    {
        if (! $name) {
            throw new InvalidArgumentException('The input name can not be empty.');
        }
    }

    /**
     * Get save key.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getSaveKey(): string
    {
        return $this->root.'/'.$this->name;
    }

    /**
     * Get value.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getSaveValue(): array
    {
        return [
            'type' => $this->type,
            'data' => $this->data,
            'save' => $this->save,
            'form' => array_values($this->childrens),
        ];
    }

    /**
     * Save data to database.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function saveToDatabase()
    {
        $key = $this->getSaveKey();

        // Create if the data does not exist
        if (! CommonConfig::byNamespace('admin-forms')->byName($key)->first()) {
            return factory(CommonConfig::class)->create([
                'name' => $key,
                'namespace' => 'admin-forms',
                'value' => json_encode($this->getSaveValue()),
            ]);
        }

        // Data is stored directly to the data.
        // Use else is to reduce database operations.
        else {
            return CommonConfig::byNamespace('admin-forms')->byName($key)
                ->update([
                    'value' => json_encode($this->getSaveValue()),
                ]);
        }
    }
}
