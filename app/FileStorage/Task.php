<?php

declare(strict_types=1);

namespace Zhiyi\Plus\FileStorage;

class Task implements TaskInterface
{
    protected $uri;
    protected $method;
    protected $from;
    protected $fileKey;
    protected $headers;

    public function __construct(string $uri, string $method, ?array $form = null, ?string $fileKey = null, array $headers = [])
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->form = $form;
        $this->fileKey = $fileKey;
        $this->headers = $headers;
    }

    /**
     * Get the task URI.
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Get the task method.
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get the task headers.
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get the task form.
     * @return null|array
     */
    public function getForm(): ?array
    {
        return $this->form;
    }

    /**
     * Get the task file key.
     * @return null|string
     */
    public function getFileKey(): ?string
    {
        return $this->fileKey;
    }
}
