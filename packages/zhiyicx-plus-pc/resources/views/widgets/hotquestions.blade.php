@if(!empty($issues))
<div class="hot-issues">
    <div class="title">热门问题</div>
    <ul class="hot-issues-list">
            @foreach($issues as $issue)
                <li>
                    <div class="rank-num">
                        <span @if($loop->index <= 2) class="blue" @elseif($loop->index >= 2) class="grey" @endif>{{$loop->iteration}}
                        </span>
                    </div>
                    <div class="hot-subject">
                        <a class="hot-issues-title" href="{{ Route('pc:questionread', $issue['id']) }}">{{$issue['subject']}}</a>
                    </div>
                </li>
            @endforeach
    </ul>
</div>
@endif