@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
{{-- 个人中心文章栏列表 --}}

@if(!$data->isEmpty())
@foreach($data as $key => $post)
<div class="news_item @if ($loop->iteration == 1)mt30 @endif">

    <div class="news_title">
        <a class="avatar_box" href="{{ route('pc:mine', $post->user->id) }}">
            <img class="avatar" src="{{ getAvatar($post->user, 50) }}" width="50" />
            @if($post->user->verified)
            <img class="role-icon" src="{{ $post->user->verified['icon'] or asset('assets/pc/images/vip_icon.svg') }}">
            @endif
        </a>

        <a href="javascript:;">
            <span class="uname font14">{{ $post->user->name }}</span>
        </a>

        <a class="date" href="{{ route('pc:newsread', $post->id) }}">
            <span class="font12">{{ getTime($post->created_at) }}</span>
            <span class="font12 hide">查看详情</span>
        </a>
    </div>

    <div class="news_body">
        <div class="article_box relative">
            <img data-original="{{$routes['storage']}}{{$post['storage']}}?w=584&h=400" class="lazy img">
            <div class="article_desc">
                <p class="title"><a @if ($post->audit_status == 0) href="{{ route('pc:newsread', $post->id) }}" @endif>{{ $post['title'] }}</a></p>
                <p class="subject">{{ $post['subject'] or '' }}</p>
            </div>
        </div>
    </div>
    <div class="news_bottom mt20">
        @if ($post->audit_status == 0)
        <div class="feed_datas relative">
            <span class="collect" id="J-collect{{$post->id}}" rel="{{$post->collection_count}}" status="{{(int) $post->has_collect}}">
                @if($post->has_collect)
                <a href="javascript:;" onclick="collected.init({{$post->id}}, 'news', 1);">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>
                    <font>{{$post->collection_count}}</font>
                </a>
                @else
                <a href="javascript:;" onclick="collected.init({{$post->id}}, 'news', 1);">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>
                    <font>{{$post->collection_count}}</font>
                </a>
                @endif
            </span>
            <span class="comment J-comment-show">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>
                <font class="cs{{$post->id}}">{{$post->comment_count}}</font>
            </span>
            <span class="view">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-chakan"></use></svg> {{$post->hits}}
            </span>
            @if($post->user_id == $TS['id'])
            <span class="options" onclick="options(this)">
                <svg class="icon icon-more" aria-hidden="true"><use xlink:href="#icon-more"></use></svg>
            </span>
            <div class="options_div">
                <div class="triangle"></div>
                <ul>
                    <li>
                        <a href="javascript:;" onclick="news.pinneds({{$post->id}}, 'news');">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-pinned2"></use></svg>申请置顶
                        </a>
                    </li>
                </ul>
            </div>
            @endif
        </div>
        @endif
        {{-- 评论 --}}
        @include('pcview::widgets.comments', ['id' => $post->id, 'comments_count' => $post->comment_count, 'comments_type' => 'news', 'url' => Route('pc:newsread', $post->id), 'position' => 1, 'comments_data' => $post->comments, 'top' => 1])
        <div class="feed_line"></div>
    </div>
</div>
@endforeach
@endif