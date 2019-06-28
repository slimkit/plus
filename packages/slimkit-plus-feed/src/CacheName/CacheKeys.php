<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\CacheName;

class CacheKeys
{
    /* 节点支付缓存KEY */
    const PAID = 'paid:%s,%s';
    /* 动态点赞 */
    const LIKED = 'feed-like:%s,%s';
    /* 动态收藏 */
    const COLLECTED = 'feed-collected:%s,%s';
}
