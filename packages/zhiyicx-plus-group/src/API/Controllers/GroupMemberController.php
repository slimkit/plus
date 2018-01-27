<?php

namespace Zhiyi\PlusGroup\API\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\PlusGroup\Models\Group as GroupModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Zhiyi\PlusGroup\Models\GroupMember as GroupMemberModel;
use Zhiyi\PlusGroup\Models\GroupIncome as GroupIncomeModel;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;
use Zhiyi\PlusGroup\Models\GroupMemberLog as GroupMemberLogModel;

class GroupMemberController
{
    /**
     * Get members of group.
     *
     * @param Request $request
     * @param GroupModel $group
     * @param GroupMemberModel $memberModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function index(Request $request, GroupModel $group)
    {
        $type = in_array($request->query('type'), ['all', 'manager', 'member', 'blacklist', 'audit', 'audit_user']) ? $request->query('type') : 'all';
        $limit = $request->query('limit', 15);
        $after = $request->query('after', 0);
        $name = $request->query('name');

        $users = $group->members()->when($type !== 'all', function ($query) use ($type) {
            switch ($type) {
                case 'manager':

                    return $query->where(function ($query) {
                        return $query->where('role', 'administrator');
                    })->where('disabled', 0)->where('audit', 1);
                    break;
                case 'member':

                    return $query->where('role', 'member')->where('disabled', 0)->where('audit', 1);
                    break;

                case 'blacklist':

                    return $query->where('disabled', 1)->where('audit', 1);
                    break;
                case 'audit_user':
                    return $query->where('disabled', 0)->where('audit', 1);
                    break;
                    
                case 'audit':

                    return $query->where('audit', 0);
                    break;
            }
        })
        ->when($name, function ($query) use ($name) {
            return $query->whereHas('user', function ($query) use ($name) {
                return $query->where('name', 'like', sprintf('%%%s%%', $name));
            });
        })
        ->when($after, function ($query) use ($after) {
            return $query->where('id', '<', $after);
        })
        ->has('user')
        ->limit($limit)
        ->orderBy('id', 'desc')
        ->with('user')
        ->get();

        return response()->json($users, 200);
    }

    /**
     * remove a member of group.
     *
     * @param Request $request
     * @param GroupModel $group
     * @param GroupMemberModel $member
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function remove(Request $request, GroupModel $group, GroupMemberModel $member)
    {
        $user = $request->user();
        
        if ($member->audit == 0) {
            
            return response()->json(['message' => ['待审核成员不能进行该操作']], 403);
        }

        if (! $member->canBeSet($user)) {
            
            return response()->json(['message' => ['权限不足']], 403);
        }

        $member->delete();

        return response()->json(['message' => ['操作成功']], 204);
    }

    /**
     * set a manager of group.
     *
     * @param Request $request
     * @param GroupModel $group
     * @param GroupMemberModel $member
     * @author BS <414606094@qq.com>
     */
    public function setManager(Request $request, GroupModel $group, GroupMemberModel $member)
    {
        $user = $request->user();

        if ($member->audit == 0) {
            
            return response()->json(['message' => ['待审核成员不能进行该操作']], 403);
        }

        if (! $member->canBeSetManager($user)) {
            
            return response()->json(['message' => ['权限不足']], 403);
        }

        if ($group->members()->where('role', 'administrator')->count() >= 5) {
            
            return response()->json(['message' => ['最多设置5个管理员']], 422);
        }

        $member->role = 'administrator';
        $member->save();

        return response()->json(['message' => ['操作成功']], 201);
    }

    /**
     * remove a manager of group.
     *
     * @param Request $request
     * @param GroupModel $group
     * @param GroupMemberModel $member
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function removeManager(Request $request, GroupModel $group, GroupMemberModel $member)
    {
        $user = $request->user();

        if ($member->audit == 0) {
            
            return response()->json(['message' => ['待审核成员不能进行该操作']], 403);
        }

        if ($member->role !== 'administrator') {
            return response()->json(['message' => ['操作错误']], 403);
        }

        if (! $member->canBeSetManager($user)) {
            
            return response()->json(['message' => ['权限不足']], 403);
        }

        $member->role = 'member';
        $member->save();

        return response()->json(['message' => ['操作成功']], 204);
    }

    /**
     * disable a member.
     *
     * @param Request $request
     * @param GroupModel $group
     * @param GroupMemberModel $member
     * @author BS <414606094@qq.com>
     */
    public function setBlacklist(Request $request, GroupModel $group, GroupMemberModel $member)
    {
        $user = $request->user();
        
        if ($member->audit == 0) {
            
            return response()->json(['message' => ['待审核成员不能进行该操作']], 403);
        }

        if (! $member->canBeSet($user)) {
            
            return response()->json(['message' => ['权限不足']], 403);
        }

        $member->disabled = 1;
        $member->save();

        return response()->json(['message' => ['操作成功']], 201);
    }

