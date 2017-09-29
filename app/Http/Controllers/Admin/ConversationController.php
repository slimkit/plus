<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Conversation;
use Zhiyi\Plus\Http\Controllers\Controller;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        $limit = (int) $request->get('limit', 15);
        $offset = (int) $request->get('offset', 0);

        $query = Conversation::with('user')
            ->orderBy('id', 'desc')
            ->when(! is_null($type), function ($query) use ($type) {
                $query->where('type', $type);
            });

        $total = $query->count('id');
        $items = $query->limit($limit)
            ->offset($offset)
            ->get();

        return response()->json($items, 200, ['x-conversation-total' => $total]);
    }

    public function delete(Conversation $conversation)
    {
        $conversation->delete();

        return response()->json('', 204);
    }
}
