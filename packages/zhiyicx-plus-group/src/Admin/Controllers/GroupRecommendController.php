<?php

namespace Zhiyi\PlusGroup\Admin\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\PlusGroup\Models\GroupRecommend as RecommendModel;

class GroupRecommendController
{

    /**
     * 列表.
     * 
     * @param  Request $request
     * @return miexed
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 15);
        $offset = $request->query('offset', 0);

        $user = $request->query('user');
        $group = $request->query('group');
        $category = (int) $request->query('category');
        $start = $request->query('start');
        $end = $request->query('end');

        $query = RecommendModel::with([
            'referrer', 
            'group.founder.user', 
            'group.category'
        ])
        ->has('group');

        $query = $query->when($group, function ($query) use ($group) {
            return $query->whereHas('group', function ($query) use ($group) {
                return $query->where('name', 'like', sprintf('%%%s%%', $group));
            });
        })
        ->when($start || $end, function ($query) use ($start, $end) {
            if ($start) {
                $query->where('created_at', '>=', Carbon::parse($start)->startOfDay());
            }
            if ($end) {   
                $query->where('created_at', '<', Carbon::parse($end)->endOfDay());
            }
            return $query;
        })
        ->when($category, function ($query) use ($category) {
            return $query->whereHas('group', function ($query) use ($category) {
                return $query->where('category_id', $category);
            });
        })
        ->when($user, function ($query) use ($user) {
            return $query->whereHas('group.founder.user', function ($query) use ($user) {
                return $query->where('name', 'like', sprintf('%%%s%%', $user));
            });
        });

        $count = $query->count();
        $items = $query->limit($limit)
        ->offset($offset)
        ->orderBy('sort_by', 'desc')
        ->get();

        return response()->json($items, 200);
    }

    /**
     * 推荐排序.
     * 
     * @param  Request        $request
     * @param  RecommendModel $recommend
     * @return miexed              
     */
    public function sort(Request $request, RecommendModel $recommend)
    {
        $sort = (int) $request->input('sort');

        $recommend->sort_by = $sort;
        $recommend->save();

        return response()->json($recommend, 201);
    }

    /**
     * 移除推荐.
     * 
     * @param  RecommendModel $recommend
     * @return mixed
     */
    public function remove(RecommendModel $recommend)
    {
        $recommend->delete();

        return response()->json(null, 204);
    }
}
