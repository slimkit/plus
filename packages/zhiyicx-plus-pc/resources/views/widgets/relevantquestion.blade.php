@if(!$issues->isEmpty())
    <div class="right-relevant">
        <div class="relevant-issues">
            <div class="title">相关问题推荐</div>
            <ul class="relevant-issues-list">
                @foreach($issues as $issue)
                    <li>
                        <a href="{{ Route('pc:questionread', $issue->id) }}">{{$issue->subject}}</a>
                        {{-- <span>{{$issue->likes_count}}个赞</span> --}}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif