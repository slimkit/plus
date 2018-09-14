{{-- 转发内容预览 --}}
@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    $repostable = $feed['repostable'];
    $nolink = $nolink ?? false;
@endphp

<script>console.log(@json($repostable));</script>

@switch($feed['repostable_type'])

    @case('news')
    @if($repostable['title'] ?? false)
        <a class="feed_repostable news"
        @if ($nolink)
            href="javascript:;"
        @else
            href="{{ route('pc:newsread', ['news_id' => $feed['repostable_id']]) }}"
        @endif
        >
            <div class="news-left">
                <div class="cover" style='background-image: url({{ url("/api/v2/files/{$repostable['image']['id']}") }})'></div>
            </div>
            <div class="news-right">
                <p class="title">{{$repostable['title']}}</p>
                <p class="description">{{$repostable['subject']}}</p>
                <div class="info">
                    <span class="category">{{ $repostable['category']['name'] }}</span>
                    <span class="information">{{ $repostable['author'] }} · {{ $repostable['hits'] }}浏览 · {{ getTime($repostable['created_at']) }}</span>
                </div>
            </div>
        </a>
    @else
        <span class="feed_repostable">
            <p class="description">该内容已被删除</p>
        </span>
    @endif
    @break

    @case('feeds')
    @if($repostable['id'] ?? false)
        <a class="feed_repostable"
        @if ($nolink)
            href="javascript:;"
        @else
            href="{{ route('pc:feedread', ['feed' => $feed['repostable_id']]) }}"
        @endif
        >
            <p class="description">
                <strong>{{$repostable['user']['name']}}: </strong>
                {{$repostable['feed_content']}}
                @if (count($repostable['images']))
                <span class="view-more">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-liaotiantubiao_5"></use></svg> 查看图片
                </span>
                @endif
                @if ($repostable['video'])
                <span class="view-more">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-liaotiantubiao_5"></use></svg> 查看视频
                </span>
                @endif
            </p>
        </a>
    @else
        <span class="feed_repostable">
            <p class="description">该内容已被删除</p>
        </span>
    @endif
    @break

    @case('groups')
    @if($repostable['name'])
        <a class="feed_repostable group"
        @if ($nolink)
            href="javascript:;"
        @else
            href="{{ route('pc:groupread', ['group_id' => $feed['repostable_id']]) }}"
        @endif
        >
            <div class="group-wrap">
                <div class="group-left">
                    <div class="label">{{ $repostable['category']['name'] }}</div>
                    <img src='{{ $repostable['avatar'] ? $repostable['avatar']->url() : asset('assets/pc/images/default_picture.png') }}' alt="">
                </div>
                <div class="group-right">
                    <p class="title"><strong>{{$repostable['name']}}</strong></p>
                    <p class="description">{{$repostable['summary']}}</p>
                </div>
            </div>
        </a>
    @else
        <span class="feed_repostable">
            <p class="description">该内容已被删除</p>
        </span>
    @endif
    @break

    @case('group-posts')
    @case('posts')
    @if($repostable['title'] ?? false)
        <a class="feed_repostable"
        @if ($nolink)
            href="javascript:;"
        @else
            href="{{ route('pc:grouppost', ['group_id' => 1, 'post_id' => $feed['repostable_id']]) }}"
        @endif
        >
            <p class="description"><strong>{{$repostable['user']['name']}}: {{$repostable['title']}}</strong></p>
            <p class="description">{{ substr(preg_replace('/@!\[image\]\(\d+\)/', '[图片]', $repostable['summary']), 0, 255)}}
            </p>
        </a>
    @else
        <span class="feed_repostable">
            <p class="description">该内容已被删除</p>
        </span>
    @endif
    @break

@endswitch
