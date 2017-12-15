<?php

namespace SlimKit\PlusID\Support;

class Message
{
    protected $status;
    protected $code;
    protected $data = [];

    public function __construct($code, $status, array $data = [])
    {
        $this->status = $status;
        $this->code = $code;
        $this->data = $data;
    }

    public function toArray(): array
    {
        return array_merge($this->data, [
            'code' => $this->code,
            'status' => $this->status,
        ]);
    }
}
