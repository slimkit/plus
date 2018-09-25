{{-- 转发内容预览 --}}
@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    $repostable = $feed['repostable'];
    $nolink = $nolink ?? false;
@endphp

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
        <a class="feed_repostable" href="javascript:;"
        @if (!$nolink)
            onclick='repostable.jumpToReference("{{ route('pc:feedread', ['feed' => $feed['repostable_id']]) }}", @json($repostable['paid_node']))'
        @endif
        >
            <p class="description">
                <strong>{{$repostable['user']['name']}}: </strong>
                @if (count($repostable['images']))
                <span class="view-more">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-pic"></use></svg> 查看图片
                </span>
                @elseif ($repostable['video'])
                <span class="view-more">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-video"></use></svg> 查看视频
                </span>
                @else
                {{$repostable['feed_content']}}
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
                    <img src='{{ $repostable['avatar'] ? $repostable['avatar']['url'] : asset('assets/pc/images/default_picture.png') }}' alt="">
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
        @elseif($repostable['group']['mode'] !== 'public' && !$repostable['group']['joined'])
        href="{{ route('pc:groupread', ['group_id' => $repostable['group']['id']]) }}"
        @else
        href="{{ route('pc:grouppost', ['group_id' => $repostable['group']['id'], 'post_id' => $feed['repostable_id']]) }}"
        @endif
        >
            @php
                $summary = preg_replace('/@!\[image\]\(\d+\)/', '[图片]', $repostable['summary']); // 替换图片
                $summary = preg_replace('/<{0,1}((http|ftp|https):\/\/)(([a-zA-Z0-9\._-]+\.[a-zA-Z]{2,6})|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,4})*(\/[#a-zA-Z0-9\&%_\.\/-~-]*)?>{0,1}/', '<span style="color: #59b6d7;">网页链接</span>', $summary); // 超级厉害的正则（来自android端）匹配网址
            @endphp
            <p class="description"><strong>{{$repostable['title']}}</strong></p>
            <p class="description">{!! $summary !!}</p>
            @if ($repostable['image'] ?? false)
            <img class="post_cover" src="{{ url('/api/v2/files/' . $repostable['image']) }}" alt="">
            @endif
        </a>
    @else
        <span class="feed_repostable">
            <p class="description">该内容已被删除</p>
        </span>
    @endif
    @break

@endswitch
