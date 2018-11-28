@section('title')
排行榜
@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/rank.css') }}"/>
@endsection

@section('content')
    <div class="dy_bg fans_bg">
        <div class="dy_cont list_bg">
            <ul class="list_ul">
                <li><a href="{{ route('pc:rank',['mold'=>1]) }}" class="font16 @if($mold == 1) a_border @endif">用户排行榜</a></li>
                <li><a href="javascript:;" class="font16" onclick="layer.alert(buyTSInfo)">问答排行榜</a></li>
                <li><a href="{{ route('pc:rank',['mold'=>3]) }}" class="font16 @if($mold == 3) a_border @endif">动态排行榜</a></li>
                <li><a href="javascript:;" class="font16" onclick="layer.alert(buyTSInfo)">资讯排行榜</a></li>
            </ul>
            @if($mold == 1)
                <div class="fans_div">
                    @if(!empty($follower))
                        @include('pcview::templates.rank', ['title' => '粉丝排行榜', 'genre' => 'follower', 'post' => $follower, 'tabName' => '粉丝数'])
                    @endif
                    @if(!empty($balance))
                        @include('pcview::templates.rank', ['title' => '财富达人排行榜', 'genre' => 'balance', 'post' => $balance])
                    @endif
                    @if(!empty($income))
                        @include('pcview::templates.rank', ['title' => '收入达人排行榜', 'genre' => 'income', 'post' => $income])
                    @endif
                    @if(!empty($check))
                        @include('pcview::templates.rank', ['title' => '社区签到排行榜', 'genre' => 'check', 'post' => $check, 'tabName' => '累计签到'])
                    @endif
                </div>
            @elseif($mold == 3)     {{--动态排行榜--}}
                <div class="fans_div">
                    @if(!empty($feeds_day))
                        @include('pcview::templates.rank', ['title' => '今日动态排行榜', 'genre' => 'feeds_day', 'post' => $feeds_day, 'tabName' => '点赞量'])
                    @endif
                    @if(!empty($feeds_week))
                        @include('pcview::templates.rank', ['title' => '一周动态排行榜', 'genre' => 'feeds_week', 'post' => $feeds_week, 'tabName' => '点赞量'])
                    @endif
                    @if(!empty($feeds_month))
                        @include('pcview::templates.rank', ['title' => '本月动态排行榜', 'genre' => 'feeds_month', 'post' => $feeds_month, 'tabName' => '点赞量'])
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function gorank(action,genre,num) {
            var _this = $("div[rel='"+genre+"div']").first();
            var current = $('div[rel="'+genre+'div"][current="1"]');
            //当前页数
            var curnum = $('#'+genre+'num').text();

            //向前
            if ( action == 1 ){
                curnum = parseInt(curnum) - 1;
            } else {
                //向后翻页
                curnum = parseInt(curnum) + 1;
            }
            var last = $('div[rel="' + genre + 'div"][current="1"]').prev();
            var postArgs = {};
            postArgs.offset = (curnum - 1) * num;
            if (postArgs.offset >= 100 || postArgs.offset < 0) {

                return false;
            }
            postArgs.limit = num;
            postArgs.genre = genre;
            if ( last != undefined ) {
                axios.get('/rank/rankList', { params: postArgs })
                  .then(function (response) {
                    if (response.data.status) {
                        if (response.data.data.count <= 0) {
                            //noticebox('已无更多啦', 0);
                        } else {
                            $('#'+genre+'-rank-list').html(response.data.data.html);
                            $('#'+genre+'num').text(curnum);
                            var old = _this.find('.fans_span1').children('span').text();
                            if (old < postArgs.offset) {
                                $('#' + genre + 'last').hasClass('arrow-rank-l-l') ? $('#' + genre + 'last').removeClass('arrow-rank-l-l').addClass('arrow-rank-l') : '';
                            } else if (old > postArgs.offset) {
                                $('#' + genre + 'next').hasClass('arrow-rank-r-l') ? $('#' + genre + 'next').removeClass('arrow-rank-r-l').addClass('arrow-rank-r') : '';
                            }
                            if (postArgs.offset <= 0) {
                                $('#' + genre + 'last').hasClass('arrow-rank-l') ? $('#' + genre + 'last').removeClass('arrow-rank-l').addClass('arrow-rank-l-l') : '';
                            } else if(response.data.data.count < postArgs.limit || postArgs.offset >= 90) {
                                $('#' + genre + 'next').hasClass('arrow-rank-r') ? $('#' + genre + 'next').removeClass('arrow-rank-r').addClass('arrow-rank-r-l') : '';
                            }
                        }
                    }
                  })
                  .catch(function (error) {
                    showError(error.response.data);
                  });
            }
        }
    </script>
@endsection
