@section('title')
    找伙伴
@endsection

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/pc/css/user.css') }}"/>
@endsection

@section('content')
    <div class="left_container">
        <div class="user_container">
            <ul class="user_menu">
                <li><a class="menu @if($type == 1) selected @endif" type="1" href="javascript:;" >热门</a></li>
                <li><a class="menu @if($type == 2) selected @endif" type="2" href="javascript:;">最新</a></li>
                <li><a class="menu @if($type == 3) selected @endif" type="3" href="javascript:;">推荐</a></li>
                {{-- <li><a href="{{ route('pc:userarea') }}">地区</a></li> --}}
            </ul>
            <div class="user_filter @if($type != 4) hide @endif">
                <div class="area_search mt20">
                    <input class="font14 search_input" id="location" type="text" name="search_key" placeholder="输入关键字搜索">
                    <div class="area_searching font14 hide"></div>
                    <a class="search_icon" id="J-search-area">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-search"></use></svg>
                    </a>
                    <div class="head_search">
                        <div class="history">
                            <p>历史记录</p>
                            <ul></ul>
                            <div class="clear">
                                <a href="javascript:;" onclick="delHistory('all')">清空历史记录</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
    $(function(){
        var last;
        var type = "{{ $type }}";
        // 关注
        $('#user_list').on('click', '.follow_btn', function(){
            var _this = $(this);
            var status = $(this).attr('status');
            var user_id = $(this).attr('uid');
            follow(status, user_id, _this, afterdata);
        })

        // 图片懒加载
        $("img.lazy").lazyload({effect: "fadeIn"});

        // 点击切换分类
        $('.user_menu .menu').click(function(event){
            var type = $(this).attr('type');

            switchType(type);
            $(this).parents('ul').find('a').removeClass('selected');
            $(this).addClass('selected');
        })

        switchType(type);
    })

    $("#location").keyup(function(event){
        last = event.timeStamp;
        setTimeout(function(){
            if(last - event.timeStamp == 0){
                location_search();
            }
        }, 500);
    })

    $('.area_searching').on('click', 'a', function() {
        $('#location').val($(this).text());
        $('.area_searching').hide();
    })

    function location_search(event)
    {
        var val = $.trim($("#location").val());
        var area_searching = $(".area_searching");
        area_searching.html('').hide();

        if (val != "") {
            axios.get('/api/v2/locations/search', { name: val })
              .then(function (response) {
                if (response.data.length > 0) {
                    $.each(response.data, function(key, value) {
                        if (key < 3) {
                            var text = tree(value.tree);
                            var html = '<a>' + text + '</a>';
                            area_searching.append(html);
                        }
                    });
                    area_searching.show();
                }
              })
              .catch(function (error) {
                showError(error.response.data);
              });
        }
    }

    function tree(obj)
    {
        var text = '';
        if (obj.parent != null) {
            text = tree(obj.parent) + ' ' +  obj.name;
        } else {
            text = obj.name;
        }
        return text;
    }

    // 切换类型加载数据
    var switchType = function(type){

        $('#user_list').html('');
        var params = {
            type: type,
            limit: 10,
            isAjax: true,
        };
        loader.init({
            container: '#user_list',
            loading: '.user_container',
            url: '/people',
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