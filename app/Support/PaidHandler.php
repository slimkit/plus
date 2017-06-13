<?php

namespace Zhiyi\Plus\Support;

use Zhiyi\Plus\Contracts\Paid\PaidWhenResolved;

abstract class PaidHandler implements PaidWhenResolved
{
    /**
     * The paid nodes.
     *
     * @var array
     */
    private static $nodes = [];

    protected $node;

    protected $formValues = null;

    /**
     * Publish Paid handler.
     *
     * @param string|array $node
     * @param \Zhiyi\Plus\Contracts\PaidWhenResolved $handler
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public static function publish($nodes, $handler = null)
    {
        if (! is_array($nodes)) {
            $nodes = [$nodes => $handler];
        }

        array_merge(static::$nodes, $nodes);
    }

    public function handle(callable $callable)
    {
        $callable($this, );
    }

    /**
     * Set node key.
     *
     * @param string $node
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setNode(string $node)
    {
        $this->node = $node;
    }

    /**
     * Get node key.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getNode(): string
    {
        return $this->node;
    }

    /**
     * Set node form values.
     *
     * @param mixed $values
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setFormValues($values)
    {
        $this->formValues = $values;
    }

    /**
     * Get node form values.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getFormValues()
    {
        return $this->formValues;
    }
}
