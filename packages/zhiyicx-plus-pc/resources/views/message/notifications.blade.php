@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp
@if (!empty($notifications))
    <ul class="tz-cont">
        @foreach($notifications as $noti)
            @php
                $ref = $noti['data'];
            @endphp
            <li>
                <div class="tz-content">
                    @switch($ref['type'])
                        @case('reward')
                        <a href="{{route('pc:mine', ['user' => $ref['sender']['id']])}}">
                            {{ $ref['sender']['name'] }}打赏了你
                        </a>
                        @break
                        @case('reward:feeds')
                        <a href="{{route('pc:feedread', ['feed'=>$ref['feed_id']])}}">
                            {{ $ref['sender']['name'] }}打赏了你的动态
                        </a>
                        @break
                        @case('reward:news')
                        <a href="{{route('pc:newsread', ['news'=>$ref['news']['id']])}}">
                            你的资讯《{{ $ref['news']['title'] }}》被{{ $ref['sender']['name'] }}
                            打赏了{{ $ref['amount'].$ref['unit'] }}
                        </a>
                        @break
                        @case('group:post-reward')
                        <a href="{{route('pc:grouppost', ['group_id'=>$ref['group_id'], ''=>$ref['post']['id']])}}">
                            你的帖子「{{ $ref['post']['title'] }}」被{{ $ref['sender']['name'] }}打赏了
                        </a>
                        @break
                        @case('group:join')
                        @if($ref['state'] ?? 'accept'== 'rejected')
                            <a href="{{route('pc:groupread', ['group_id'=>$ref['group']['id']])}}">
                                拒绝用户加入「{{ $ref['group']['name'] }}」圈子
                            </a>
                        @else
                            <a href="{{route('pc:groupread', ['group_id'=>$ref['group']['id']])}}">
                                {{ $ref['user'] ? $ref['user']['name'] : ''}}请求加入圈子「{{ $ref['group']['name'] }}」
                            </a>
                        @endif
                        @break
                        @case('user-certification')

                        <a href="{{route('pc:authenticate')}}">
                            @if($ref['state']  === 'rejected')
                                你申请的身份认证已被驳回，驳回理由：{{ $ref['contents'] }}
                            @else
                                你申请的身份认证已通过
                            @endif
                        </a>
                        @break
                        @case('qa:answer-adoption')
                        @case('question:answer')
                        <a href="{{route('pc:answeread', ['question'=>$ref['question']['id'],'answer'=>$ref['answer']['id']])}}">
                            你提交的问题回答被采纳
                        </a>
                        @break
                        @case('qa:reward')
                        <a href="{{route('pc:answeread', ['question'=>$ref['answer']['question_id'],'answer'=>$ref['answer']['id']])}}">
                            {{ $ref['sender']['name'] }}打赏了你的回答
                        </a>
                        @break
                        @case('qa:invitation')
                        <a href="{{route('pc:questionread', ['question'=>$ref['question']['id']])}}">
                            {{ $ref['sender']['name'] }}邀请你回答问题「{{ $ref['question']['subject'] }}」
                        </a>
                        @break
                        @case('pinned:feed/comment')
                        <a href="{{route('pc:feedread', ['feed'=>$ref['feed']['id']])}}">
                            @if($ref['state'] ?? 'accept' == 'rejected')
                                拒绝用户动态评论「{{ $ref['comment']['contents'] }}」的置顶请求
                            @else
                                同意用户动态评论「{{ $ref['comment']['contents'] }}」的置顶请求
                            @endif
                        </a>
                        @break
                        @case('pinned:news/comment')
                        <a href="{{route('pc:newsread', ['news'=>$ref['news']['id']])}}">
                            @if($ref['state'] ?? 'accept' == 'rejected')
                                拒绝用户关于资讯《{{ $ref['news']['title'] }}》评论「{{ $ref['comment']['contents'] }}」的置顶请求
                            @else
                                同意用户关于资讯《{{ $ref['news']['title'] }}》评论「{{ $ref['comment']['contents'] }}」的置顶请求
                            @endif
                        </a>
                        @break
                        @case('group:comment-pinned')
                        @case('group:send-comment-pinned')
                        <a href="{{route('pc:grouppost', ['group_id'=>$ref['group_id'], ''=>$ref['post']['id']])}}">
                            @if($ref['state'] ?? 'accept' == 'rejected')
                                拒绝帖子「{{ $ref['post']['title']}}」的评论置顶请求
                            @else
                                同意帖子「{{ $ref['post']['title']}}」的评论置顶请求
                            @endif
                        </a>
                        @break
                        @case('group:post-pinned')
                        <a href="{{route('pc:grouppost', ['group_id'=>$ref['group_id'], ''=>$ref['post']['id']])}}">
                            @if($ref['state'] ?? 'accept' == 'rejected')
                                拒绝用于帖子「{{ $ref['post']['title']}}」的置顶请求
                            @else
                                同意用于帖子「{{ $ref['post']['title']}}」的置顶请求
                            @endif
                        </a>
                        @break
                        @case('group:pinned-admin')
                        <a href="{{route('pc:grouppost', ['group_id'=>$ref['group_id'], ''=>$ref['post']['id']])}}">
                            你的帖子「{{ $ref['post']['title'] }}」被管理员置顶
                        </a>
                    @endswitch
                </div>
                <div class="tz-date">{{ getTime($noti['created_at']) }}</div>
            </li>
        @endforeach
    </ul>
@else
    {{trans('plus-pc::common.no_more')}}
@endif
