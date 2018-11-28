@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp

@if (!empty($comments))
    @foreach($comments as $comment)
        <dl class="message_one">
            <dt>
                <img src="{{ getAvatar($comment['user'], 40) }}">
                @if($comment['user']['verified'])
                    <img class="role-icon" src="{{ $comment['user']['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                @endif
            </dt>
            <dd>
                <div class="one_title"><a href="/users/{{$comment['user']['id']}}">{{$comment['user']['name']}}</a></div>
                <div class="one_date">{{ getTime($comment['created_at']) }}</div>
                @if ($comment['post'])
                    <div class="top_comment">对帖子“<sapn>{{$comment['post']['title'] ?? '已删除'}}</sapn>”评论并申请置顶，请及时处理。</div>
                @endif
                <div class="comment_audit">
                    @if($comment['comment'] == null || $comment['post'] == null)
                        <a href="javascript:" class="red">该评论已被删除</a>
                    @elseif($comment['expires_at'] == null)
                        <a href="javascript:" class="green" data-args="type=1&comment_id={{$comment['comment']['id']}}">同意置顶</a>
                        <a href="javascript:" class="green" data-args="type=-1&comment_id={{$comment['comment']['id']}}">拒绝置顶</a>
                    @else
                        <a href="javascript:">已审核</a>
                    @endif
                </div>
            </dd>
        </dl>
    @endforeach

    <script>
        axios.defaults.baseURL = TS.SITE_URL;
        axios.defaults.headers.common['Accept'] = 'application/json';
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + TS.TOKEN;
        axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="_token"]').attr('content');
        $('.comment_audit').on('click', 'a', function () {
            var _this = this;
            var data = urlToObject($(this).data('args'));
            var url = '';
            var type = 'PATCH';

            if (data.type == 1) {
                url = '/api/v2/plus-group/currency-pinned/comments/' + data.comment_id + '/accept';
            } else {
                url = '/api/v2/plus-group/currency-pinned/comments/' + data.comment_id + '/reject';
            }

            axios({ method:type, url:url })
              .then(function(response) {
                if (data.type == 1){
                    $(_this).parent('.comment_audit').html('<a href="javascript:">已审核</a>');
                } else {
                    $(_this).parent('.comment_audit').html('<a href="javascript:">已审核</a>');
                }
                TS.UNREAD.pinneds -= 1;
                easemob.setUnreadMes();
              })
              .catch(function (error) {
                showError(error.response.status);
              });
        });
    </script>

@endif
