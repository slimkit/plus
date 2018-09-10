<div class="repostable-wrap">
    @switch($type)
        @case('news')
        <blockquote>
            <h3>{{$news['title']}}</h3>
            <p>{{$news['content']}}</p>
        </blockquote>
        @break

        @case('feeds')
        <blockquote>
            <p><strong>{{$feeds['user']['name']}}: </strong>{{$feeds['feed_content']}}</p>
        </blockquote>
        @break
    @endswitch

    <div class="content ev-ipt-repostable-content" contenteditable="true" placeholder="请输入转发理由"></div>

    <div class="tools">
        <span class="repostable-topic" onclick="repostable.topic()">
            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-topic1"></use></svg>
            话题
        </span>
        <span class="repostable-mention" onclick="repostable.showMention()">
            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-mention"></use></svg>
            @某人
        </span>
        <div class="repostable-mention-wrap">
            <div class="dialog-mention-select ev-view-repostable-mention-select" style="display: none;">
                <label class="search-wrap">
                    <svg class="icon" aria-hidden="true" style="fill: #59b6d7;"><use xlink:href="#icon-mention"></use></svg>
                    <input type="text" placeholder="搜索用户" oninput="searchUserForRepostable(this)">
                </label>
                <span class="hot ev-view-repostable-mention-placeholder">关注用户</span>
                <ul class="follow-users ev-view-repostable-follow-users">
                    @foreach($follow_users ?? [] as $user)
                    @if($loop->index < 8)
                    <li data-user-id="{{$user['id']}}" data-user-name="{{$user['name']}}">{{$user['name']}}</li>
                    @endif
                    @endforeach
                </ul>
            </div>
        </div>
        <button onclick="repostable.post('{{$type}}', {{$id}})">分 享</button>
    </div>
</div>
