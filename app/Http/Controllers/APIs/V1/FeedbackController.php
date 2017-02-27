<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V1;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Feedback;
use Zhiyi\Plus\Models\User;

class FeedbackController extends Controller
{
    public function createFeedback(Request $request)
    {
        $feedback = new Feedback();
        $feedback->content = $request->input('content');
        $feedback->user_id = $request->user()->id;
        $feedback->save();

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '反馈成功',
        ]))->setStatusCode(201);
    }
}
