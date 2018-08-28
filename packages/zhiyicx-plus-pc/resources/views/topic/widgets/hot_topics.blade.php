<div class="w-hot-topics">
    <header>热门话题</header>
    <hr>
    <ol>
        @foreach($list as $topic)
        <li title="{{$topic['name']}}" @if($loop->index < 3)class="top3"@endif>
            <a href="/topic/{{$topic['id']}}">{{$topic['name']}}</a>
        </li>
        @endforeach
    </ol>
</div>
