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

    public function getPhone()
    {
        return $phone;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setPhone($phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
