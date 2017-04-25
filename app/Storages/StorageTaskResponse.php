<?php

namespace Zhiyi\Plus\Storages;

class StorageTaskResponse
{
    protected $uri;
    protected $method;
    protected $headers = [];
    protected $options = [];
    protected $inputName = 'file';

    /**
     * Create the response.
     *
     * @var
     */
    public static function create(string $uri, string $method = 'POST', array $headers = [], array $options = []): self
    {
        $response = new self();
        $response->setURI($uri)
            ->setMethod($method)
            ->setHeaders($headers)
            ->setOptions($options);

        return $response;
    }

    /**
     * Set uri.
     *
     * @param string $uri
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setURI(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Set method.
     *
     * @param string $method
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Set headers.
     *
     * @param array $headers
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setHeaders(array $headers = []): self
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Set options.
     *
     * @param array $options
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setOptions(array $options = []): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Set input name.
     *
     * @param string $name
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setInputName(string $name): self
    {
        $this->inputName = $name;

        return $this;
    }

    /**
     * Get the response array.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function toArray(): array
    {
        return [
            'uri' => $this->uri,
            'method' => $this->method,
            'headers' => $this->headers,
            'options' => $this->options,
            'input' => $this->inputName,
        ];
    }
}
