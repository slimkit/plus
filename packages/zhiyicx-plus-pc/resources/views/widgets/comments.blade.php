@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getUserInfo;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatContent;

    $params = $params ?? ['group_id' => 0, 'disabled' => 0];
@endphp
@if($position == 1)
    <div class="comment_box" style="display: none;">
        <div class="comment_line">
            <div class="tr2"></div>
        </div>
        <div class="comment_body" id="comment_box{{ $id }}">
            <div class="comment_textarea">
                <textarea class="comment_editor" id="J-editor-{{ $comments_type }}{{ $id }}" placeholder="说点什么吧" onkeyup="checkNums(this, 255, 'nums');"></textarea>
                <div class="comment_post">
                    <span class="dy_cs">可输入<span class="nums" style="color: rgb(89, 182, 215);">255</span>字</span>
                    <a class="btn btn-primary fr" id="J-button{{ $id }}" data-id="{{ $id }}" data-position="{{ $position or 0 }}" data-type="{{ $comments_type }}" data-top="{{ $top or 0 }}" data-groupid="{{ $params['group_id'] or 0 }}" onclick="comment.publish(this)"> 评 论 </a>
                </div>
            </div>
            <div id="J-commentbox-{{ $comments_type }}{{ $id }}">
                @if($comments_count)
                    @foreach($comments_data as $cv)
                        <p class="comment_con" id="comment{{$cv->id}}">
                            <span class="tcolor">{{ $cv->user['name'] }}：</span>
                            @if ($cv->reply_user)
                                @php $user = getUserInfo($cv->reply_user); @endphp
                                回复<a href="{{ route('pc:mine', $user->id) }}">{{ '@'.$user->name }}</a>：
                            @endif

                            {!! formatContent($cv->body) !!}

                            @if(isset($cv->pinned) && $cv->pinned == 1)
                                <span class="mouse green">置顶</span>
                            @endif

                            @if (
                                    ($TS['id'] == $cv->user_id) ||
                                    (isset($group->joined) && in_array($group->joined->role, ['administrator', 'founder']))
                                )
                                @if(isset($top) && $top == 1 && $TS['id'] == $cv->user_id && !$params['disabled'])
                                    <a class="mouse comment_del" onclick="comment.pinneds('{{$cv['commentable_type']}}', {{$cv['commentable_id']}}, {{$cv['id']}})">申请置顶</a>
                                @endif
                                <a class="mouse comment_del" onclick="comment.delete('{{$cv['commentable_type']}}', {{$cv['commentable_id']}}, {{$cv['id']}})">删除</a>
                            @else
                                <a class="mouse" onclick="comment.reply('{{$cv['user']['id']}}', {{$cv['commentable_id']}}, '{{$cv['user']['name']}}', '{{ $cv['commentable_type'] }}')">回复</a>
                                <a class="mouse" onclick="reported.init('{{$cv['id']}}', '{{$cv['commentable_type']}}');">举报</a>
                            @endif
                        </p>
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
            <textarea class="comment_editor" id="J-editor-{{ $comments_type }}{{ $id }}" placeholder="说点什么吧" onkeyup="checkNums(this, 255, 'nums');"></textarea>
            <div class="comment_tool">
                <span class="text_stats">可输入<span class="nums mcolor"> 255 </span>字</span>
                <button class="btn btn-primary" id="J-button{{ $id }}" data-id="{{ $id }}" data-position="{{ $position or 0 }}" data-type="{{ $comments_type }}" data-top="{{ $top or 0 }}" data-groupid="{{ $params['group_id'] or 0 }}" onclick="comment.publish(this)"> 评 论 </button>
            </div>
        </div>
        <div class="comment_list J-commentbox" id="J-commentbox-{{ $comments_type }}{{ $id }}"></div>
    </div>
@endif

@if ($position == 0)
<script>
    $(function(){
        var params = {};
        var comments_type = '{{ $comments_type }}';
        var group_id = '{{$params['group_id'] or 0}}';
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
                loading: '{{ $loading or ''}}',
                url: types[comments_type],
                params: params,
            });
        }
    })
</script>
@endif