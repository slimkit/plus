<div class="repostable-wrap">
    {{-- 转发内容预览 --}}
    @switch($type)
        @case('news')
        <blockquote>
            <h3>{{$news['title']}}</h3>
            <p>{{$news['summary']}}</p>
        </blockquote>
        @break

        @case('feeds')
        <blockquote>
            <p><strong>{{$feeds['user']['name']}}: </strong>{{$feeds['feed_content']}}</p>
        </blockquote>
        @break

        @case('groups')
        <blockquote>
            <p><strong>{{$groups['name']}}</strong></p>
            <p>{{$groups['summary']}}</p>
        </blockquote>
        @break

        @case('posts')
        <blockquote>
            <p><strong>{{$posts['user']['name']}}: {{$posts['title']}}</strong></p>
            <p>{{$posts['summary']}}</p>
        </blockquote>
        @break

    @endswitch

    <div class="content ev-ipt-repostable-content" contenteditable="true" placeholder="请输入转发理由"></div>

    <ul class="selected-topics ev-selected-repostable-topics">
        {{-- there will be injected topics by javascript --}}
    </ul>

    <div class="tools">
        <span class="repostable-topic">
            <div onclick="repostable.showTopics()">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-topic1"></use></svg>
                话题
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
                @某人
            </div>
            <!-- <div class="repostable-mention-wrap"> -->
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
            <!-- </div> -->
        </span>
        <button onclick="repostable.post('{{$type}}', {{$id}})">分 享</button>
    </div>
</div>
