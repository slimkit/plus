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

                <div class="top_comment">对你的动态进行了"<sapn>{{$comment['comment']['body']}}</sapn>"评论并申请置顶，请及时审核。</div>

                <div class="comment_audit">
                    @if($comment['comment'] == null || $comment['feed'] == null)
                        <a href="javascript:" class="red">该评论已被删除</a>
                    @elseif($comment['expires_at'] == null)
                        <a href="javascript:" class="green" data-args="type=1&feed_id={{$comment['feed']['id']}}&comment_id={{$comment['comment']['id']}}&pinned_id={{$comment['id']}}">同意置顶</a>
                        <a href="javascript:" class="green" data-args="type=-1&feed_id={{$comment['feed']['id']}}&comment_id={{$comment['comment']['id']}}&pinned_id={{$comment['id']}}">拒绝置顶</a>
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
                url = '/api/v2/feeds/'+data.feed_id+'/comments/'+data.comment_id+'/currency-pinneds/'+data.pinned_id;
            } else {
                url = '/api/v2/user/feed-comment-currency-pinneds/'+data.pinned_id;
                type = 'DELETE';
            }

            axios({ method:type, url:url })
              .then(function(response) {
                if (response.data.type == 1){
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