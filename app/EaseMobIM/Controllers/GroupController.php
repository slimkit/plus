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

namespace Zhiyi\Plus\EaseMobIm;

use GuzzleHttp\Client;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Cdn\UrlManager;
use Zhiyi\Plus\Models\ImGroup;
use Zhiyi\Plus\Models\FileWith;

class GroupController extends EaseMobController
{
    /**
     * 创建群组.
     *
     * @param CheckGroup $request
     * @param ImGroup $imGroup
     * @author ZsyD<1251992018@qq.com>
     * @return $this
     */
    public function store(CheckGroup $request, ImGroup $imGroup)
    {
        $callback = function () use ($request, $imGroup) {
            $options['groupname'] = $request->input('groupname');
            $options['desc'] = $request->input('desc');
            $options['public'] = (bool) $request->input('public', 1);
            $options['maxusers'] = $request->input('maxusers', 300);
            $options['members_only'] = (bool) $request->input('members_only', 0);
            $options['allowinvites'] = (bool) $request->input('allowinvites', 1);
            $options['owner'] = (string) $request->user()->id;
            $request->input('members') && $options['members'] = explode(',', $request->input('members'));

            $url = $this->url.'chatgroups';
            $data['headers'] = [
                'Authorization' => $this->getToken(),
            ];
            $data['body'] = json_encode($options);
            $data['http_errors'] = false;
            $Client = new Client();
            $result = $Client->request('post', $url, $data);

            if ($result->getStatusCode() != 200) {
                $error = $result->getBody()->getContents();

                return response()->json([
                    'message' => [
                        json_decode($error)->error_description,
                    ],
                ])->setStatusCode(500);
            }
            $res = json_decode($result->getBody()->getContents());

            $imGroup->im_group_id = $res->data->groupid;
            $imGroup->user_id = $request->user()->id;
            $imGroup->type = $request->input('type', 0);
            $imGroup->save();

            // 发送消息至群组
            $cmd_content = $request->user()->name.'创建了群聊！';
            $ext = [
                'type' => 'ts_group_create',
                'group_id' => $imGroup->im_group_id,
            ];
            $isCmd = $this->sendCmd($cmd_content, [$imGroup->im_group_id], 'admin', 'chatgroups', $ext);

            if (! $isCmd) {
                return response()->json([
                    'message' => ['消息发送失败'],
                    'im_group_id' => $imGroup->im_group_id,
                ])->setStatusCode(202);
            }

            return response()->json([
                'message' => ['成功'],
                'im_group_id' => $imGroup->im_group_id,
            ])->setStatusCode(201);
        };

        return $this->getConfig($callback);
    }

    /**
     * 修改群信息.
     *
     * @param UpdateGroup $request
     * @param UrlManager $urlManager
     * @return $this
     * @author ZsyD<1251992018@qq.com>
     */
    public function update(UpdateGroup $request, UrlManager $urlManager)
    {
        $callback = function () use ($request, $urlManager) {
            $im_group_id = $request->input('im_group_id');
            $options['groupname'] = $request->input('groupname');
            $options['desc'] = $request->input('desc');
            $options['public'] = (bool) $request->input('public', 1);
            $options['maxusers'] = $request->input('maxusers', 300);
            $options['members_only'] = (bool) $request->input('members_only', 0);
            $options['allowinvites'] = (bool) $request->input('allowinvites', 1);
            $request->input('new_owner_user') > 0 && $options['newowner'] = $request->input('new_owner_user');

            $url = $this->url.'chatgroups/'.$im_group_id;
            $data['headers'] = [
                'Authorization' => $this->getToken(),
            ];
            $data['body'] = json_encode($options);
            $data['http_errors'] = false;
            $Client = new Client();
            $result = $Client->request('put', $url, $data);

            if ($result->getStatusCode() != 200) {
                $error = $result->getBody()->getContents();

                return response()->json([
                    'message' => [
                        json_decode($error)->error_description,
                    ],
                ])->setStatusCode(500);
            }
            $options['group_face'] = '';
            $imGroup = ImGroup::where('im_group_id', $im_group_id)->first();
            $imGroup->type = $request->input('type', 0);
            $request->input('new_owner_user') > 0 && $imGroup->user_id = $options['newowner'];
            if ($request->input('group_face', 0) > 0) {
                $imGroup->group_face = $request->input('group_face', 0);
                // 创建头像
                $fileWith = FileWith::where('id', $imGroup->group_face)
                    ->where('channel', null)
                    ->where('raw', null)
                    ->first();

                // 保存群头像
                if ($fileWith) {
                    $fileWith->channel = 'im:group_face';
                    $fileWith->raw = $imGroup->id;
                    $fileWith->save();

                    $options['group_face'] = $urlManager->make($fileWith->file);
                }
            }
            if (! $imGroup->save()) {
                return response()->json([
                    'message' => ['修改失败'],
                    'group_id' => $im_group_id,
                ])->setStatusCode(500);
            }

            // 发送消息至群组
            if (isset($options['newowner'])) {
                $newowner = User::where('id', $options['newowner'])->value('name');
                $cmd_content = $newowner.'已成为新群主！';
            } else {
                $cmd_content = $request->user()->name.'修改了群信息！';
            }
            $ext = [
                'type' => 'ts_group_change',
                'group_id' => $im_group_id,
            ];
            $isCmd = $this->sendCmd($cmd_content, [$im_group_id], 'admin', 'chatgroups', $ext);

            if (! $isCmd) {
                return response()->json([
                    'message' => ['消息发送失败'],
                    'im_group_id' => $im_group_id,
                ])->setStatusCode(202);
            }

            $options['im_group_id'] = $im_group_id;

            return response()->json($options)->setStatusCode(201);
        };

        return $this->getConfig($callback);
    }