    /**
     * cancel disable a member.
     *
     * @param Request $request
     * @param GroupModel $group
     * @param GroupMemberModel $member
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function removeBlacklist(Request $request, GroupModel $group, GroupMemberModel $member)
    {
        $user = $request->user();

        if ($member->audit == 0) {
            
            return response()->json(['message' => ['待审核成员不能进行该操作']], 403);
        }

        if (! $member->canBeSet($user)) {
            
            return response()->json(['message' => ['权限不足']], 403);
        }

        $member->disabled = 0;
        $member->save();

        return response()->json(['message' => ['操作成功']], 204);
    }

    /**
     * 审核.
     *
     * @param Request $request
     * @param GroupModel $group
     * @param GroupMemberModel $member
     * @return mixed
     */
    public function audit(Request $request, GroupModel $group, GroupMemberModel $member, Carbon $datetime)
    {
        $user = $request->user();

        $status = (int) $request->input('status');

        if ($group->id !== $member->group_id) {
            return response()->json(['message' => ['圈子和成员不匹配']], 403);
        }

        if (! in_array($status, [1, 2])) {
            return response()->json(['message' => ['参数错误']], 422);
        }

        if (! $member->canBeSet($user)) {

            return response()->json(['message' => ['权限不足']], 403);
        }

        if (in_array($member->audit, [1,2])) {
            return response()->json(['message' => ['该成员已审核']], 403);
        }

        DB::beginTransaction();

        try {
            
            $caharge = new WalletChargeModel();

            if ($group->mode == 'paid') {
                if ($status === 1) {
                    // 流水记录
                    $caharge->user_id = $group->founder->user_id;
                    $caharge->channel = 'user';
                    $caharge->action = 1;
                    $caharge->amount = $group->money;
                    $caharge->subject = '用户加圈，审核通过';
                    $caharge->body = sprintf('用户%s申请加入《%s》圈子审核通过,圈主收益', $member->user->name, $group->name);
                    $caharge->status = 1;
                    $caharge->account = $member->user_id;
                    $caharge->save();

                    /// 保存圈内收入流水
                    $income = new GroupIncomeModel();
                    $income->subject = sprintf('"%s "加入圈子', $member->user->name);
                    $income->type = 1;
                    $income->amount = $group->money;
                    $income->user_id = $member->user_id;

                    $group->incomes()->save($income);

                    // 用户加钱
                    $group->founder->user->wallet()->increment('balance', $group->money);

                    // 发送通知
                    $message = sprintf("您申请加入的圈子%s已被审核通过", $group->name);
                    $member->user->sendNotifyMessage(
                        'group:join:accept',
                        $message,
                        ['group' => $group]
                    );
                } else {
                    // 流水
                    $caharge->user_id = $member->user_id;
                    $caharge->channel = 'user';
                    $caharge->action = 0;
                    $caharge->amount = $group->money;
                    $caharge->subject = '用户加圈，审核拒绝';
                    $caharge->body = sprintf('用户%s申请加入《%s》圈子审核拒绝,用户退款', $member->user->name, $group->name);
                    $caharge->status = 1;
                    $caharge->account = $user->id;
                    $caharge->save();
                    // 用户退款
                    $member->user->wallet()->increment('balance', $group->money);

                    // 发送通知
                    $message = sprintf("您申请加入的圈子%s已被管理员拒绝", $group->name);
                    $member->user->sendNotifyMessage(
                        'group:join:reject',
                        $message,
                        ['group' => $group]
                    );
                }
            }
            
            if ($status === 1) {
                // 增加成员数    
                $group->increment('users_count');

                // 保存成员状态
                $member->audit = $status;
                $member->save();
            } else {
                // 删除待审成员
                $member->delete();
            }

            // 保存成员审核记录
            if ($log = $member->logs()->where('status', 0)->where('auditer', null)->first()) {
                $log->status = $status;
                $log->auditer = $user->id;
                $log->audit_at = $datetime;
                $log->save();
            }

            DB::commit();
            return response()->json(['message' => ['审核成功']], 201);
        } catch (\Exception $exception) {
            DB::rollback();
            return response()->json(['message' => [$exception->getMessage()]], 500);
        }
    }

