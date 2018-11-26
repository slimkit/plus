{{-- 动态发布 --}}
<div class="feed_post">
    <div class="input-wrap">
        <textarea id="feed_content" class="post_textarea" onkeyup="checkNums(this, 255, 'nums');"></textarea>
        <div id="mirror" class="post_textarea" contenteditable="true"></div>
        <span class="dy_cs">可输入<span class="nums" style="color: rgb(89, 182, 215);">255</span>字</span>
        {{-- at列表 --}}
        <ul id="mention_list"></ul>
    </div>


    <div class="post_extra">
        <span class="font14 ev-btn-feed-pic">
            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-img"></use></svg>
            图片
        </span>

        <span class="font14 ml20 topic-btn">
            <div onclick="weibo.showTopics()">
                <svg class="icon" aria-hidden="true" style="fill: #999;"><use xlink:href="#icon-topic1"></use></svg>
                话题
            </div>

            <div class="dialog-topic-select ev-view-topic-select" style="display: none;">
                <label class="search-wrap">
                    <svg class="icon" aria-hidden="true" style="fill: #59b6d7;"><use xlink:href="#icon-search"></use></svg>
                    <input type="text" placeholder="搜索话题" oninput="weibo.searchTopics(this)">
                </label>
                <span class="hot ev-view-topic-hot">热门话题</span>
                <ul class="topic-list ev-view-topic-list">
                    @foreach($list ?? [] as $topic)
                    @if($loop->index < 8)
                    <li data-topic-id="{{$topic['id']}}" data-topic-name="{{$topic['name']}}">{{$topic['name']}}</li>
                    @endif
                    @endforeach
                </ul>
            </div>
        </span>

        <span class="font14 ml20 mention-btn">
            <div onclick="weibo.showMention()">
                <svg class="icon" aria-hidden="true" style="fill: #999;"><use xlink:href="#icon-mention"></use></svg>
                某人
            </div>

            <div class="dialog-mention-select ev-view-mention-select" style="display: none;">
                <label class="search-wrap">
                    <svg class="icon" aria-hidden="true" style="fill: #59b6d7;"><use xlink:href="#icon-mention"></use></svg>
                    <input type="text" placeholder="搜索用户" oninput="weibo.searchUser(this)">
                </label>
                <span class="hot ev-view-mention-placeholder">好友</span>
                <ul class="follow-users ev-view-follow-users">
                    @foreach($follow_users ?? [] as $user)
                    @if($loop->index < 8)
                    <li data-user-id="{{$user['id']}}" data-user-name="{{$user['name']}}">{{$user['name']}}</li>
                    @endif
                    @endforeach
                </ul>
            </div>

        </span>

        <a href="javascript:;" class="post_button" onclick="weibo.postFeed()">分享</a>

        {{-- 付费免费 --}}
        @if($config['bootstrappers']['feed']['paycontrol'])
            <div data-value="free" class="zy_select t_c gap12 feed_select" id="feed_select">
                <span>免费</span>
                <ul>
                    <li data-value="pay">付费</li>
                    <li class="active" data-value="free">免费</li>
                </ul>
                <i></i>
            </div>
        @endif

        <ul class="selected-topics ev-selected-topics">
            {{-- 如果传入了话题, 就默认添加这个话题 --}}
            @if ($default_topic ?? [])
            <li class="selected-topic-item ev-selected-topic-default" data-topic-id="{{ $default_topic['id'] }}">{{$default_topic['name']}}</li>
            @endif
            {{-- this will be injected by javascript --}}
        </ul>
    </div>
</div>
