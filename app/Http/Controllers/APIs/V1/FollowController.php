<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V1;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\Following;
use Zhiyi\Plus\Models\Followed;

class FollowController extends Controller
{

	public function __construct(Request $request)
	{
		$user = User::find($request->user_id);
        if (!$user or !$user instanceof User) {
            return response()->json(static::createJsonData([
                'status'  => false,
                'code'    => 1005,
            ]))->setStatusCode(404);
        }
	}
	/**
	 * 关注用户
	 * @param  $user_id [被关注者ID]
	 * @return [type]           [description]
	 */
    public function doFollow(Request $request)
    {
    	$user_id = $request->user()->id;
    	$follow_user_id = $request->user_id;
    	Following::create([
    		'user_id' => $user_id,
    		'following_user_id' => $follow_user_id
    	]);
    	Followed::create([
    		'user_id' => $follow_user_id,
    		'followed_user_id' => $user_id
    	]);

    	return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '关注成功'
        ]))->setStatusCode(201);
    }

    /**
     * 取消关注
     * @param  [integer] $user_id [被取消关注的用户ID]
     * @return [type]          [description]
     */
    public function doUnFollow(Request $request)
    {
    	$user_id = $request->user()->id;
    	$follow_user_id = $request->user_id;

    	// 我关注的
    	Following::where([
    			['user_id', $user_id],
    			['following_user_id', $follow_user_id]
    		])
    		->delete();

    	// 目标用户我的粉丝
    	Followed::where([
    		['user_id', $follow_user_id],
    		['followed_user_id', $user_id]
    	])
    	->delete();

    	return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '成功取关'
        ]))->setStatusCode(200);
    }

    /**
     * 关注的用户
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function follows(int $user_id, int $max_id = 0)
    {
    	if(!User::find($user_id)) {
    		return response()->json(static::createJsonData([
	            'status'  => false,
	            'code'    => 1023,
	            'message' => '用户未找到'
	        ]))->setStatusCode(404);
    	}

    	$data['follows'] = Following::where('user_id', $user_id)
    		->where(function ($query) use ($max_id) {
    			if($max_id > 0) {
    				$query->where('id', '<', $max_id);
    			}
    		})
    		->select('id', 'following_user_id as user_id')
    		->orderBy('id', 'DESC')
    		->take(15)
    		->get();
    	return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data' => $data
        ]))->setStatusCode(200);
    }
    /**
     * 查询粉丝
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function followeds(int $user_id, int $max_id = 0)
    {
    	if(!User::find($user_id)) {
    		return response()->json(static::createJsonData([
	            'status'  => false,
	            'code'    => 1023,
	            'message' => '用户未找到'
	        ]))->setStatusCode(404);
    	}
    	$data['followeds'] = Followed::where('user_id', $user_id)
    		->where(function ($query) use ($max_id) {
    			if($max_id > 0) {
    				$query->where('id', '<', $max_id);
    			}
    		})
    		->select('id', 'followed_user_id as user_id')
    		->orderBy('id', 'DESC')
    		->take(15)
    		->get();

    	return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data' => $data
        ]))->setStatusCode(200);	
    }
}
