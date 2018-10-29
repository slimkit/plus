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
                <p class="description">
                    @if (mb_strlen($repostable['subject']) > 80)
                    {{ mb_substr($repostable['subject'], 0, 80) }}...
                    @else
                    {{ $repostable['subject'] }}
                    @endif
                </p>
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

    @case('questions')
        <a class="feed_repostable"
        @if ($nolink)
            href="javascript:;"
        @else
            href="{{ route('pc:questionread', ['question' => $feed['repostable_id']]) }}"
        @endif
        >
            <p class="title">{{ $repostable['subject'] }}</p>
            <p class="description">{{ $repostable['body'] }}</p>
        </a>
    @break

    @case('question-answers')
        <a class="feed_repostable"
        @if ($nolink)
            href="javascript:;"
        @else
            href="{{ route('pc:answeread', ['question' => $repostable['question_id'], 'answer' => $repostable['id']]) }}"
        @endif
        >
            <p class="title">{{ $repostable['question']['subject'] }}</p>
            <p class="description">{{ $repostable['body'] }}</p>
        </a>
    @break

@endswitch
