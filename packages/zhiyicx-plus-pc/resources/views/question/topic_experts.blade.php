@section('title')
    专题详情-更多专家
@endsection

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/pc/css/user.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/pc/css/question.css') }}"/>
@endsection

@section('content')
    <div class="experts_container">
        <div class="user_container">
            <div class="clearfix" id="user_list"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/pc/js/module.profile.js') }}"></script>
    <script type="text/javascript">
        $(function(){
            // 关注
            $('#user_list').on('click', '.follow_btn', function(){
                var _this = $(this);
                var status = $(this).attr('status');
                var user_id = $(this).attr('uid');
                follow(status, user_id, _this, afterdata);
            });

            // 图片懒加载
            $("img.lazy").lazyload({effect: "fadeIn"});

            getExperts();
        });

        $('.area_searching').on('click', 'a', function() {
            $('#location').val($(this).text());
            $('.area_searching').hide();
        });

        // 切换类型加载数据
        var getExperts = function(){
            var topic_id = "{{ $topic }}";
            $('#user_list').html('');
            var params = {
                limit: 18,
                isAjax: true,
            };
            loader.init({
                container: '#user_list',
                loading: '.user_container',
                url: '/question-topics/' + topic_id + '/experts',
                params: params,
                paramtype: 0
            });
        };

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
