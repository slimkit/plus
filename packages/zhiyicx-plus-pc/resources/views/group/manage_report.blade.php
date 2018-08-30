
@section('title') {{ $group['name'] }}-举报管理 @endsection

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
                    <a href="{{ route('pc:groupbankroll', ['group_id'=>$group['id']]) }}"><li>圈子收益</li></a>
                @endif
                <a href="{{ route('pc:groupmember', ['group_id'=>$group['id']]) }}"><li>成员管理</li></a>
                <a href="{{ route('pc:groupreport', ['group_id'=>$group['id']]) }}"><li class="cur">举报管理</li></a>
            </ul>
        </div>
        <div class="g-mn">
            <div class="m-nav  f-mb20">
                <ul class="f-cb" id="J-tab">
                    <li class="cur">全部</li>
                    <li status="0">未处理</li>
                    <li status="1">已处理</li>
                    <li status="2">已驳回</li>
                </ul>
                <div class="m-filter f-fr">
                    <input class="t-filter" id="T-start" type="text" placeholder="请选择开始日期">
                    -
                    <input class="t-filter" id="T-end" type="text" placeholder="请选择结束日期">
                </div>
            </div>
            <div class="m-ct">
                <div id="report-box"> </div>
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
        container: '#report-box',
        loading: '#report-box',
        url: '/groups/report',
        params: {limit: 15, group_id: {{$group['id']}} }
    });
})

laydate.render({
    elem: '#T-start',
    done: function(value){
        $('#report-box').html('');
        if ($('#T-end').val() && value > $('#T-end').val()) {
            noticebox('开始日期不能大于结束日期', 0);
            $('#T-start').val('');
            return;
        }
        loader.init({
            container: '#report-box',
            loading: '#report-box',
            url: '/groups/report',
            params: {limit: 15, group_id: {{$group['id']}}, start: value, end: $('#T-end').val() ,status:$('#J-tab .cur').attr('status')}
        });
    }
});
laydate.render({
    elem: '#T-end',
    done: function(value){
        $('#report-box').html('');
        if ($('#T-start').val() && value < $('#T-start').val()) {
            noticebox('结束日期不能小于开始日期', 0);
            $('#T-end').val('');
            return;
        }
        loader.init({
            container: '#report-box',
            loading: '#report-box',
            url: '/groups/report',
            params: {limit: 15, group_id: {{$group['id']}}, start: $('#T-start').val(), end: value,status:$('#J-tab .cur').attr('status')}
        });
    }
});

var MAG = {
    /**
     * 圈子举报审核
     * @param int gid
     * @param int uid
     * @param int type [1-通过 0-驳回]
     */
    audit: function(rid, type){
        var params = {
            url: '/api/v2/plus-group/reports/'+rid+'/accept',
            method: 'PATCH',
        }
        if (!type) {
            params.url = '/api/v2/plus-group/reports/'+rid+'/reject';
        }
        axios({
            method: params.method,
            url: params.url,
        })
        .then(function (response) {
            noticebox('操作成功', 1);
        });
    },

};

$('#J-tab li').on('click', function(){
    var status = $(this).attr('status');
        $('#report-box').html('');
        $('#J-tab li').removeClass('cur'); $(this).addClass('cur');
    var params = {
        limit: 15,
        group_id: {{$group['id']}},
    }
    if (status != undefined) {
        params.status = status;
    }
    loader.init({
        container: '#report-box',
        loading: '#report-box',
        url: '/groups/report',
        params: params
    });

});
</script>
@endsection