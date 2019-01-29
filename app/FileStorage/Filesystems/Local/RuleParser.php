<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\FileStorage\Filesystems\Local;

class RuleParser
{
    /**
     * Cacheing the rules.
     * @var array
     */
    protected $rule = [];

    /**
     * Create a new rule.
     * @param null|string $rule
     */
    public function __construct(?string $rule = null)
    {
        if (! is_null($rule)) {
            $this->parse(urldecode($rule));
        }
    }

    /**
     * Parse a rule.
     * @param null|string $rule
     * @return void
     */
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

    /**
     * Get rule.
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getRule(string $key, $default)
    {
        $value = $this->rule[$key] ?? $default;
        if (! $value) {
            return $default;
        }

        return $value;
    }

    /**
     * Get quality.
     * @return int
     */
    public function getQuality(): int
    {
        return (int) $this->getRule('q', 90);
    }

    /**
     * Get blur.
     * @return int
     */
    public function getBlur(): int
    {
        $blur = (int) $this->getRule('b', 0);
        $blur = min(100, $blur);
        $blur = max(0, $blur);

        return $blur;
    }

    /**
     * Get width.
     * @return null|float
     */
    public function getWidth(): ?float
    {
        $width = (float) $this->getRule('w', 0.0);

        return $width ?: null;
    }

    /**
     * Get height.
     * @return null|float
     */
    public function getHeight(): ?float
    {
        $height = (float) $this->getRule('h', 0.0);

        return $height ?: null;
    }

    /**
     * Get cacheing filename.
     * @return string
     */
    public function getFilename(): string
    {
        return sprintf('w%s-h%s-b%s-q%s', $this->getWidth(), $this->getHeight(), $this->getBlur(), $this->getQuality());
    }
}
