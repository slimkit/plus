@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getUserInfo;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatContent;

    $params = $params ?? ['group_id' => 0, 'disabled' => 0];
@endphp
@if($position === 1)
    <div class="comment_box" style="display: none;">
        <div class="comment_line">
            <div class="tr2"></div>
        </div>
        <div class="comment_body" id="comment_box{{ $id }}">
            <div class="comment_textarea">
                <div class="input-wrap">
                    <textarea class="comment_editor ev-comment-editor" id="J-editor-{{ $comments_type }}{{ $id }}" placeholder="说点什么吧" oninput="checkNums(this, 255, 'nums');" ></textarea>
                    <div class="comment_editor mirror ev-mirror" contenteditable="true"></div>
                    {{-- at列表 --}}
                    <ul class="ev-mention-list"></ul>
                </div>
                <div class="comment_post">
                    <span class="dy_cs">可输入<span class="nums" style="color: rgb(89, 182, 215);">255</span>字</span>
                    <span class="font14 mention-btn">
                        <div onclick="comment.showMention(true, this);">
                            <svg class="icon" aria-hidden="true" style="fill: #999;"><use xlink:href="#icon-mention"></use></svg>
                            某人
                        </div>

                        <div class="dialog-mention-select ev-view-comment-mention-select" style="display: none;">
                            <label class="search-wrap">
                                <svg class="icon" aria-hidden="true" style="fill: #59b6d7;"><use xlink:href="#icon-mention"></use></svg>
                                <input type="text" placeholder="搜索用户" oninput="comment.searchUser(this)">
                            </label>
                            <span class="hot ev-view-comment-mention-placeholder">好友</span>
                            <ul class="follow-users ev-view-comment-follow-users">
                                @foreach($follow_users ?? [] as $user)
                                @if($loop->index < 8)
                                <li data-user-id="{{$user['id']}}" data-user-name="{{$user['name']}}">{{$user['name']}}</li>
                                @endif
                                @endforeach
                            </ul>
                        </div>

                    </span>
                    <a class="btn btn-primary fr" id="J-button{{ $id }}" data-id="{{ $id }}" data-position="{{ $position ?? 0 }}" data-type="{{ $comments_type }}" data-top="{{ $top ?? 0 }}" data-groupid="{{ $params['group_id'] ?? 0 }}" onclick="comment.publish(this)"> 评 论 </a>
                </div>
            </div>
            <div id="J-commentbox-{{ $comments_type }}{{ $id }}">
                @if($comments_count)
                    @foreach($comments_data as $cv)
                        @if ($cv['user'] ?? false)
                        <p class="comment_con" id="comment{{$cv['id']}}">
                            <span class="tcolor">{{ $cv['user']['name'] }}：</span>
                            @if ($cv['reply_user'])
                                @php $user = getUserInfo($cv['reply_user']); @endphp
                                回复<a href="{{ route('pc:mine', $user['id']) }}">{{ $user['name'] }}</a>：
                            @endif

                            {!! formatContent($cv['body']) !!}

                            @if(isset($cv['pinned']) && $cv['pinned'])
                                <span class="mouse green">置顶</span>
                            @endif

                            @if (
                                    (($TS['id'] ?? 0) === $cv['user_id']) ||
                                    (isset($group['joined']) && in_array($group['joined']['role'], ['administrator', 'founder']))
                                )
                                @if(isset($top) && $top == 1 && ($TS['id'] ?? 0) === $cv['user_id'] && !$params['disabled'])
                                    <a class="mouse comment_del" onclick="comment.pinneds('{{$cv['commentable_type']}}', {{$cv['commentable_id']}}, {{$cv['id']}})">申请置顶</a>
                                @endif
                                <a class="mouse comment_del" onclick="comment.delete('{{$cv['commentable_type']}}', {{$cv['commentable_id']}}, {{$cv['id']}})">删除</a>
                            @else
                                <a class="mouse" onclick="comment.reply('{{$cv['user']['id']}}', {{$cv['commentable_id']}}, '{{$cv['user']['name']}}', '{{ $cv['commentable_type'] }}')">回复</a>
                                <a class="mouse" onclick="reported.init('{{$cv['id']}}', '{{$cv['commentable_type']}}');">举报</a>
                            @endif
                        </p>
                        @endif
                    @endforeach
                @endif
            </div>
            @if($comments_count >= 5)
                <div class="comit_all font12"><a href="{{ $url }}">查看全部评论</a></div>
            @endif
        </div>
    </div>
