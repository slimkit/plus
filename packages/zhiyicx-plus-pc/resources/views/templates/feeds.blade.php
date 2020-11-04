@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatContent;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getUserInfo;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp

@if(!empty($feeds))
@foreach($feeds as $key => $post)
<div class="feed_item" id="feed_{{$post['id']}}" data-amount="{{ $post['paid_node']['amount'] ?? 0 }}" data-node="{{ $post['paid_node']['node'] ?? 0 }}">
    <div class="feed_title">
        <a class="avatar_box" href="{{ route('pc:mine', $post['user']['id']) }}">
            <img class="avatar" src="{{ getAvatar($post['user'], 50) }}" width="50" />
            @if($post['user']['verified'])
            <img class="role-icon" src="{{ $post['user']['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
            @endif
        </a>

        <a href="javascript:;">
            <span class="feed_uname font14">{{ $post['user']['name'] }}</span>
        </a>

        <a class="date" @if($post['paid_node'] && $post['paid_node']['paid'] == false) href="javascript:;"  onclick="weibo.payText(this, '/feeds/{{ $post['id'] }}')" @else href="{{ route('pc:feedread', ['feed' => $post['id']]) }}" @endif>
            <span class="feed_time font12">{{ getTime($post['created_at']) }}</span>
            <span class="feed_time font12 hide">查看详情</span>
        </a>

        @if(isset($post['pinned']) && $post['pinned'])
        <a class="pinned" href="javascript:;">
            <span class="font12">置顶</span>
        </a>
        @endif
    </div>

    <div class="feed_body">
        {{-- 文字付费 --}}
        @if ($post['paid_node'] && $post['paid_node']['paid'] == false)
        <p class="feed_text" onclick="weibo.payText(this)">
            @php
            $content = mb_substr($post['feed_content'], 0, $config['bootstrappers']['feed']['limit'], 'utf-8');
            $len = (strlen($content) + mb_strlen($content, 'utf-8')) / 2;
            @endphp
            {!! formatContent($content) !!}
            <span class="fuzzy">@php for ($i = 0; $i < (200 - $len); $i ++) {echo 'T';} @endphp</span>
        </p>
        @else
        <a class="feed_text" href="{{ route('pc:feedread', ['feed' => $post['id']]) }}">
            @php
                $content = $post['feed_content'];
                $has_more = mb_strlen($content, 'utf-8') > 100;
                $content = mb_substr($content, 0, 100, 'utf-8');
            @endphp
            {!! formatContent($content) !!}
            @if($has_more)
            <a href="{{ route('pc:feedread', ['feed' => $post['id']]) }}" class="more mcolor"> ...查看更多</a>
            @endif
        </a>
        @endif

        @include('pcview::templates.feed_images', ['target_url' => route('pc:feedread', ['feed' => $post['id']])])

        @if($post['repostable_type'] ?? false)
        @include('pcview::templates.feed_repostable', ['feed' => $post])
        @endif

    </div>

    <div class="feed_bottom">
        <div class="selected-topics">
            @if (isset($post['topics']))
            @foreach($post['topics'] as $topic)
            <a class="selected-topic-item" href='{{ url("/topic/{$topic['id']}") }}'>{{$topic['name']}}</a>
            @endforeach
            @endif
        </div>

        <div class="feed_datas">
            <span class="digg" id="J-likes{{$post['id']}}" rel="{{$post['like_count']}}" status="{{(int) $post['has_like']}}">
                @if($post['has_like'])
                <a href="javascript:void(0)" onclick="liked.init({{ $post['id'] }}, 'feeds', 1)">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-likered"></use></svg> <font>{{ $post['like_count'] }}</font>
                </a>
                @else
                <a href="javascript:;" onclick="liked.init({{$post['id']}}, 'feeds', 1)">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-like"></use></svg> <font>{{$post['like_count']}}</font>
                </a>
                @endif
            </span>
            <span class="comment J-comment-show">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>
                <span class="cs{{$post['id']}}">{{$post['feed_comment_count']}}</span>
            </span>
            <span class="view">
                <a @if($post['paid_node'] && $post['paid_node']['paid'] == false)
                        href="javascript:;"
                        onclick="weibo.payText(this, '/feeds/{{ $post['id'] }}')"
                    @else
                        href="{{ route('pc:feedread', ['feed' => $post['id']]) }}"
                    @endif
                >
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-chakan"></use>
                    </svg>
                    {{$post['feed_view_count']}}
                </a>
            </span>
            <span class="options" onclick="options(this)">
                <svg class="icon icon-more" aria-hidden="true"><use xlink:href="#icon-more"></use></svg>
            </span>
            <div class="options_div">
                <div class="triangle"></div>
                <ul>
                    <li>
                        <a href="javascript:;" onclick="repostable.show('feeds', {{$post['id']}})">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-share"></use></svg>
                            <span>转发</span>
                        </a>
                    </li>
                    <li id="J-collect{{$post['id']}}" rel="0" status="{{(int) $post['has_collect']}}">
                        @if($post['has_collect'])
                        <a href="javascript:;" onclick="collected.init({{$post['id']}}, 'feeds', 0);" class="act">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>
                            <span>已收藏</span>
                        </a>
                        @else
                        <a href="javascript:;" onclick="collected.init({{$post['id']}}, 'feeds', 0);">
                          <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>
                          <span>收藏</span>
                        </a>
                        @endif
                    </li>
                    @if(($TS['id'] ?? 0) === $post['user_id'])
                    <li>
                        <a href="javascript:;" onclick="weibo.pinneds({{$post['id']}});">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-pinned2"></use></svg>申请置顶
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" onclick="weibo.delFeed({{$post['id']}});">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-delete"></use></svg>删除
                        </a>
                    </li>
                    @else
                    <li>
                        <a href="javascript:;" onclick="reported.init('{{$post['id']}}', 'feed');">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-report"></use></svg>
                            <span>举报</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- 评论 --}}
        @include('pcview::widgets.comments', [
            'id' => $post['id'],
            'comments_count' => count($post['comments']),
            'comments_type' => 'feeds',
            'url' => Route('pc:feedread', $post['id']),
            'position' => 1,
            'comments_data' => $post['comments'],
            'top' => 1,
        ])

        <div class="feed_line"></div>
    </div>
</div>
@endforeach
@endif
