<div class="repostable-wrap">
    @switch($type)
        @case('news')
        <blockquote>
            <h3>{{$news['title']}}</h3>
            <p>{{$news['content']}}</p>
        </blockquote>
        @break

        @case('feed')
        <blockquote>
            <p>{{$feed['feed_content']}}</p>
        </blockquote>
        @break
    @endswitch

    <div class="content" contenteditable=true placeholder="请输入转发理由"></div>

    <div class="tools">
        <span>
            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-topic1"></use></svg>
            话题
        </span>
        <span>
            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-mention"></use></svg>
            @某人
        </span>
        <button>分 享</button>
    </div>
</div>
