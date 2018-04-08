<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Blacklist;

class UserBlacklistController extends Controller
{
    /**
     * 加入黑名单
     * @Author   Wayne
     * @DateTime 2018-04-08
     * @Email    qiaobin@zhiyicx.com
     * @param    Request             $request
     * @param    User                $targetUser
     * @param    Blacklist           $blacklist
     * @return   [type]
     */
    public function black(Request $request, User $targetUser, Blacklist $blacklist)
    {
        $currentUser = $request->user();
        if ($blacklist->where('user_id', $currentUser->id)
            ->where('target_id', $targetUser->id)
            ->count()) {
            return response()->json(['message' => '已加入黑名单'], 201);
        }

        $blacklist->user_id = $currentUser->id;
        $blacklist->target_id = $targetUser->id;

        $blacklist->save();

        return response()->json(['message' => '已加入黑名单'], 201);
    }

    public function unBlack(Request $request, User $targetUser, Blacklist $blacklist)
    {
        $currentUser = $request->user();
        $target = $blacklist->where('user_id', $currentUser->id)
            ->where('target_id', $targetUser->id);
        if ($target->count() === 0) {
            return response()->json(['message' => '已移出黑名单'], 204);
        }

        $target->delete();

        return response()->json("", 204);
    }
}
