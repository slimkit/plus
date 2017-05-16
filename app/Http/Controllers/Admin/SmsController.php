<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\VerifyCode;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;

class SmsController extends Controller
{
    public function show(Request $request, ResponseFactory $response)
    {
        $state = $request->query('state');
        $phone = $request->query('phone');
        $limit = $request->query('limit', 20);
        $after = $request->query('after');
        $query = app(VerifyCode::class)->newQuery();

        if ($state !== null) {
            $query->where('state', $state);
        }

        if ($phone) {
            $query->where('account', 'like', sprintf('%%%s%%', $phone));
        }

        if ($after) {
            $query->where('id', '<', $after);
        }

        $query->limit($limit);
        $query->latest();

        return $response->json($query->get(), 200);
    }
}
