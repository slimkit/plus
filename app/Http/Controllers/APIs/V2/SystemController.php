<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Conversation;

class SystemController extends Controller
{
    /**
     * create a feedback.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @return mixed
     */
    public function createFeedback(Request $request, Conversation $feedback, Carbon $datetime)
    {
        $feedback->type = 'feedback';
        $feedback->content = $request->input('content');
        $feedback->user_id = $request->user()->id;
        $feedback->system_mark = $request->input('system_mark', ($datetime->timestamp) * 1000);
        $feedback->save();

        return response()->json([
            'message' => ['反馈成功'],
            'data' => $feedback,
        ])->setStatusCode(201);
    }

    /**
     * about us.
     *
     * @author bs<414606094@qq.com>
     * @return html
     */
    public function about()
    {
        return view('about');
    }
}
