<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Services;

use Zhiyi\Plus\Models\UserDatas;

class FeedCount
{
    // allowed method
    protected $method = ['increment', 'decrement'];

    // allowed key
    protected $countKey = ['diggs_count', 'feeds_count'];

    protected function checkMethod($method)
    {
        if (in_array($method, $this->method)) {
            return true;
        }

        return false;
    }

    protected function checkKey($countKey)
    {
        if (in_array($countKey, $this->countKey)) {
            return true;
        }

        return false;
    }

    protected function checkEmptyData($uid, $countKey)
    {
        $notEmpty = ! (UserDatas::byKey($countKey)->byUserId($uid)->first());
        if ($notEmpty) {
            $countModel = new UserDatas();
            $countModel->key = $countKey;
            $countModel->user_id = $uid;
            $countModel->value = 0;
            $countModel->save();
        }
    }

    /**
     * 统计数量.
     *
     * @author bs<414606094@qq.com>
     * @param  [type] $uid      [description]
     * @param  [type] $countKey [description]
     * @param  string $method   [description]
     * @return [type]           [description]
     */
    public function count($uid, $countKey, $method = 'increment', $countnum = 1)
    {
        if ($this->checkKey($countKey) && $this->checkMethod($method)) {
            $this->checkEmptyData($uid, $countKey);

            return tap(UserDatas::where('key', $countKey)->byUserId($uid), function ($query) use ($method, $countnum) {
                $query->$method('value', $countnum);
            });
        }

        return false;
    }
}
