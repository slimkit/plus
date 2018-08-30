@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
@section('title') {{ $group['name'] }}-成员管理 @endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/pc/css/group.css') }}">
@endsection

@section('content')
<div class="p-member p-group">
    <div class="g-bd f-cb">
        <div class="g-sd">
            <ul>
                <a href="{{ route('pc:groupedit', ['group_id'=>$group['id']]) }}"><li>圈子资料</li></a>
                @if ($group['joined']['role'] == 'founder')
                    <a href="{{ route('pc:groupbankroll', ['group_id'=>$group['id']]) }}"><li>圈子收益</li></a>
                @endif
                <a href="{{ route('pc:groupmember', ['group_id'=>$group['id']]) }}"><li class="cur">成员管理</li></a>
                <a href="{{ route('pc:groupreport', ['group_id'=>$group['id']]) }}"><li>举报管理</li></a>
            </ul>
        </div>
        <div class="g-mn">
            <div class="m-nav">
                <ul class="f-cb" id="J-tab">
                    <a href="{{ route('pc:groupmember', ['group_id'=>$group['id']]) }}"><li class="cur">全部成员</li></a>
                    <li type="audit">待审核</li>
                    <li type="blacklist">黑名单</li>
                </ul>
                {{-- <div class="m-sch f-fr">
                    <input class="u-schipt" type="text" placeholder="输入关键词搜索">
                    <a class="u-schico" id="J-search" href="javascript:;">
                        <svg class="icon s-fc"><use xlink:href="#icon-search"></use></svg>
                    </a>
               </div> --}}
            </div>
            <div class="g-body" id="member-box">
            <div>
                <div class="f-mt20 f-fs4">圈主</div>
                <dl class="m-row">
                    <dt>
                        <img src="{{ getAvatar($group['founder']['user'], 50) }}" width="50" class="avatar">
                        @if ($group['founder']['user']['verified'])
                            <img class="role-icon" src="{{ $group['founder']['user']['verified']['icon'] or asset('assets/pc/images/vip_icon.svg') }}">
                        @endif
                    </dt>
                    <dd>{{$group['founder']['user']['name']}}</dd>
                </dl>
            </div>
            <div>
                <div class="f-mt20 f-fs4">管理员</div>
                @if (!empty($manager))
                @foreach ($manager as $manage)
                    <dl class="m-row">
                        <dt>
                            <img src="{{ getAvatar($manage['user'], 50) }}" width="50" class="avatar">
                            @if ($manage['user']['verified'])
                                <img class="role-icon" src="{{ $manage['user']['verified']['icon'] or asset('assets/pc/images/vip_icon.svg') }}">
                            @endif
                        </dt>
                        <dd><div>{{$manage['user']['name']}}</div>
                            <div class="u-opt">
                                <span>管理</span>
                                @if (($group['joined']['role'] == 'founder') && ($group['joined']['user_id'] != $manage['user']))
                                <svg class="icon f-fs2"><use xlink:href="#icon-setting"></use></svg>
                                    <ul class="u-menu f-dn">
                                        <a href="javascript:;" onclick="MAG.set({{$group['id']}}, {{$manage['id']}}, 0);"><li>撤销管理员</li></a>
                                        <a href="javascript:;" onclick="MAG.assign({{$group['id']}}, {{$manage['user_id']}});"><li>转让圈子</li></a>
                                    </ul>
                                @endif
                            </div>
                        </dd>
                    </dl>
                @endforeach
                @else
                    <p class="no-member">暂无成员</p>
                @endif
            </div>
            <div>
                <div class="f-mt20 f-fs4">一般成员</div>
                @if (!empty($members))
                @foreach ($members as $member)
                    <dl class="m-row">
                        <dt>
                            <img src="{{ getAvatar($member['user'], 50) }}" width="50" class="avatar">
                            @if ($member['user']['verified'])
                                <img class="role-icon" src="{{ $member['user']['verified']['icon'] or asset('assets/pc/images/vip_icon.svg') }}">
                            @endif
                        </dt>
                        <dd><div>{{$member['user']['name']}}</div>
                            <div class="u-opt">
                                <span>管理</span>
                                <svg class="icon f-fs2"><use xlink:href="#icon-setting"></use></svg>
                                <ul class="u-menu f-dn">
                                    @if ($group['joined']['role'] == 'founder')
                                    <a href="javascript:;" onclick="MAG.set({{$group['id']}}, {{$member['id']}}, 1);"><li>设为管理员</li></a>
                                    <a href="javascript:;" onclick="MAG.assign({{$group['id']}}, {{$member['user_id']}});"><li>转让圈子</li></a>
                                    @endif
                                    <a href="javascript:;" onclick="MAG.black({{$group['id']}}, {{$member['id']}}, 1);"><li>加入黑名单</li></a>
                                    <a href="javascript:;" onclick="MAG.delete({{$group['id']}}, {{$member['id']}});"><li>踢出圈子</li></a>
                                </ul>
                            </div>
                        </dd>
                    </dl>
                @endforeach
                @else
                    <p class="no-member">暂无成员</p>
                @endif
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>

