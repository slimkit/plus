@section('title')
{{ $user['name'] }}的个人主页
@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/feed.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/pc/css/group.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/pc/css/global.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/pc/css/profile.css') }}"/>
@endsection

@section('content')

{{-- 个人中心头部导航栏 --}}
@include('pcview::profile.navbar')

<div class="profile_body">
    <div class="left_container">
        {{-- 收藏列表 --}}
        <div class="profile_content">
            <div class="profile_menu J-menu">
                <ul class="g-tab">
                    <li>
                        <div type="join" class="zy_select t_c gap12" id="J-group">
                            @if ($TS['id'] == $user['id'])
                                <span>圈子</span>
                                <ul>
                                    <li type="join" @if($type == 'join') class="active" @endif>我加入的</li>
                                    <li type="audit" @if($type == 'audit') class="active" @endif>待审核的</li>
                                </ul>
                                <i></i>
                            @else
                                <a type="join" @if($type == 'join') class="active" @endif>TA加入的</a>
                            @endif
                        </div>
                    </li>
                    @if ($TS['id'] == $user['id'])
                    <li>
                        <div type="5" class="zy_select t_c gap12 select-gray" id="J-post">
                                <span>帖子</span>
                                <ul>
                                    <li type="1" @if($type == 1) class="active" @endif>我发布的</li>
                                    <li type="2" @if($type == 2) class="active" @endif>已置顶的</li>
                                    <li type="3" @if($type == 3) class="active" @endif>置顶待审核</li>
                                </ul>
                                <i></i>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
            <div id="content_list" class="clearfix"></div>
        </div>
    </div>

    <div class="right_box">
        @include('pcview::widgets.recusers')
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/pc/js/module.group.js') }}"></script>
<script src="{{ asset('assets/pc/js/module.profile.js') }}"></script>
<script src="{{ asset('assets/pc/js/module.picshow.js') }}"></script>
<script>
    loader.init({
        container: '#content_list',
        loading: '.profile_content',
        url: '/users/{{ $user['id'] }}/group',
        paramtype: 1,
        params: {isAjax: true, limit: 10}
    });
    $('#J-group li, #J-post li, #J-group a, #J-post a').on('click', function(){
        $('#J-post').addClass('select-gray');
        $('#J-group').addClass('select-gray');
        $(this).parents('.zy_select').removeClass('select-gray');
        $('#content_list').html('');
        var params = {
            limit: 10,
            isAjax: true,
            type: $(this).attr('type'),
        };

        loader.init({
            container: '#content_list',
            loading: '.profile_content',
            url: '/users/{{ $user['id'] }}/group',
            params: params,
            paramtype: 1,
        });
    });
</script>
@endsection