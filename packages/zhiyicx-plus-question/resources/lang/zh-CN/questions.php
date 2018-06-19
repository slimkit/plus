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

    'attributes' => [
        'subject' => '问题',
        'topics' => '话题',
        'anonymity' => '匿名',
        'look' => '围观',
        'user' => '用户',
        'body' => '详情',
        'amount' => '悬赏金额',
    ],

    'invitation' => ':user 邀请你回答问题《:question》',

    'Attribute must end with a question mark' => '问题必须以问号结尾',
    'Insufficient balance' => '余额不足',
    'Can not invite yourself' => '不能邀请自己',
    '设置回答自动入账必须设置悬赏总额' => '设置回答自动入账必须设置悬赏总额',
    '回答自动入账必须设置悬赏总额' => '悬赏积分必须大于0',
    '发布悬赏问答' => '发布悬赏问答',
    '发布悬赏问答《%s》' => '发布悬赏问答《:subject》',
    '开启围观必须设置悬赏金额' => '开启围观必须设置悬赏金额',
    '开启围观必须设置自动入账' => '开启围观必须设置自动入账',
    '该问题无法设置悬赏' => '该问题无法设置悬赏',

    'not-owner' => '你没有权限进行该操作',
    'refund' => '退还悬赏金额',

    'adoption' => [
        'not-owner' => '你没有权限操作',
        'own-answer' => '无法采纳自己的回答',
        'already' => '该回答已经是采纳或者问题已有采纳',
        'non' => '回答不属于这个问题',
        'charge-subject' => '问题回答被采纳获得悬赏',
        'notify-message' => '你提交的问题回答被采纳',
    ],

    'application' => [
        'already-exists' => '已有正在审核的申请',
        '支付问题申精费用' => '支付问题申精费用',
        '支付问题《:subject》的申精费用' => '支付问题《:subject》的申精费用',
        '退还问题申精费用' => '退还问题申精费用',
        '退还问题《:subject》的申精费用' => '退还问题《:subject》的申精费用',
    ],

    'onlookers' => [
        'unnecessary' => '无需支付围观费用',
        'already-exists' => '已支付围观费用',
        '支付问题围观答案费用' => '支付问题围观答案费用',
        '支付问题《:subject》的围观答案费用' => '支付问题《:subject》的围观答案费用',
        '收到问题围观答案费用' => '收到问题围观答案费用',
        '收到问题《:subject》的围观答案费用' => '收到问题《:subject》的围观答案费用',
    ],
];
