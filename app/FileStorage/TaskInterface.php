<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage;

interface TaskInterface
{
    /**
     * Get the task URI.
     * @return string
     */
    public function getUri(): string;

    /**
     * Get the task method.
     * @return string
     */
    public function getMethod(): string;

    /**
     * Get the task headers.
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Get the task form.
     * @return null|array
     */
    public function getForm(): ?array;

    /**
     * Get the task file key.
     * @return null|string
     */
    public function getFileKey(): ?string;

    /**
     * Get resource node string.
     * @return string
     */
    public function getNode(): string;

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array;
}
