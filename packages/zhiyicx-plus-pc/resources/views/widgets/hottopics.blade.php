@if(!empty($topics))
    <div class="hot-issues">
        <div class="title">热门专题</div>
        <ul class="hot-issues-list">
            @foreach($topics as $topic)
                <li>
                    <div class="rank-num">
                        <span @if($loop->index <= 2) class="blue" @elseif($loop->index >= 2) class="grey" @endif>{{$loop->iteration}}
                        </span>
                    </div>
                    <div class="hot-subject">
                        <a class="hot-issues-title" href="{{ Route('pc:topicinfo', $topic['id']) }}">{{$topic['name']}}</a>
                        <div class="hot-issues-count">
                            <span class="count">关注  {{$topic['follows_count']}}</span>
                            <span class="count">问题  {{$topic['questions_count']}}</span>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endif
