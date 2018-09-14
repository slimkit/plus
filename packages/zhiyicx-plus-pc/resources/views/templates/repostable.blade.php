<div class="repostable-wrap">
    {{-- 转发内容预览 --}}
    @include('pcview::templates.feed_repostable', ['feed' => $feed, 'nolink' => true])

    <div class="content ev-ipt-repostable-content" contenteditable="true" placeholder="请输入转发内容"></div>

    <ul class="selected-topics ev-selected-repostable-topics">
        {{-- there will be injected topics by javascript --}}
    </ul>

    <div class="tools">
        <span class="repostable-topic">
            <div onclick="repostable.showTopics()">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-topic1"></use></svg>
                <span>话题</span>
            </div>
            <div class="dialog-topic-select ev-view-repostable-topic-select" style="display: none;">
                <label class="search-wrap">
                    <svg class="icon" aria-hidden="true" style="fill: #59b6d7;"><use xlink:href="#icon-search"></use></svg>
                    <input type="text" placeholder="搜索话题" oninput="repostable.searchTopics(this)">
                </label>
                <span class="hot ev-view-repostable-topic-hot">热门话题</span>
                <ul class="topic-list ev-view-repostable-topic-list">
                    @foreach($hot_topics ?? [] as $topic)
                    @if($loop->index < 8)
                    <li data-topic-id="{{$topic['id']}}" data-topic-name="{{$topic['name']}}">{{$topic['name']}}</li>
                    @endif
                    @endforeach
                </ul>
            </div>
        </span>

        <span class="repostable-mention">
            <div onclick="repostable.showMention()">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-mention"></use></svg>
                <span>某人</span>
            </div>
            <!-- <div class="repostable-mention-wrap"> -->
                <div class="dialog-mention-select ev-view-repostable-mention-select" style="display: none;">
                    <label class="search-wrap">
                        <svg class="icon" aria-hidden="true" style="fill: #59b6d7;"><use xlink:href="#icon-mention"></use></svg>
                        <input type="text" placeholder="搜索用户" oninput="repostable.searchUser(this)">
                    </label>
                    <span class="hot ev-view-repostable-mention-placeholder">好友</span>
                    <ul class="follow-users ev-view-repostable-follow-users">
                        @foreach($follow_users ?? [] as $user)
                        @if($loop->index < 8)
                        <li data-user-id="{{$user['id']}}" data-user-name="{{$user['name']}}">{{$user['name']}}</li>
                        @endif
                        @endforeach
                    </ul>
                </div>
            <!-- </div> -->
        </span>
        <button onclick="repostable.post('{{$feed['repostable_type']}}', {{$feed['repostable_id']}})">分 享</button>
    </div>
</div>
