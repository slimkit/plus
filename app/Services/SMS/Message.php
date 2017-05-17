<?php

namespace Zhiyi\Plus\Services\SMS;

class Message
{
    protected $phone;
    protected $message;
    protected $data = [];

    /**
     * 构造创建消息.
     *
     * @param mixed $phone
     * @param string $message
     * @param array $data
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct($phone = null, string $message = '', array $data = [])
    {
        $this->setPhone($phone)
            ->setMessage($message)
            ->setData($data);
    }

    /**
     * Get phone.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Get message.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Get data.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Set phone.
     *
     * @param mixed $phone
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setPhone($phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Set message.
     *
     * @param string $message
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set data.
     *
     * @param array $data
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
