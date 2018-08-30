<div class="g-sidec">
    <div class="u-tt">热门圈子</div>
    <ul class="m-box">
    @if(!empty($groups))
    @foreach($groups as $group)
        <a href="{{ Route('pc:groupread', $group['id']) }}">
        <li>
            <div class="u-lab">
                <span @if($loop->index <= 2) class="blue" @elseif($loop->index >= 2) class="grey" @endif>{{$loop->iteration}}</span>
            </div>
            <div class="f-toe"> {{$group['name']}} </div>
            <div class="u-col">
                <span class="f-mr20 s-fc4">帖子  {{$group['posts_count']}} </span>
                <span class="s-fc4">成员  {{$group['users_count']}} </span>
            </div>
        </li>
        </a>
    @endforeach
    @else
        <div class="no-groups">暂无相关信息</div>
    @endif
    </ul>
</div>