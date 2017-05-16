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
        $page = $request->query('page');
        $query = app(VerifyCode::class)->newQuery();

        if ($state !== null) {
            $query->where('state', $state);
        }

        if ($phone) {
            $query->where('account', 'like', sprintf('%%%s%%', $phone));
        }

        $query->latest();

        $data = $query->simplePaginate($limit);

        return $response->json($data, 200);
    }
}
