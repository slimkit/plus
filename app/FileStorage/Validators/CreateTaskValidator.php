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

namespace Zhiyi\Plus\FileStorage\Validators;

use Zhiyi\Plus\FileStorage\ChannelManager;
use Zhiyi\Plus\FileStorage\Exceptions\NotAllowUploadMimeTypeException;
use function Zhiyi\Plus\setting;

class CreateTaskValidator extends AbstractValidator
{
    /**
     * Caching configures.
     * @var array
     */
    protected $configure;

    /**
     * Get the validate rules.
     * @return array
     */
    public function rules(bool $image = false): array
    {
        $rules = [
            'filename' => ['bail', 'required', 'string', 'regex:/(.*?)\.(\w+)$/is'],
            'hash' => ['bail', 'required', 'string'],
            'size' => ['bail', 'required', 'integer', $this->getAllowMinSize(), $this->getAllowMaxSize()],
            'mime_type' => ['bail', 'required', 'string', $this->getAllowMimeTypes()],
            'storage' => ['bail', 'required', 'array'],
            'storage.channel' => ['bail', 'required', 'string', 'in:'.implode(',', ChannelManager::DRIVES)],
        ];

        if ($image) {
            $rules = array_merge($rules, [
                'dimension' => ['bail', 'required', 'array'],
                'dimension.width' => ['bail', 'required', 'numeric', $this->getAllowImageMinWidth(), $this->getAllowImageMaxWidth()],
                'dimension.height' => ['bail', 'required', 'numeric', $this->getAllowImageMinHeight(), $this->getAllowImageMaxHeight()],
            ]);
        }

        return $rules;
    }

    /**
     * Get the validate error messages.
     * @return array
     */
    public function messages(): array
    {
        return [
            'filename.regex' => '文件名非法！',
        ];
    }

    /**
     * Get image allow min width.
     * @return string
     */
    protected function getAllowImageMinWidth(): string
    {
        return sprintf('min:%d', $this->getConfigure()['image-min-width'] ?? 0);
    }

    /**
     * Get image allow max width.
     * @return string
     */
    protected function getAllowImageMaxWidth(): string
    {
        return sprintf('max:%d', $this->getConfigure()['image-max-width'] ?? 0);
    }

    /**
     * Get image allow min height.
     * @return string
     */
    protected function getAllowImageMinHeight(): string
    {
        return sprintf('min:%d', $this->getConfigure()['image-min-height'] ?? 0);
    }

    /**
     * Get image allow max height.
     * @return string
     */
    protected function getAllowImageMaxHeight(): string
    {
        return sprintf('max:%d', $this->getConfigure()['image-max-height'] ?? 0);
    }

    /**
     * Get allow min file size.
     * @return string
     */
    protected function getAllowMinSize(): string
    {
        return sprintf('min:%d', $this->getConfigure()['file-min-size'] ?? 0);
    }

    /**
     * Get allow max file size.
     * @return string
     */
    protected function getAllowMaxSize(): string
    {
        return sprintf('max:%d', $this->getConfigure()['file-max-size'] ?? 0);
    }

    /**
     * Get allow mime types.
     * @return string
     */
    protected function getAllowMimeTypes(): string
    {
        $mimeTypes = $this->getConfigure()['file-mime-types'];
        if (empty($mimeTypes)) {
            throw new NotAllowUploadMimeTypeException();
        }

        return sprintf('in:%s', implode(',', $mimeTypes));
    }

    protected function getConfigure(): array
    {
        if ($this->configure) {
            return $this->configure;
        }

        return $this->configure = setting('file-storage', 'task-create-validate', [
            'image-min-width' => 0,
            'image-max-width' => 2800,
            'image-min-height' => 0,
            'image-max-height' => 2800,
            'file-min-size' => 2048, // 2KB
            'file-mix-size' => 2097152, // 2MB
            'file-mime-types' => [],
        ]);
    }
}
