@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp
@if (count($notifications) > 0)
    <ul class="tz-cont">
        @foreach($notifications as $noti)
            <li>
                <div class="tz-content">
                    @switch($noti['data']['type'] ?? '0')
                        @case('reward')
                        <a href="{{route('pc:mine', ['user' => $noti['data']['sender']['id']])}}">
                            {{ $noti['data']['sender']['name'] }}打赏了你
                        </a>
                        @break
                        @case('pinned:feeds')
                        <a href="{{route('pc:feedread', ['feed' => $noti['data']['feed']['id']])}}">
                            @if($noti['data']['state'] === 'passed')
                                你的动态已经成功置顶
                            @else
                                管理员拒绝了你的动态置顶申请
                            @endif
                        </a>
                        @break
                        @case('reward:feeds')
                        <a href="{{route('pc:feedread', ['feed'=>$noti['data']['feed_id']])}}">
                            {{ $noti['data']['sender']['name'] }}打赏了你的动态
                        </a>
                        @break
                        @case('news:audit')
                        <a href="{{route('pc:newsread', ['news'=>$noti['data']['news']['id']])}}">
                            {{ $noti['data']['contents'] ?? ''}}
                        </a>
                        @break
                        @case('news:reject')
                        <a href="{{route('pc:newsrelease', ['news_id'=>$noti['data']['news']['id']])}}">
                            {{ $noti['data']['contents'] ?? ''}}
                        </a>
                        @break
                        @case('reward:news')
                        <a href="{{route('pc:newsread', ['news'=>$noti['data']['news']['id']])}}">
                            你的资讯《{{ $noti['data']['news']['title'] }}》被{{ $noti['data']['sender']['name'] }}
                            打赏了{{ $noti['data']['amount'].$noti['data']['unit'] }}
                        </a>
                        @break
                        @case('user-certification')
                        <a href="{{route('pc:authenticate')}}">
                            @if($noti['data']['state']  === 'rejected')
                                你申请的身份认证已被驳回，驳回理由：{{ $noti['data']['contents'] }}
                            @else
                                你申请的身份认证已通过
                            @endif
                        </a>
                        @break
                        @case('pinned:feed/comment')
                        <a href="{{route('pc:feedread', ['feed'=>$noti['data']['feed']['id']])}}">
                            @if($noti['data']['state'] === 'rejected' )
                                你的动态评论「{{ $noti['data']['comment']['contents'] }}」已被拒绝置顶
                            @else
                                你的动态评论「{{ $noti['data']['comment']['contents'] }}」已置顶
                            @endif
                        </a>
                        @break
                        @case('feed:topic:create:passed')
                        <a href="{{route('pc:topicDetail', ['topic_id' => $noti['data']['topic']['id']])}}">
                            {{$noti['data']['contents']}}
                        </a>
                        @break
                        @case('feed:topic:create:failed')
                        <a href="javascript:void(0)">
                            {{$noti['data']['contents']}}
                        </a>
                        @break
                        @case('pinned:news/comment')
                        <a href="{{route('pc:newsread', ['news'=>$noti['data']['news']['id']])}}">
                            @if($noti['data']['state'] === 'rejected')
                                资讯《{{ $noti['data']['news']['title'] }}
                                》评论「{{ $noti['data']['comment']['contents'] }}」已被拒绝置顶
                            @else
                                资讯《{{ $noti['data']['news']['title'] }}
                                》评论「{{ $noti['data']['comment']['contents'] }}」已置顶
                            @endif
                        </a>
                        @break
                        @case('news:delete:reject')
                        <a href="{{route('pc:newsread', ['news'=>$noti['data']['news']['id']])}}">
                            你申请删除资讯「{{ $noti['data']['news']['title']}}」的请求已被拒绝
                        </a>
                        @break
                        @case('news:delete:accept')
                        <a href="javascript:void(0)">
                            你申请删除资讯「{{ $noti['data']['news']['title']}}」的请求已被通过
                        </a>
                        @break
                        @case('user-currency:cash')
                        @if($noti['data']['state'] === 'rejected')
                            你的积分提现申请已被拒绝，理由：{{$noti['data']['contents']}}
                        @else
                            你的积分提现申请已通过申请
                        @endif
                        @break
                        @case('delete:feed/comment')
                        <a href="{{route('pc:feedread',['feed' => $noti['data']['feed']['id']])}}">
                            你的评论「{{$noti['data']['comment']['contents']}}」已被管理员删除
                        </a>
                        @break
                        @case('report')
                        @switch($noti['data']['resource']['type'])
                            @case('groups')
                            <a href="{{route('pc:groupread',['group_id' => $noti['data']['resource']['id']])}}">
                                你举报的圈子「{{$noti['data']['subject']}}
                                」平台已{{$noti['data']['state'] === 'rejected' ? '驳回' : '处理'}},
                                备注: {{$noti['data']['resource']['mark'] ?? ''}}
                            </a>
                            @break
                            @case('feeds')
                            <a href="{{route('pc:feedread',['feed' => $noti['data']['resource']['id']])}}">
                                你举报的「{{$noti['data']['subject']}}
                                」平台已{{$noti['data']['state'] === 'rejected' ? '驳回' : '处理'}},
                                备注: {{$noti['data']['resource']['mark'] ?? ''}}
                            </a>
                            @break
                            @case('news')
                            <a href="{{route('pc:newsread',['news' => $noti['data']['resource']['id']])}}">
                                你举报的「{{$noti['data']['subject']}}
                                」平台已{{$noti['data']['state'] === 'rejected' ? '驳回' : '处理'}},
                                备注: {{$noti['data']['resource']['mark'] ?? ''}}
                            </a>
                            @break
                            @case('comments')
                            <a href="javascript:void(0)">
                                你举报的「{{$noti['data']['subject']}}
                                」平台已{{$noti['data']['state'] === 'rejected' ? '驳回' : '处理'}},
                                备注: {{$noti['data']['resource']['mark'] ?? ''}}
                            </a>
                            @break
                        @endswitch
                        @break
                    @endswitch
                </div>
                <div class="tz-date">{{ getTime($noti['created_at']) }}</div>
            </li>
        @endforeach
    </ul>
@else
    {{trans('plus-pc::common.no_more')}}
@endif
