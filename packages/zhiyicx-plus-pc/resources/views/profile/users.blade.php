@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')

<div class="fan_cont">
    <ul class="fan_ul">
        @if($user_id && $user_id !== ($TS['id'] ?? 0))
        <li><a href="javascript:;" data-type="1" @if($type == 1) class="a_border" @endif>TA的粉丝</a></li>
        <li><a href="javascript:;" data-type="2" @if($type == 2) class="a_border" @endif>TA的关注</a></li>
        @else
        <li><a href="javascript:;" data-type="1" @if($type == 1) class="a_border" @endif>粉丝</a></li>
        <li><a href="javascript:;" data-type="2" @if($type == 2) class="a_border" @endif>关注</a></li>
        @endif
    </ul>

    <div id="follow-list"></div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/pc/js/module.profile.js') }} "></script>
<script type="text/javascript">
    $(function(){
        loader.init({
            container: '#follow-list',
            user_id:"{{ $user_id }}",
            loading: '.fan_cont',
            type: "{{ $type }}"
        });

        // 菜单切换
        $('.fan_ul a').on('click', function(){
            var type = $(this).data('type');
            $('#follow-list').html('');
            weibo.init({container: '#follow-list',loading: '.fan_cont', user_id:"{{ $user_id }}",type: type});
            $('.fan_ul a').removeClass('a_border');
            $(this).addClass('a_border');
        });

        // 关注
        $('#follow-list').on('click', '.fan_care', function(){
            var _this = $(this);
            var status = $(this).attr('status');
            var user_id = $(this).attr('uid');
            follow(status, user_id, _this, function(target){
                if (target.attr('status') == 1) {
                    target.text('+关注');
                    target.attr('status', 0);
                    target.removeClass('c_ccc');
                } else {
                    target.text('已关注');
                    target.attr('status', 1);
                    target.addClass('c_ccc');
                }
            });
        });

        $("img.lazy").lazyload({effect: "fadeIn"});
    });

</script>
@endsection