@else
    <div class="detail_comment @if(isset($add_class)) {{ $add_class }} @endif">
        @if($comments_type == 'question')
            <span id="answer-button" class="answer-button">
                <img src="{{asset('assets/pc/images/arrow_news_up.png')}}" alt="">
            </span>
        @endif
        <div class="comment_title"><span class="comment_count cs{{ $id }}">{{ $comments_count }} </span>人评论</div>
        <div class="comment_box">
            <div class="input-wrap">
                <textarea class="comment_editor ev-comment-editor" id="J-editor-{{ $comments_type }}{{ $id }}" placeholder="说点什么吧" oninput="checkNums(this, 255, 'nums');" ></textarea>
                <div class="comment_editor mirror ev-mirror" contenteditable="true"></div>
                {{-- at列表 --}}
                <ul class="ev-mention-list"></ul>
            </div>
            <div class="comment_buttons">
                <span class="font14 mention-btn">
                    <div onclick="comment.showMention(true, this);">
                        <svg class="icon" aria-hidden="true" style="fill: #999;"><use xlink:href="#icon-mention"></use></svg>
                        某人
                    </div>

                    <div class="dialog-mention-select ev-view-comment-mention-select" style="display: none;">
                        <label class="search-wrap">
                            <svg class="icon" aria-hidden="true" style="fill: #59b6d7;"><use xlink:href="#icon-mention"></use></svg>
                            <input type="text" placeholder="搜索用户" oninput="comment.searchUser(this)">
                        </label>
                        <span class="hot ev-view-comment-mention-placeholder">好友</span>
                        <ul class="follow-users ev-view-comment-follow-users">
                            @foreach($follow_users ?? [] as $user)
                            @if($loop->index < 8)
                            <li data-user-id="{{$user['id']}}" data-user-name="{{$user['name']}}">{{$user['name']}}</li>
                            @endif
                            @endforeach
                        </ul>
                    </div>
                </span>
            </div>
            <div class="comment_tool">
                <span class="text_stats">可输入<span class="nums mcolor"> 255 </span>字</span>
                <button class="btn btn-primary" id="J-button{{ $id }}" data-id="{{ $id }}" data-position="{{ $position ?? 0 }}" data-type="{{ $comments_type }}" data-top="{{ $top ?? 0 }}" data-groupid="{{ $params['group_id'] ?? 0 }}" onclick="comment.publish(this)"> 评 论 </button>
            </div>
        </div>
        <div class="comment_list J-commentbox" id="J-commentbox-{{ $comments_type }}{{ $id }}"></div>
    </div>
@endif

@if ($position == 0)
<script>
    $(function(){
        var params = {
          limit: 15
        };
        var comments_type = '{{ $comments_type }}';
        var group_id = '{{$params['group_id'] ?? 0}}';
        if (group_id) {
            params.group_id = group_id;
        }
        var types = {
            'news' : '/news/{{$id}}/comments',
            'feeds' : '/feeds/{{$id}}/comments',
            'group-posts' : '/groups/{{$id}}/comments',
            'answer' : '/questions/answers/{{$id}}/comments',
            'product' : '/product/{{$id}}/comments',
            // 'question' : '/questions/{{$id}}/comments',
        };
        if (types[comments_type] !== undefined) {
            loader.init({
                container: '.J-commentbox',
                loading: '{{ $loading ?? ''}}',
                url: types[comments_type],
                params: params,
            });
        }
    })
</script>
@endif
