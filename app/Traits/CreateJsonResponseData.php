<?php

namespace Zhiyi\Plus\Traits;

trait CreateJsonResponseData
{
    protected static function createJsonData(array $data = [])
    {
        $data = array_merge([
            'status'  => false,
            'code'    => 0,
            'message' => null,
            'data'    => null,
        ], $data);

        if (! $data['message']) {
            $data['message'] = $data['status'] === true ? '操作成功' : '操作失败';
        }

        return $data;
    }
}
