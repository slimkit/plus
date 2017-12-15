<?php

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
