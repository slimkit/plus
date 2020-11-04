@section('title') @if($type == 1) 粉丝 @else 关注 @endif
@endsection
@extends('pcview::layouts.default')


@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/pc/css/user.css') }}"/>
@endsection
@section('content')
    <div class="left_container">
        <div class="user_container">
            <ul class="user_menu">
                @if($user_id && $user_id !== ($TS['id'] ?? 0))
                <li><a type="1" href="javascript:;" @if($type == 1) class="selected" @endif>TA的粉丝</a></li>
                <li><a type="2" href="javascript:;" @if($type == 2) class="selected" @endif>TA的关注</a></li>
                @else
                <li><a type="1" href="javascript:;" @if($type == 1) class="selected" @endif>粉丝</a></li>
                <li><a type="2" href="javascript:;" @if($type == 2) class="selected" @endif>关注</a></li>
                @endif
            </ul>
            <div class="clearfix" id="user_list"></div>
        </div>
    </div>

    <div class="right_container">
        @include('pcview::widgets.hotusers')
    </div>
@endsection

@section('scripts')
<script src="{{ asset('assets/pc/js/module.profile.js') }}"></script>
<script type="text/javascript">
    var user_id = {{ $user_id }};
    var type = {{ $type }};
    $(function(){
        // 关注
        $('#user_list').on('click', '.follow_btn', function(){
            var _this = $(this);
            var status = $(this).attr('status');
            var user_id = $(this).attr('uid');
            follow(status, user_id, _this, afterdata);
        })

        // 导航点击切换
        $('.user_menu a').click(function(){
            var t = $(this).attr('type');
            $(this).parents('.user_menu').find('a').removeClass('selected');
            $(this).addClass('selected');
            switchType(t);
        })
        $("img.lazy").lazyload({effect: "fadeIn"});

        switchType(type);
    })

    // 加载用户列表
    function switchType(type) {
        $('#user_list').html('');
        var params = {
            user_id: user_id,
            type: type,
            isAjax: true,
            limit: 10
        };
        var url = '';
        if (type == 1) {
            url = '/users/' + user_id + '/follower'
        } else {
            url = '/users/' + user_id + '/following'
        }
        loader.init({
            container: '#user_list',
            loading: '.user_container',
            url: url,
            params: params,
            paramtype: 1
        });
    }

    // 关注回调
    var afterdata = function(target){
        if (target.attr('status') == 1) {
            target.text('+关注');
            target.attr('status', 0);
            target.removeClass('followed');
        } else {
            target.text('已关注');
            target.attr('status', 1);
            target.addClass('followed');
        }
    }
</script>
@endsection
