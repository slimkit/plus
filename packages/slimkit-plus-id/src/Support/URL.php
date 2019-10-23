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

namespace SlimKit\PlusID\Support;

use Illuminate\Support\Arr;

class URL
{
    /**
     * The uri scheme.
     *
     * @var string
     */
    protected $scheme = null;

    /**
     * The uri host.
     *
     * @var string
     */
    protected $host = null;

    /**
     * The uri host.
     *
     * @var string
     */
    protected $port = null;

    /**
     * The uri user.
     *
     * @var string
     */
    protected $user = null;

    /**
     * The uri user password.
     *
     * @var string
     */
    protected $pass = null;

    /**
     * The uri path.
     *
     * @var string
     */
    protected $path = null;

    /**
     * The uri query.
     *
     * @var array
     */
    protected $query = [];

    /**
     * The uri fragment.
     *
     * @var string
     */
    protected $fragment = null;

    /**
     * Create the URL parse.
     *
     * @param string $uri
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(string $uri)
    {
        $parts = parse_url(urldecode($uri));
        if (Arr::get($parts, 'scheme') === 'http' && ! Arr::get($parts, 'port')) {
            $this->port = 80;
        } elseif (Arr::get($parts, 'scheme') === 'https' && ! Arr::get($parts, 'port')) {
            $this->port = 443;
        }

        $parts += [
            'scheme' => 'http',
            'host' => null,
            'user' => null,
            'pass' => null,
            'path' => '',
            'query' => [],
            'fragment' => null,
        ];

        foreach ($parts as $key => $value) {
            $this->$key = $value;
        }

        $query = [];
        if (! empty($parts['query'])) {
            parse_str($parts['query'], $query);
        }

        $this->query = $query;
    }

    /**
     * Get the URL protected.
     *
     * @param string $key
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function get(string $key)
    {
        return $this->$key;
    }

    /**
     * Set the URL protected.
     *
     * @param string $key
     * @param mixed $value
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function set(string $key, $value)
    {
        $this->$key = $value;

        return $this;
    }

    /**
     * Add a query key.
     *
     * @param string $key
     * @param string|array $value
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function addQuery(string $key, $value)
    {
        $query = array_merge($this->query, [$key => $value]);

        return $this->set('query', $query);
    }

    public function make(): string
    {
        $url = $this->scheme.'://';
        if ($this->user && $this->pass) {
            $url .= $this->user.':'.$this->password.'@';
        }

        $url .= $this->host;
        if (! ($this->scheme === 'http' && $this->port == 80) && ! ($this->scheme === 'https' && $this->port == 443)) {
            $url .= ':'.$this->port;
        }

        $url .= $this->path;
        if (! empty($this->query)) {
            $url .= '?'.http_build_query($this->query);
        }

        if ($this->fragment) {
            $url .= '#'.$this->fragment;
        }

        return $url;
    }

    /**
     * Make url to string.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __toString()
    {
        return $this->make();
    }
}
