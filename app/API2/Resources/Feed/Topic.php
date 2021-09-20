<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\API2\Resources\Feed;

use Illuminate\Http\Resources\Json\JsonResource;

class Topic extends JsonResource
{
    /**
     * The topic resource to array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $user = $request->user('api');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => $this->when($this->logo, function () {
                return $this->logo->toArray();
            }),
            'desc' => $this->when($this->desc, $this->desc),
            'creator_user_id' => $this->creator_user_id,
            'feeds_count' => $this->feeds_count,
            'followers_count' => $this->followers_count,
            'has_followed' => $this->when($user, function () use ($user) {
                $link = $this->users->firstWhere('id', $user->id)->pivot ?? null;
                if ($link && $link->following_at) {
                    return true;
                }

                return false;
            }),
            'participants' => $this->when($this->participants, $this->participants),
        ];
    }
}