    /**
     * 删除群组.
     *
     * @param Request $request
     * @param ImGroup $imGroup
     * @return $this
     * @author ZsyD<1251992018@qq.com>
     */
    public function delete(Request $request, ImGroup $imGroup)
    {
        $callback = function () use ($request, $imGroup) {
            $im_group_id = $request->query('im_group_id');
            $group = $imGroup->where('user_id', $request->user()->id)
                ->where('im_group_id', $im_group_id);

            if (! $group->first()) {
                return response()->json([
                    'message' => ['该群组不存在或已被删除'],
                ])->setStatusCode(400);
            }

            $url = $this->url.'chatgroups/'.$im_group_id;
            $data['headers'] = [
                'Authorization' => $this->getToken(),
            ];
            $data['http_errors'] = false;
            $Client = new Client();
            $result = $Client->request('delete', $url, $data);

            if ($result->getStatusCode() != 200) {
                $error = $result->getBody()->getContents();

                return response()->json([
                    'message' => [
                        json_decode($error)->error_description,
                    ],
                ])->setStatusCode(500);
            }

            $group->delete();

            return response()->json([
                'message' => ['成功'],
            ])->setStatusCode(204);
        };

        return $this->getConfig($callback);
    }

    /**
     * 获取群信息.
     *
     * @param Request $request
     * @param UrlManager $urlManager
     * @return $this
     * @author ZsyD<1251992018@qq.com>
     */
    public function newGetGroup(Request $request, UrlManager $urlManager)
    {
        $callback = function () use ($request, $urlManager) {
            $im_group_id = $request->query('im_group_id'); // 多个以“,”隔开
            $url = $this->url.'chatgroups/'.$im_group_id;
            $data['headers'] = [
                'Authorization' => $this->getToken(),
            ];
            $data['http_errors'] = false;
            $Client = new Client();
            $result = $Client->request('get', $url, $data);
            $groupCon = json_decode($result->getBody()->getContents());

            if ($result->getStatusCode() != 200) {
                return response()->json([
                    'message' => [
                        $groupCon->error_description,
                    ],
                ])->setStatusCode(500);
            }
            // 获取群组头像
            $group_face = ImGroup::with('face')->whereIn('im_group_id', collect($groupCon->data)->pluck('id'))
                ->select('group_face', 'im_group_id')->get()->keyBy('im_group_id');

            foreach ($groupCon->data as $group) {
                // $affiliations = collect($group->affiliations);
                // $owner = $affiliations->pluck('owner')->filter();
                // $members = $affiliations->pluck('member')->filter();
                // $group->affiliations = $this->getUser($members, $owner);
                $group->group_face = (isset($group_face[$group->id]) && $group_face[$group->id]->face) ? $urlManager->make($group_face[$group->id]->face->file) : '';
            }

            return response()->json($groupCon->data)->setStatusCode(200);
        };

        return $this->getConfig($callback);
    }

    public function getGroup(Request $request, UrlManager $urlManager)
    {
        $callback = function () use ($request, $urlManager) {
            $im_group_id = $request->query('im_group_id'); // 多个以“,”隔开
            $url = $this->url.'chatgroups/'.$im_group_id;
            $data['headers'] = [
                'Authorization' => $this->getToken(),
            ];
            $data['http_errors'] = false;
            $Client = new Client();
            $result = $Client->request('get', $url, $data);
            $groupCon = json_decode($result->getBody()->getContents());

            if ($result->getStatusCode() != 200) {
                return response()->json([
                    'message' => [
                        $groupCon->error_description,
                    ],
                ])->setStatusCode(500);
            }
            // 获取群组头像
            $group_face = ImGroup::with('face')->whereIn('im_group_id', collect($groupCon->data)->pluck('id'))
                ->select('group_face', 'im_group_id')->get()->keyBy('im_group_id');

            foreach ($groupCon->data as &$group) {
                $affiliations = collect($group->affiliations);
                $owner = $affiliations->pluck('owner')->filter();
                $members = $affiliations->pluck('member')->filter();
                $group->affiliations = $this->getUser($members, $owner);
                $group->group_face = (isset($group_face[$group->id]) && $group_face[$group->id]->face) ? $urlManager->make($group_face[$group->id]->face->file) : '';
            }

            return response()->json($groupCon->data)->setStatusCode(200);
        };

        return $this->getConfig($callback);
    }

