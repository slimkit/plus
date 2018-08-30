@section('title') {{ $user['name'] }} 的个人主页@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/global.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/pc/css/question.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/pc/css/profile.css') }}"/>
@endsection

@section('content')

{{-- 个人中心头部导航栏 --}}
@include('pcview::profile.navbar')

<div class="profile_body p-qa p-topic">
    <div class="left_container">
        <div class="profile_content">
            <div class="m-snav" id="J-menu">
                <div data-value="1" class="zy_select t_c gap12 mr20" id="J-question">
                    <span class="qa_opt">全部问题</span>
                    <ul>
                        <li data-value="all" class="active">全部提问</li>
                        <li data-value="invitation">邀请提问</li>
                        <li data-value="reward">悬赏提问</li>
                        <li data-value="other">其他提问</li>
                    </ul>
                    <i></i>
                </div>
                <div data-value="1" class="zy_select t_c gap12 mr20 select-gray" id="J-answer">
                    @if ($user['id'] == $TS['id'])
                        <span class="qa_opt">我的回答</span>
                    @else
                        <span class="qa_opt">TA的回答</span>
                    @endif
                    <ul>
                        <li data-value="all" class="active">全部</li>
                        <li data-value="adoption">被采纳</li>
                        <li data-value="invitation">被邀请</li>
                        <li data-value="other">其他</li>
                    </ul>
                    <i></i>
                </div>
                @if ($user['id'] == $TS['id'])
                    <a class="qa_opt ucolor" href="javascript:;" data-value="3">关注的问题</a>
                    <a class="qa_opt ucolor" href="javascript:;" data-value="4">关注的专题</a>
                @endif
            </div>
            <div id="content_list" class="m-lst">
            </div>
        </div>
    </div>

    <div class="right_box">
        @include('pcview::widgets.recusers')
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/pc/js/module.profile.js') }} "></script>
    <script src="{{ asset('assets/pc/js/module.question.js') }}"></script>
    <script>
        $(function(){
            loader.init({
                container: '#content_list',
                loading: '.profile_content',
                url: '/users/{{$user['id']}}/q-a',
                params: {isAjax: true, type: 'all', user_id: '{{$user['id']}}' }
            });
        })

        $('#J-question li').on('click', function(){
            var type = $(this).data('value');
            $('#content_list').html('');
            loader.init({
                container: '#content_list',
                loading: '.profile_content',
                url: '/users/{{$user['id']}}/q-a',
                params: {type: type, isAjax: true, cate: 1, user_id: '{{$user['id']}}' }
            });
        });
        $('#J-answer li').on('click', function(){
            var type = $(this).data('value');
            $('#content_list').html('');
            loader.init({
                container: '#content_list',
                loading: '.profile_content',
                url: '/users/{{$user['id']}}/q-a',
                params: {type: type, isAjax: true, cate: 2, user_id: '{{$user['id']}}' }
            });
        });

        $('#J-menu a').on('click', function(){
            var type = $(this).data('value');
            $('#content_list').html('');
            loader.init({
                container: '#content_list',
                loading: '.profile_content',
                url: '/users/{{$user['id']}}/q-a',
                params: {isAjax: true, cate: type, user_id: '{{$user['id']}}' }
            });

            $('.qa_opt').removeClass('active');
            $(this).addClass('active');
        });

        $('#content_list').on('click', '.J-follow', function(){
            checkLogin();
            var _this = this;
            var status = $(this).attr('status');
            var topic_id = $(this).attr('tid');
            topic(status, topic_id, function(){
                if (status == 1) {
                    $(_this).text('+关注');
                    $(_this).attr('status', 0);
                    $(_this).removeClass('followed');
                } else {
                    $(_this).text('已关注');
                    $(_this).attr('status', 1);
                    $(_this).addClass('followed');
                }
            });
        });
        $('#content_list').on('click', '.J-watched', function(){
            checkLogin();
            var _this = this;
            var status = $(this).attr('status');
            var url = '/api/v2/user/question-watches/'+$(this).data('id')
            var type = '';

            if (status == 1) {
                type = 'DELETE';
            } else {
                type = 'PUT';
            }

            axios({ method:type, url:url })
              .then(function (response) {
                if (status == '1') {
                    _this.attr('status', 0);
                    _this.find('span.watched').text('关注');
                } else {
                    _this.attr('status', 1);
                    _this.find('span.watched').text('已关注');
                }
              })
              .catch(function (error) {
                showError(error.response.data);
              });
        });

    </script>
@endsection