    /**
     * 用户所有圈子下面未审核的成员列表.
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function auditMembers(Request $request, GroupMemberLogModel $logModel)
    {
        $user = $request->user();

        $limit = $request->query('limit', 15);
        $after = $request->query('after', 0);
        $group_ids = $request->query('group_ids');

        if ($user->unreadCount !== null) {
            $user->unreadCount()->update(['unread_group_join_count' => 0]);
        }

        $groupIds =  GroupModel::select('id')->whereHas('members', function($query) use($user) {
            return $query->where('user_id', $user->id)->whereIn('role', ['founder', 'administrator']);
        })
        ->when($group_ids, function ($query) use ($group_ids) {
            return $query->whereIn('id', $group_ids);
        })
        ->where('audit', 1)
        ->get()
        ->pluck('id');

        $items = $logModel->whereIn('group_id', $groupIds)
        ->orderBy('id', 'desc')
        ->limit($limit)
        ->where('user_id', '!=', $user->id)
        ->with(['user', 'audit_user', 'member_info', 'group'])
        ->when($after, function ($query) use ($after) {
            return $query->where('id', '<', $after);
        })->get();

        return response()->json($items, 200);
    }

    /**
     * 转让圈主.
     *
     * @param Request $request
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function transferOwner(Request $request, GroupModel $group, UserModel $userModel)
    {
        $user = $request->user();
        if ($group->founder->user_id !== $user->id) {
            return response()->json(['message' => ['没有权限操作']], 403);
        }

        if ($group->members()->where('audit', 0)->first()) {
            return response()->json(['message' => ['圈子内还有待审核人员，不能转让']], 422);
        }

        $target_user_id = $request->input('target');
        $target_user = $userModel->find($target_user_id);
        $target = $group->members()->where('user_id', $target_user_id)->where('audit', 1)->where('disabled', 0)->first();
        if (! $target || ! $target_user) {
            return response()->json(['message' => ['被转让人必须是圈内成员']], 422);
        }

        // 记录新圈主
        $founder = $group->founder;
        $founder->user_id = $target_user_id;
        $founder->save();

        // 记录新成员
        $target->role = 'member';
        $target->user_id = $user->id;
        $target->save();

        $target_user->sendNotifyMessage('group:transfer', sprintf('%s已将圈子%s转让给你', $user->name, $group->name), [
            'user' => $user,
            'group' => $group
        ]);

        return response()->json(['message' => ['操作成功']], 201);
    }

    /**
     * 积分相关新版审核圈子成员接口.
     *
     * @param Request $request
     * @param GroupModel $group
     * @param GroupMemberModel $member
     * @param Carbon $datetime
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function newAudit(Request $request, GroupModel $group, GroupMemberModel $member, Carbon $datetime)
    {
        $user = $request->user();

        $status = (int) $request->input('status');

        if ($group->id !== $member->group_id) {
            return response()->json(['message' => ['圈子和成员不匹配']], 403);
        }

        if (! in_array($status, [1, 2])) {
            return response()->json(['message' => ['参数错误']], 422);
        }

        if (! $member->canBeSet($user)) {

            return response()->json(['message' => ['权限不足']], 403);
        }

        if (in_array($member->audit, [1,2])) {
            return response()->json(['message' => ['该成员已审核']], 403);
        }

        DB::beginTransaction();

        try {
            
            $caharge = new WalletChargeModel();

            if ($group->mode == 'paid') {
                if ($status === 1) {
                    $process = new UserProcess();
                    $process->receivables($group->founder->user_id, $group->money, $member->user_id, '用户加圈，审核通过', sprintf('用户%s申请加入《%s》圈子审核通过,圈主收益', $member->user->name, $group->name));

                    /// 保存圈内收入流水
                    $income = new GroupIncomeModel();
                    $income->subject = sprintf('"%s "加入圈子', $member->user->name);
                    $income->type = 1;
                    $income->amount = $group->money;
                    $income->user_id = $member->user_id;

                    $group->incomes()->save($income);

                    // 发送通知
                    $message = sprintf("您申请加入的圈子%s已被审核通过", $group->name);
                    $member->user->sendNotifyMessage(
                        'group:join:accept',
                        $message,
                        ['group' => $group]
                    );
                } else {
                    $process = new UserProcess();
                    $process->reject($group->founder->user_id, $group->money, $member->user_id, '用户加圈，审核拒绝', sprintf('用户%s申请加入《%s》圈子审核拒绝,用户退款', $member->user->name, $group->name));

                    // 发送通知
                    $message = sprintf("您申请加入的圈子%s已被管理员拒绝", $group->name);
                    $member->user->sendNotifyMessage(
                        'group:join:reject',
                        $message,
                        ['group' => $group]
                    );
                }
            }
            
            if ($status === 1) {
                // 增加成员数    
                $group->increment('users_count');

                // 保存成员状态
                $member->audit = $status;
                $member->save();
            } else {
                // 删除待审成员
                $member->delete();
            }

            // 保存成员审核记录
            if ($log = $member->logs()->where('status', 0)->where('auditer', null)->first()) {
                $log->status = $status;
                $log->auditer = $user->id;
                $log->audit_at = $datetime;
                $log->save();
            }

            DB::commit();
            return response()->json(['message' => ['审核成功']], 201);
        } catch (\Exception $exception) {
            DB::rollback();
            return response()->json(['message' => [$exception->getMessage()]], 500);
        }
    }
}