$('body').on('click', '.u-opt svg', function(){
    $(this).next("ul").toggle();
});

var MAG = {
    /**
     * 设置圈子管理员
     * @param int gid
     * @param int uid
     * @param int type [1-设置 0-移除]
     */
    set: function(gid, uid, type){
        var params = {
            url: '/api/v2/plus-group/groups/'+gid+'/managers/'+uid,
            method: type ? 'PUT' : 'DELETE',
        }
        axios({
            method: params.method,
            url: params.url,
        })
        .then(function (response) {
            noticebox('操作成功', 1, 'refresh');
        })
        .catch(function (error) {
            showError(error.response.data);
        });
    },
    /**
     * 设置圈子黑名单
     * @param int gid
     * @param int uid
     * @param int type [1-加入 0-移除]
     */
    black: function(gid, uid, type){
        var params = {
            url: '/api/v2/plus-group/groups/'+gid+'/blacklist/'+uid,
            method:type ? 'PUT' : 'DELETE',
        }
        axios({
            method: params.method,
            url: params.url,
        })
        .then(function (response) {
            noticebox('操作成功', 1, 'refresh');
        })
        .catch(function (error) {
            showError(error.response.data);
        });
    },
    /**
     * 审核圈子加入请求
     * @param  int gid
     * @param  int id
     * @param int type [1-通过 2-驳回]
     */
    audit: function(gid, id, type){
        var URL = '/api/v2/plus-group/currency-groups/'+gid+'/members/'+id+'/audit';
        axios.patch( URL, {
                status: type
            })
        .then(function (response) {
            noticebox('操作成功', 1, 'refresh');
        })
        .catch(function (error) {
            showError(error.response.data);
        });
    },
    /**
     * 移除圈子成员
     * @param  int gid
     * @param  int uid
     */
    delete: function(gid, uid){
        var URL = '/api/v2/plus-group/groups/'+gid+'/members/'+uid;
        axios.delete( URL )
        .then(function (response) {
            noticebox('操作成功', 1, 'refresh');
        })
        .catch(function (error) {
            showError(error.response.data);
        });
    },
    /**
     *  圈子转让
     * @param  int gid
     * @param  int uid
     */
    assign: function(gid, uid){
        var URL = '/api/v2/plus-group/groups/'+gid+'/owner';
        axios.patch( URL, {
                target: uid
            })
        .then(function (response) {
            noticebox('操作成功', 1, 'refresh');
        })
        .catch(function (error) {
            showError(error.response.data);
        });
    }
}

$('#J-tab li').on('click', function(){
    var type = $(this).attr('type');
        if (type == undefined){
            return;
        }
        $('#member-box').html('');
        $('#J-tab li').removeClass('cur'); $(this).addClass('cur');
    var params = {
        limit: 15,
        type: type,
        group_id: {{$group['id']}},
    }
    loader.init({
        container: '#member-box',
        loading: '#member-box',
        url: '/groups/get-member',
        params: params
    });

});
</script>
@endsection