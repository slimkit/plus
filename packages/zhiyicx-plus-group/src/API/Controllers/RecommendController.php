<?php

namespace Zhiyi\PlusGroup\API\Controllers;

use Illuminate\Http\Request;
use Zhiyi\PlusGroup\Models\GroupMember as GroupMemberModel;
use Zhiyi\PlusGroup\Models\GroupRecommend as GroupRecommendModel;

class RecommendController
{
    /**
     * recommend groups.
     *
     * @param Request $request
     * @param GroupRecommend $recommendModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function groups(Request $request, GroupRecommendModel $recommendModel)
    {
        $user_id = $request->user('api')->id ?? 0;

        $limit = (int) $request->query('limit', 15);
        $offset = (int) $request->query('offset', 0);
        $type = $request->query('type');

        $recommends = $recommendModel->where('disable', 0)->with(['group' => function ($query) {
                return $query->where('audit', 1);
            }])
        ->has('group')
        ->limit($limit)
        ->when($type == 'random', function ($query) {
            return $query->inRandomOrder();
        })
        ->offset($offset)
        ->orderBy('sort_by', 'asc')
        ->get();

        $joined = GroupMemberModel::whereIn('group_id', $recommends->map->group->map->id)
            ->where('user_id', $user_id)
            ->get();

        $groups = $recommends->map(function ($recommend) use ($joined) {
            $group = $recommend->group;
            $group->joined = null;
            $joined->each(function (GroupMemberModel $member) use ($group) {
                if ($member->group_id === $group->id) {
                    $group->joined = $member;

                    return false;
                }
            });

            return $group;
        });

        return response()->json($groups, 200);
    }
}
