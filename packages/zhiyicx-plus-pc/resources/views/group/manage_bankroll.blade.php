
@section('title') {{ $group['name'] }}-圈子收益 @endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/pc/css/group.css') }}">
@endsection

@section('content')
<div class="p-bankroll p-group">
    <div class="g-bd f-cb">
        <div class="g-sd">
            <ul>
                <a href="{{ route('pc:groupedit', ['group_id'=>$group['id']]) }}"><li>圈子资料</li></a>
                @if ($group['joined']['role'] == 'founder')
                    <a href="{{ route('pc:groupbankroll', ['group_id'=>$group['id']]) }}"><li class="cur">圈子收益</li></a>
                @endif
                <a href="{{ route('pc:groupmember', ['group_id'=>$group['id']]) }}"><li>成员管理</li></a>
                <a href="{{ route('pc:groupreport', ['group_id'=>$group['id']]) }}"><li>举报管理</li></a>
            </ul>
        </div>
        <div class="g-mn">
            <div class="m-nav">
                <ul class="f-cb" id="J-tab">
                    <li class="cur" type="all">圈子财务</li>
                    <li type="pinned">置顶收益</li>
                    <li type="join">成员费</li>
                </ul>
            </div>
            <div class="m-hd">
                <div class="m-income all-income">
                    <span>{{$group['join_income_count'] + $group['pinned_income_count']}}</span>
                    <div class="s-fc4 f-fs2">账户余额（积分）</div>
                </div>
                <div class="f-dn m-income pinned-income">
                    <span>{{$group['pinned_income_count']}}</span>
                    <div class="s-fc4 f-fs2">置顶收益（积分）</div>
                </div>
                <div class="f-dn m-income join-income">
                    <span>{{$group['join_income_count']}}</span>
                    <div class="s-fc4 f-fs2">成员费（积分）</div>
                </div>
                <div class="m-ct">
                    <div class="u-tt">
                        <span>交易记录</span>
                        <div class="m-filter f-fr">
                            <input class="t-filter" id="T-start" type="text" placeholder="请选择开始日期">
                            -
                            <input class="t-filter" id="T-end" type="text" placeholder="请选择结束日期">
                        </div>
                    </div>
                    <div id="incomes-box"> </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('assets/pc/layer/laydate/laydate.js')}}"></script>
@section('scripts')
<script>
$(function(){
    loader.init({
        container: '#incomes-box',
        loading: '#incomes-box',
        url: '/groups/incomes',
        params: {limit: 15, group_id: {{$group['id']}} }
    });
})

laydate.render({
    elem: '#T-start',
    done: function(value){
        $('#incomes-box').html('');
        if ($('#T-end').val() && value > $('#T-end').val()) {
            noticebox('开始日期不能大于结束日期', 0);
            $('#T-start').val('');
            return;
        }
        loader.init({
            container: '#incomes-box',
            loading: '#incomes-box',
            url: '/groups/incomes',
            params: {limit: 15, group_id: {{$group['id']}}, start: value, end: $('#T-end').val() ,type:$('#J-tab .cur').attr('type')}
        });
    }
});
laydate.render({
    elem: '#T-end',
    done: function(value){
        $('#incomes-box').html('');
        if ($('#T-start').val() && value < $('#T-start').val()) {
            noticebox('结束日期不能小于开始日期', 0);
            $('#T-end').val('');
            return;
        }
        loader.init({
            container: '#incomes-box',
            loading: '#incomes-box',
            url: '/groups/incomes',
            params: {limit: 15, group_id: {{$group['id']}}, start: $('#T-start').val(), end: value,type:$('#J-tab .cur').attr('type')}
        });
    }
});
$('#J-tab li').on('click', function(){
    var type = $(this).attr('type');
        $('#incomes-box').html('');
        $('#J-tab li').removeClass('cur'); $(this).addClass('cur');
        $('.m-income').hide(); $('.'+type+'-income').show();
    var params = {
        limit: 15,
        type: type,
        group_id: {{$group['id']}},
    }
    loader.init({
        container: '#incomes-box',
        loading: '#incomes-box',
        url: '/groups/incomes',
        params: params
    });

    // (type=='all') ? $('.m-filter').show() : $('.m-filter').hide();
});
</script>
@endsection