<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\Comment as CommentModel;

class ReportController extends Controller
{
    public function user(Request $request, UserModel $user)
    {
        return 'user';
    }

    public function comment(Request $request, CommentModel $comment)
    {
        return 'comment';
    }
}