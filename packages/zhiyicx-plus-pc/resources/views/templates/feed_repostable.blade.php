{{-- 转发内容预览 --}}
@if($feed['repostable'] && !$feed['repostable']['exception'])
    @switch($feed['repostable_type'])

        @case('news')
        <a class="feed_repostable" href="{{ route('pc:newsread', ['news_id' => $feed['repostable_id']]) }}">
            <p class="title">{{$feed['repostable']['title']}}</p>
            <p class="description">{{$feed['repostable']['subject']}}</p>
        </a>
        @break

        @case('feeds')
        <a class="feed_repostable" href="{{ route('pc:feedread', ['feed' => $feed['repostable_id']]) }}">
            <p class="description"><strong>{{$feed['repostable']['user']['name']}}: </strong>{{$feed['repostable']['feed_content']}}</p>
        </a>
        @break

        @case('groups')
        <a class="feed_repostable" href="{{ route('pc:groupread', ['group_id' => $feed['repostable_id']]) }}">
            <p class="description"><strong>{{$feed['repostable']['name']}}</strong></p>
            <p class="description">{{$feed['repostable']['summary']}}</p>
        </a>
        @break

        @case('posts')
        <a class="feed_repostable" href="{{ route('pc:grouppost', ['group_id' => 1, 'post_id' => $feed['repostable_id']]) }}">
            <p class="description"><strong>{{$feed['repostable']['user']['name']}}: {{$feed['repostable']['title']}}</strong></p>
            <p class="description">{{$feed['repostable']['summary']}}</p>
        </a>
        @break

    @endswitch
@else
    <span class="feed_repostable">
        <p class="description">该内容已被删除</p>
    </span>
@endif

<script>
console.log(@json($feed));
</script>
