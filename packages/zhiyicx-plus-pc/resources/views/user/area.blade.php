@section('title') 找伙伴 - 地区查找 @endsection

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/pc/css/user.css') }}"/>
@endsection

@section('content')
    <div class="left_container">
        <div class="user_container">
            <ul class="user_menu">
                <li><a type="1" href="{{ route('pc:users', 1) }}">热门</a></li>
                <li><a type="2" href="{{ route('pc:users', 2) }}">最新</a></li>
                <li><a type="3" href="{{ route('pc:users', 3) }}">推荐</a></li>
                <li><a type="4" href="javascript:;" class="selected">地区</a></li>
            </ul>
            <div class="user_filter">
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
        // 关注
        $('#user_list').on('click', '.follow_btn', function(){
            var _this = $(this);
            var status = $(this).attr('status');
            var user_id = $(this).attr('uid');
            follow(status, user_id, _this, afterdata);
        })
        // switchType(type);
    })

	$('.area_searching').on('click', 'a', function() {
        $('#location').val($(this).text());
        $('.area_searching').hide();
    })

	$('#J-search-area, #J-area span').on('click', function(e){
        var key = $('.search_input').val();
        if(e.target.tagName == 'SPAN') key = $(this).text();
        getGeo(key, function(geo){
        	$('#user_list').html('');
	        var params = {
	            type: 4,
	            limit: 10,
	            latitude: geo[0],
	            longitude: geo[1],
	        };
	        loader.init({
                container: '#user_list',
                loading: '.user_container',
                url: '/users',
                params: params,
                paramtype: 1
            });
            $('.search_input').val('');
        });
    })

    $("#location").keyup(function(event){
        last = event.timeStamp;
        setTimeout(function(){
            if(last - event.timeStamp == 0){
                var val = $.trim($("#location").val());
		        var box = $(".area_searching");
		        if (val == "")  return false;
                axios.post('/api/v2/locations/search', { name: val })
                  .then(function (response) {
                    if (response.data.length > 0) {
                        $.each(response.data, function(key, value) {
                            if (key < 3) {
                                var text = tree(value.tree);
                                var html = '<a>' + text + '</a>';
                                box.html('');
                                box.append(html);
                            }
                        });
                        box.show();
                    }
                  })
                  .catch(function (error) {
                    showError(error.response.data);
                  });
            }
        }, 100);
    })

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

    var getGeo = function(key, callback){
        if(key) {
            axios.get('/api/v2/around-amap/geo', { address: key })
              .then(function (response) {
                if(response.data.geocodes[0]){
                    var geo = response.data.geocodes[0].location.split(',');
                    callback(geo);
                } else {
                    return false;
                }
              })
              .catch(function (error) {
                showError(error.response.data);
              });
        }
    }
</script>
@endsection