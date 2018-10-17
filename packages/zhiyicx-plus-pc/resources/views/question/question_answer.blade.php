@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getUserInfo;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatList;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp
@if(!empty($answers))
    @foreach ($answers as $answer)
        <div class="list-item" id="answer{{$answer['id']}}">
            <div class="list-item-header">
                <span class="userlink authorinfo-avatarwrapper">
                    @if($answer['anonymity'] == 1 && !(isset($TS) && $answer['user_id'] == $TS['id']))
                        <img class="avatar avatar--round" width="50" height="50" src="{{ asset('assets/pc/images/ico_anonymity_60.png') }}" alt="">
                    @else
                        <a href="{{ route('pc:mine', $answer['user']['id']) }}" class="avatar_box">
                            <img class="avatar" width="50" height="50" src="{{ getAvatar($answer['user'], 50) }}" alt="">
                            @if ($answer['user']['verified'])
                                <img class="role-icon" src="{{ $answer['user']['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                            @endif
                        </a>
                    @endif
                </span>
                <div class="authorinfo-content">
                    <div class="authorinfo-head">
                        @if($answer['anonymity'] == 1 && !(isset($TS) && $answer['user_id'] == $TS['id']))
                            <span class="userlink authorinfo-name">匿名用户</span>
                        @else
                            <a href="{{ route('pc:mine', $answer['user']['id']) }}" class="userlink authorinfo-name">{{ $answer['user']['name'] }} {{ isset($TS) && $answer['anonymity'] == 1 && $answer['user_id'] == $TS['id'] ? '（匿名）' : ''}}</a>
                        @endif
                        @if(isset($answer['invited']) && $answer['invited'] == 1)
                            <span class="blue-tag">邀请回答</span>
                        @endif
                        @if($answer['adoption'] == 1)
                            <span class="green-tag">已采纳</span>
                        @endif
                    </div>
                    <div class="authorinfo-time">
                        <span>{{ getTime($answer['created_at']) }}</span>
                    </div>
                </div>
            </div>
            <div class="list-item-content">
                <div class="content-inner">
                    @if($answer['invited'] == 0 || $question['look'] == 0 || (isset($TS) && $answer['invited'] == 1 && ((!isset($answer['could']) || $answer['could'] !== false) || $question['user_id'] == $TS['id'] || $answer['user_id'] == $TS['id'])))
                        <span class="answer-body">{!! str_limit(formatList($answer['body']), 250, '...') !!}</span>
                        <a class="button button-plain button-more" href="{{ route('pc:answeread', ['question' => $answer['question_id'], 'answer' => $answer['id']]) }}">查看详情</a>
                    @else
                        <span class="answer-body fuzzy" onclick="QA.look({{ $answer['id'] }}, '{{ $config['bootstrappers']['question:onlookers_amount'] }}' , {{ $answer['question_id'] }})">@php for ($i = 0; $i < 250; $i ++) {echo 'T';} @endphp</span>
                    @endif
                </div>

                <div class="answer-footer">
                    <div class="answer-footer-inner">
                        <a href="{{ route('pc:answeread', ['question' => $answer['question_id'], 'answer' => $answer['id']]) }}" class="button button-plain">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-share"></use></svg>
                            {{ $answer['likes_count'] }} 分享
                        </a>
                        <a href="{{ route('pc:answeread', ['question' => $answer['question_id'], 'answer' => $answer['id']]) }}" class="button button-plain comment J-comment-show">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>
                            <font class="cs{{$answer['id']}}">{{$answer['comments_count']}}</font> 评论
                        </a>
                        <a href="javascript:;" class="button button-plain" id="J-likes{{$answer['id']}}" onclick="liked.init({{$answer['id']}}, 'question', 1);" status="{{(int) (isset($TS) && $answer['liked'])}}" rel="{{ $answer['likes_count'] }}">
                            @if(isset($TS) && $answer['liked'])
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-likered"></use></svg>
                            @else
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-like"></use></svg>
                            @endif
                            <font>{{ $answer['likes_count'] }}</font> 点赞
                        </a>

                        <a href="javascript:;" class="button button-plain options" type="button" aria-haspopup="true" aria-expanded="false" onclick="options(this)">
                            <svg class="icon icon-more" aria-hidden="true"><use xlink:href="#icon-more"></use></svg>
                        </a>
                        <div class="options_div">
                            <div class="triangle"></div>
                            <ul>
                                <li id="J-collect{{$answer['id']}}" rel="0" status="{{ isset($answer['collected']) ? 1 : 0 }}">
                                    @if($answer['collected'] ?? false)
                                    <a href="javascript:;" onclick="collected.init({{$answer['id']}}, 'question', 0);" class="act">
                                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>
                                        <span>已收藏</span>
                                    </a>
                                    @else
                                    <a href="javascript:;" onclick="collected.init({{$answer['id']}}, 'question', 0);">
                                      <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>
                                      <span>收藏</span>
                                    </a>
                                    @endif
                                </li>
                                <li>
                                    <a href="javascript:;" onclick="repostable.show('question-answers', {{ $answer['id'] }})">
                                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-share"></use></svg> 转发
                                    </a>
                                </li>
                                @if($question['user_id'] == $TS['id'])
                                    <li>
                                        @if($answer['adoption'] == 1)
                                            <a class="act" href="javascript:;">
                                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-adopt"></use></svg>
                                                已采纳
                                            </a>
                                        @else
                                            <a href="javascript:;" onclick="QA.adoptions('{{$answer['question_id']}}', '{{$answer['id']}}', '/questions/{{ $answer['question_id'] }}')">
                                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-adopt"></use></svg>
                                                采纳
                                            </a>
                                        @endif
                                    </li>
                                @endif
                                @if($answer['user_id'] == $TS['id'] && (!$answer['invited'] && !$answer['adoption']))
                                    <li>
                                        <a href="javascript:;" onclick="QA.delAnswer({{$answer['question_id']}}, {{$answer['id']}}, '/questions/{{ $answer['question_id'] }}')">
                                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-delete"></use></svg>
                                            删除
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a href="javascript:;" onclick="reported.init('{{$answer['id']}}', 'question-answer');">
                                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-report"></use></svg>举报
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        @if(isset($answer['invited']) && $answer['invited'] == 1)
                            <div class="look-answer">

                                <span class="look-user">{{ $answer['onlookers_count'] }}人正在围观</span>
                                @if($question['user_id'] != $TS['id'] && $answer['user_id'] != $TS['id'])
                                    @if(isset($TS) && ($answer['could'] ?? false))
                                        <button class="button look-cloud" type="button">已围观</button>
                                    @else
                                        <button class="button button-blue button-primary look-cloud" onclick="QA.look({{ $answer['id'] }}, '{{ $config['bootstrappers']['question:onlookers_amount'] }}' , {{ $answer['question_id'] }})" type="button">围观</button>
                                    @endif
                                @endif
                            </div>
                        @endif

                    </div>

                    {{-- 评论 --}}
                    {{-- @include('pcview::widgets.comments', [
                        'id' => $answer['id'],
                        'comments_count' => count($answer['comments']),
                        'comments_type' => 'question-answers',
                        'url' => Route('pc:answeread', ['question' => $answer['question_id'], 'answer' => $answer['id']]),
                        'position' => 1,
                        'comments_data' => $answer['comments'],
                    ]) --}}

                </div>
            </div>
        </div>
    @endforeach
@endif