    /**
     * 获取群头像(多个用","隔开).
     *
     * @param GroupId $request
     * @return \Illuminate\Http\JsonResponse
     * @author ZsyD<1251992018@qq.com>
     */
    public function getGroupFace(GroupId $request)
    {
        $groups = $request->input('im_group_id');
        $groups = ! is_array($groups) ? explode(',', $groups) : $groups;
        $datas = ImGroup::whereIn('im_group_id', $groups)
            ->select('im_group_id', 'group_face')
            ->get();

        return response()->json($datas, 200);
    }

    /**
     * 获取群组用户信息列表.
     *
     * @param $members
     * @param $owner
     * @return mixed
     * @author ZsyD<1251992018@qq.com>
     */
    private function getUser($members, $owner)
    {
        $user = new User();
        $users = $user->whereIn('id', $owner->merge($members))->with('certification')->get();
        $admin = $owner->values()[0];
        if ($users) {
            $users->map(function ($user) use ($admin) {
                $user->is_owner = $user->id == $admin ? 1 : 0;

                return $user;
            });
        }

        return $users->sortByDesc('is_owner')->values();
    }

    /**
     * 添加群成员(多个用","隔开).
     *
     * @param GroupMember $request
     * @return $this
     * @author ZsyD<1251992018@qq.com>
     */
    public function addGroupMembers(GroupMember $request)
    {
        $callback = function () use ($request) {
            $im_group_id = $request->input('im_group_id');
            $option['usernames'] = $request->input('members');
            $option['usernames'] = is_array($option['usernames']) ? $option['usernames'] : explode(',', $option['usernames']);

            // 查询用户昵称
            $users = User::whereIn('id', $option['usernames'])->pluck('name');
            $names = '';
            if ($users) {
                $names = implode('、', $users->toArray());
            }
            $url = $this->url.'chatgroups/'.$im_group_id.'/users';
            $data['body'] = json_encode($option);
            $data['headers'] = [
                'Authorization' => $this->getToken(),
            ];
            $data['http_errors'] = false;
            $Client = new Client();
            $result = $Client->request('post', $url, $data);
            $groupCon = json_decode($result->getBody()->getContents());

            if ($result->getStatusCode() != 200) {
                return response()->json([
                    'message' => [
                        $groupCon->error_description,
                    ],
                ])->setStatusCode(500);
            }
            // 发送消息至群组
            $cmd_content = $request->user()->name.'邀请了'.$names.'加入了群聊。';
            $ext = [
                'type' => 'ts_user_join',
                'uid' => $option['usernames'],
            ];
            $isCmd = $this->sendCmd($cmd_content, [$im_group_id], 'admin', 'chatgroups', $ext);

            if (! $isCmd) {
                return response()->json([
                    'message' => ['消息发送失败'],
                    'im_group_id' => $im_group_id,
                ])->setStatusCode(202);
            }

            return response()->json([])->setStatusCode(201);
        };

        return $this->getConfig($callback);
    }

    /**
     * 移除群成员(多个用","隔开).
     *
     * @param GroupMember $request
     * @return $this
     * @author ZsyD<1251992018@qq.com>
     */
    public function removeGroupMembers(GroupMember $request)
    {
        $callback = function () use ($request) {
            $im_group_id = $request->input('im_group_id');
            $members = $request->input('members');
            $members = is_array($members) ? implode(',', $members) : $members;
            // 查询用户昵称
            $users = User::whereIn('id', explode(',', $members))->pluck('name');
            $names = '';
            if ($users) {
                $names = implode('、', $users->toArray());
            }

            $url = $this->url.'chatgroups/'.$im_group_id.'/users/'.$members;
            $data['headers'] = [
                'Authorization' => $this->getToken(),
            ];
            $data['http_errors'] = false;
            $Client = new Client();
            $result = $Client->request('delete', $url, $data);
            $groupCon = json_decode($result->getBody()->getContents());

            if ($result->getStatusCode() != 200) {
                return response()->json([
                    'message' => [
                        $groupCon->error_description,
                    ],
                ])->setStatusCode(500);
            }
            // 发送消息至群组
            $cmd_content = $request->user()->name.'已将'.$names.'移出群聊。';
            $ext = [
                'type' => 'ts_user_exit',
                'uid' => $members,
            ];
            $isCmd = $this->sendCmd($cmd_content, [$im_group_id], 'admin', 'chatgroups', $ext);

            if (! $isCmd) {
                return response()->json([
                    'message' => ['消息发送失败'],
                    'im_group_id' => $im_group_id,
                ])->setStatusCode(202);
            }

            return response()->json([])->setStatusCode(204);
        };

        return $this->getConfig($callback);
    }
}
