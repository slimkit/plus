<?php

namespace Zhiyi\PlusGroup\Admin\Controllers;

use Illuminate\Http\Request;
use Zhiyi\PlusGroup\Models\Group as  GroupModel;
use Zhiyi\PlusGroup\Models\GroupMember as MemberModel;

class GroupMemberController
{
    public function members(Request $request, GroupModel $group)
    {
        $role = $request->query('role');
        $user = $request->query('user');
        $audit = $request->query('audit');
        $disable = $request->query('disable');

        $limit = (int) $request->query('limit', 15);
        $offset = (int) $request->query('offset', 0);

        $query = $group->members()
        ->when(! is_null($audit), function($query) use($audit) {
            return $query->where('audit', $audit);
        })
        ->when($role, function($query) use($role) {
            return $query->where('role', $role);
        })
        ->when(! is_null($disable), function($query) use($disable) {
            return $query->where('disabled', $disable);
        })
        ->when($user, function($query) use($user) {
            return $query->whereHas('user', function($query) use ($user) {
                return $query->where('name', 'like', sprintf('%%%s%%', $user));
            });
        });

        $items = $query->with(['user', 'group'])
        ->limit($limit)
        ->offset($offset)
        ->get();

        return response()->json($items, 200, ['x-toal' => $query->count()]);
    }

    /**
     * 设置圈子角色.
     * 
     * @param  Request     $request
     * @param  GroupMember $member
     * @return mixed
     */
    public function role(Request $request, MemberModel $member)
    {
        $role = $request->input('role');

        if (! $role || !in_array($role, ['member', 'founder', 'administrator'])) {
            return response()->json(['mssage' => '错误的参数'], 422);
        }

        if ($member->disabled == 1) {
            return response()->json(['message' => '成员已被拉黑,不能设置职位'], 422);
        }

        if ($role == 'administrator'
         && MemberModel::where('group_id', $member->group_id)->where('role', 'administrator')->count() >= 5) {
            return response()->json(['message' => '最多只能设置5个管理员'], 403);
        }

        $user = $member->user;
        $group = $member->group;

        $memberModel = MemberModel::where('group_id', $member->group_id)
        ->where('role', $role)
        ->first();

        // 判断该成员是否已是该职位
        if ($memberModel && $memberModel->id == $member->id) {
            return response()->json(['mssage' => '你已被设置成该职位'], 403);
        }

        // 如果设置成员和管理员为圈主
        if ($role == 'founder') {
            $founder = $group->founder;
            $founder->role = 'member';
            $founder->save();

            $founder->user->sendNotifyMessage(
                'group:member:role', 
                sprintf('系统管理员将你设置成"%s"圈子的成员', $group->name), 
                ['user' => $founder->user, 'group' => $group]
            );
        }

        $member->role = $role;
        $member->save();

        // 发送系统通知
        $user->sendNotifyMessage(
            'group:member:role', 
            sprintf('系统管理员将你设置成"%s"圈子的%s', $group->name, $this->getNameByRole($role)), 
            ['user' => $user, 'group' => $group]
        );

        return response()->json(['message' => '设置成功', 'member' => $member], 201);
    }

    /**
     * 通过角色获取角色名.
     * 
     * @param  string $role
     * @return string
     */
    public function getNameByRole(string $role)
    {
        switch ($role) {
            case 'administrator':
                return '管理员';
                break;
            case 'founder':
                return '圈主';
                break;
            default:
                return '成员';
                break;
        }
    }

    /**
     * 踢出圈子.
     * 
     * @param  MemberModel $member
     * @return mixed
     */
    public function delete(MemberModel $member)
    {
        $user = $member->user;
        $group = $member->group;

        if ($member->role == 'founder') {
            return response()->json(['mssage' => '圈主不能被踢出'], 403);
        }
        
        $member->group()->decrement('users_count');

        $user->sendNotifyMessage(
            'group:member:remove', 
            sprintf('你已被系统管理员移除“%s”圈子', $group->name), 
            ['user' => $user, 'group' => $group]
        );

        $member->delete();

        return response()->json(null, 204);
    }

    /**
     * 拉黑.
     * 
     * @param  MemberModel $member
     * @return mixed
     */
    public function disable(Request $request, MemberModel $member)
    {
        $disable = $request->input('disable');

        if (! in_array($disable, [0, 1])) {
            return response()->json(['mssage' => '参数错误'], 422);
        }
        if ($member->role == 'founder') {
            return response()->json(['mssage' => '圈主不能被拉黑'], 403);
        }

        $member->disabled = $disable;
        $member->save();

        $user = $member->user;
        $group = $member->group;

        $user->sendNotifyMessage(
            'group:member:blacklist', 
            sprintf('你已被系统管理员%s“%s”圈子黑名单', ($disable ? '加入' : '移除'), $group->name), 
            ['user' => $user, 'group' => $group]
        );

        return response()->json(['message' => $disable ? '加入黑名单成功' : '解除黑名单成功'], 201);
    }


}
