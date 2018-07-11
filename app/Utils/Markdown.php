<?php

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

namespace Zhiyi\Plus\Utils;

use Parsedown;
use HTMLPurifier;
use League\HTMLToMarkdown\HtmlConverter;

class Markdown
{
    /**
     * Markdown to html parser.
     *
     * @var \Parsedown
     */
    protected $parsedown;

    /**
     * Html to Markdown parser.
     *
     * @var \League\HTMLToMarkdown\HtmlConverter
     */
    protected $htmlConverter;

    /**
     * Create the Markdown util instance.
     *
     * @param \Parsedown $parsedown
     * @param \League\HTMLToMarkdown\HtmlConverter $htmlConverter
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(Parsedown $parsedown, HtmlConverter $htmlConverter)
    {
        $this->parsedown = $parsedown;
        $this->htmlConverter = $htmlConverter;
    }

    /**
     * Markdown to html.
     *
     * @param string $markdown
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function toHtml(string $markdown): string
    {
        return $this->parsedown->parse($markdown);
    }

    /**
     * Html to markdown.
     *
     * @param string $html
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function toMarkdown(string $html): string
    {
        return $this->htmlConverter->convert($html);
    }

    /**
     * Get safety markdown string.
     *
     * @param string $markdown
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function safetyMarkdown(string $markdown): string
    {
        $html = $this->toHtml($markdown);
        $html = $this->safetyHtml($html);

        return $this->toMarkdown($html);
    }

    /**
     * Get safety html string.
     *
     * @param string $html
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function safetyHtml(string $html): string
    {
        return $this->filterHtml($html);
    }

    /**
     * Filter html.
     *
     * @param string $html
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function filterHtml(string $html): string
    {
        return app(HTMLPurifier::class)->purify($html);
    }
}
