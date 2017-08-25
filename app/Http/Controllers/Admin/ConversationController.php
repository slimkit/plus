<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Conversation;
use Zhiyi\Plus\Http\Controllers\Controller;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('perPage', 20);
        $type = $request->get('type');

        $conversations = Conversation::with('user')
            ->orderBy('id', 'desc')
            ->when(! is_null($type), function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->paginate($perPage);

        return response()->json($conversations, 200);
    }
}
