<div class="hot_news">
    <div class="title">近期热点</div>
    <ul class="time_menu" id="time_menu">
        <li type="week"><a href="javascript:;" class="hover">一周</a></li>
        <li type="month"><a href="javascript:;">月度</a></li>
        <li type="quarter"><a href="javascript:;">季度</a></li>
    </ul>
    <ul class="hot_news_list"">
        <div class="hot_news_item" id="week">
            @if(!$week->isEmpty())
            @foreach($week as $item)
                <li>
                    <span @if($loop->index > 2) class="grey" @endif>{{$loop->iteration}}</span>
                    <a href="{{ Route('pc:newsread', $item->id) }}">{{$item->title}}</a>
                </li>
            @endforeach
            @else
                <div class="no_news">暂无相关信息</div>
            @endif
        </div>
        <div class="hot_news_item hide" id="month">
            @if(!$month->isEmpty())
            @foreach($month as $item)
                <li>
                    <span @if($loop->index > 2) class="grey" @endif>{{$loop->iteration}}</span>
                    <a href="{{ Route('pc:newsread', $item->id) }}">{{$item->title}}</a>
                </li>
            @endforeach
            @else
                <div class="no_news">暂无相关信息</div>
            @endif
        </div>
        <div class="hot_news_item hide" id="quarter">
            @if(!$quarter->isEmpty())
            @foreach($quarter as $item)
                <li>
                    <span @if($loop->index > 2) class="grey" @endif>{{$loop->iteration}}</span>
                    <a href="{{ Route('pc:newsread', $item->id) }}">{{$item->title}}</a>
                </li>
            @endforeach
            @else
                <div class="no_news">暂无相关信息</div>
            @endif
        </div>
    </ul>
</div>