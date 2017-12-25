<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Controllers\V2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecialLink;

class MusicController extends Controller
{
    /**
     * 专辑详情.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  Music   $music  
     * @return           
     */
    public function show(Request $request, Music $music)
    {
        $uid = $request->user('api')->id ?? 0;
        $music->load(['singer' => function ($query) {
            $query->with('cover');
        }]);

        $music->has_like = $music->liked($uid);
        $music = $music->formatStorage($uid);
        $music->increment('taste_count'); // 歌曲增加播放数量
        
        $music->musicSpecials->each(function ($musicSpecial) {
            $musicSpecial->increment('taste_count');
        }); // 相应专辑增加播放数量 

        return response()->json($music)->setStatusCode(200);
    }

    /**
     * 增加歌曲分享数.
     *
     * @author bs<414606094@qq.com>
     * @param  Music  $music
     * @return mixed
     */
    public function share(Music $music)
    {
        $music->increment('share_count');
        $music->musicSpecials->each(function ($musicSpecial) {
            $musicSpecial->increment('share_count');
        }); 

        return response()->json([])->setStatusCode(204);
    }
}