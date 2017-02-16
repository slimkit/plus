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
    	Following::where([
    			['user_id', $user_id],
    			['following_user_id', $follow_user_id]
    		])
    		->delete();
    	Followed::where([
    		['user_id', $follow_user_id],
    		['followed_user_id', $user_id]
    	])
    	->delete();

    	return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '成功取关'
        ]))->setStatusCode(201);
    }

    /**
     * 查询关注我的用户
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function follows(Request $request)
    {

    }
    /**
     * [followeds description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function followeds(Request $request)
    {

    }
}
