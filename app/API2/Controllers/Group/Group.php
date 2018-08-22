<?php

declare(strict_types=1);

namespace Zhiyi\Plus\API2\Controllers\Group;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zhiyi\Plus\API2\Controllers\Controller;
use Zhiyi\PlusGroup\Models\Group as GroupModel;
use Zhiyi\PlusGroup\Models\GroupMember as GroupMemberModel;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Group extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function bindImGroupId(Request $request, GroupMemberModel $memberModel, GroupModel $group): Response
    {
        $request->validate(['id' => 'required|string'], ['id.required' => '必须传输群聊 ID']);
        $user = $request->user();
        $canOperation = $memberModel
            ->query()
            ->where('group_id', $group->id)
            ->where('user_id', $user->id)
            ->where(function ($query) {
                return $query
                    ->where('role', 'founder')
                    ->orWhere('role', 'administrator');
            })
            ->where('disabled', 0)
            ->exists();
        if (! $canOperation) {
            throw new AccessDeniedHttpException('你无权进行操作');
        }
        
        $group->im_group_id = $request->input('id');
        $group->save();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
