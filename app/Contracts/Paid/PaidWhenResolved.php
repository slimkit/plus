<?php

namespace Zhiyi\Plus\Contracts\Paid;

interface PaidWhenResolved
{
    /**
     * Get the node key.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public static function node(): string;

    /**
     * Get the node desc.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public static function desc(): string;

    /**
     * Get the node forms.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public static function form(): array;

    /**
     * Handle.
     *
     * @param callable $callable
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle(callable $callable);

    /**
     * 生成发布信息 key.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function makePayKey(): string;
}
