<?php

namespace Zhiyi\Plus\Storages;

use Zhiyi\Plus\Models\StorageTask;

class StorageTaskResponse
{
    protected $url;
    protected $method;
    protected $headers = [];
    protected $options = [];
    protected $inputName = 'file';

    /**
     * Create the response.
     *
     * @var $this
     */
    public static function create(string $url, string $method = 'POST', array $headers = [], array $options = []): self
    {
        $response = new self();
        $response->setURL($url)
            ->setMethod($method)
            ->setHeaders($headers)
            ->setOptions($options);

        return $response;
    }

    /**
     * Set url.
     *
     * @param string $url
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setURL(string $url): self
    {
        $this->url = $url;

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
            'url' => $this->url,
            'method' => $this->method,
            'headers' => $this->headers,
            'options' => $this->options,
            'input' => $this->inputName,
        ];
    }

}
