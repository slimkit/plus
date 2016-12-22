<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;

class MessageResponseBody extends JsonResponse
{
    protected $status = false;
    protected $code = 0;
    protected $message = '';
    protected $anyData = null;

    /**
     * 构造方法.
     *
     * @param bool|bool $status  状态
     * @param int|int   $code    状态码
     * @param string    $message 消息
     * @param any       $data    携带数据
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function __construct(bool $status = false, int $code = 0, string $message = '', $data = null)
    {
        parent::__construct();
        $this->setStatus($status)
            ->setMessage($message)
            ->setCode($code)
            ->setData($data);
    }

    /**
     * 设置状态
     *
     * @param bool|bool $status 状态
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function setStatus(bool $status = false): self
    {
        $this->status = $status;
        parent::setData($this->getBody());

        return $this;
    }

    /**
     * 设置消息.
     *
     * @param string $message 消息
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        parent::setData($this->getBody());

        return $this;
    }

    /**
     * 设置状态码
     *
     * @param int $code 状态码
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function setCode(int $code): self
    {
        $this->code = $code;
        parent::setData($this->getBody());

        return $this;
    }

    /**
     * 设置数据.
     *
     * @param any $data 数据
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function setData($data = null): self
    {
        $this->anyData = $data;
        parent::setData($this->getBody());

        return $this;
    }

    /**
     * 获取设置的数据.
     *
     * @return array 数据结构
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function getBody()
    {
        return [
            'status'  => $this->status,
            'code'    => $this->code,
            'message' => $this->message,
            'data'    => $this->anyData,
        ];
    }
}
