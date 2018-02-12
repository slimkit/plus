<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

return [
    'notifications' => [
        'invited' => '你邀请:user已回答了你的问题',
        'answer' => '你的问题被:user回答',
    ],
    'charges' => [
        'invited' => [
            'subject' => '回答问答问题邀请获得悬赏',
            'body' => '回答问题《:body》',
        ],
    ],
    'reward' => [
        'attributes' => [
            'amount' => '金额',
        ],
        'required' => '请输入打赏:attribute',
        'min' => '你输入的:attribute非法',
        'max' => '余额不足',
        'not-user' => '打赏对象不存在，无法进行打赏',
        'send-reward' => '打赏问答答案。',
        'get-reward' => '问答回答被打赏',
    ],
    'like' => [
        'liked' => '已点赞该回答',
        'not-liked' => '未点赞该回答',
    ],
    'collect' => [
        'collected' => '已收藏该回答',
        'not-collected' => '未收藏该回答',
    ],
    'not-own' => '你没有权限操作该问题',
    'adopted' => '该回答已被采纳，无法进行操作',
    'delete' => [
        'invited' => '邀请回答无法被删除',
    ],
];
