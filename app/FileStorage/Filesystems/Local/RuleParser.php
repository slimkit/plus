<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\FileStorage\Filesystems\Local;

class RuleParser
{
    protected $rule = [];

    public function __construct(?string $rule = null)
    {
        if (! is_null($rule)) {
            $this->parse(urldecode($rule));
        }
    }

    protected function parse(?string $rule): void
    {
        if (! $rule) {
            return;
        }

        $rules = explode(',', $rule);
        foreach ($rules as $rule) {
            $rule = explode('_', $rule);
            $this->rule[$rule[0]] = $rule[1];
        }
    }

    public function getRule(string $key, $default)
    {
        $value = $this->rule[$key] ?? $default;
        if (! $value) {
            return $default;
        }

        return $value;
    }

    public function getQuality(): int
    {
        return (int) $this->getRule('q', 90);
    }

    public function getBlur(): int
    {
        $blur = (int) $this->getRule('b', 0);
        $blur = min(100, $blur);
        $blur = max(0, $blur);

        return $blur;
    }

    public function getWidth(): ?float
    {
        return (float) $this->getRule('w', 0.0);
    }

    public function getHeight(): ?float
    {
        return (float) $this->getRule('h', 0.0);
    }

    public function getFilename(): string
    {
        return sprintf('w%s-h%s-b%s-q%s', $this->getWidth(), $this->getHeight(), $this->getBlur(), $this->getQuality());
    }
}
