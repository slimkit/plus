{{-- 动态发布 --}}
<div class="feed_post">
    <textarea class="post_textarea" placeholder="说说新鲜事" id="feed_content" amount="" onkeyup="checkNums(this, 255, 'nums');"></textarea>
    <div class="post_extra">
        <span class="font14 ev-btn-feed-pic">
            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-img"></use></svg>
            图片
        </span>

        <span class="font14 ml20 topic-btn">
            <div class="ev-btn-topic">
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
            <div class="ev-btn-mention">
                <svg class="icon" aria-hidden="true" style="fill: #999;"><use xlink:href="#icon-mention"></use></svg>
                @某人
            </div>

            <div class="dialog-mention-select ev-view-mention-select" style="display: none;">
                <label class="search-wrap">
                    <svg class="icon" aria-hidden="true" style="fill: #59b6d7;"><use xlink:href="#icon-mention"></use></svg>
                    <input type="text" placeholder="搜索用户" oninput="weibo.searchUsers(this)">
                </label>
                <span class="hot ev-view-follow-users">关注用户</span>
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
            <li class="selected-topic-item">{{$default_topic['name']}}
                <span class="close ev-delete-topic" data-topic-id="{{$default_topic['id']}}">
                    <svg class="icon" aria-hidden="true" style="fill: #59b6d7;"><use xlink:href="#icon-close"></use></svg>
                </span>
            </li>
            @endif
            {{-- this will be injected by javascript --}}
        </ul>

        <span class="dy_cs">可输入<span class="nums" style="color: rgb(89, 182, 215);">255</span>字</span>
    </div>
</div>
