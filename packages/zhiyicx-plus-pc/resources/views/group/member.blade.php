@php
	use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp

@section('title')圈子成员@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/pc/css/group.css') }}">
@endsection

@section('content')
<div class="p-member p-notice">
    <div class="g-mn">
        <div class="g-hd f-mb30">
            <span class="f-fs5">圈子成员</span>
            <a class="f-fr s-fc" href="javascript:history.go(-1);">返回</a>
        </div>
        <div class="g-bd">
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
	                    <dd><div>{{$manage['user']['name']}}</div></dd>
	                </dl>
	            @endforeach
	            @else
	                <p class="no-member">暂无成员</p>
	            @endif
	        </div>
	        <div>
	            <div class="f-mt20 f-fs4">一般成员</div>
	            <div id="member-box"> </div>
	            @if (!empty($members))
	            @foreach ($members as $member)
	                <dl class="m-row">
	                    <dt>
							<img src="{{ getAvatar($member['user'], 50) }}" width="50" class="avatar">
							@if ($member['user']['verified'])
								<img class="role-icon" src="{{ $member['user']['verified']['icon'] or asset('assets/pc/images/vip_icon.svg') }}">
							@endif
						</dt>
	                    <dd><div>{{$member['user']['name']}}</div></dd>
	                </dl>
	            @endforeach
	            @else
	                <p class="no-member">暂无成员</p>
	            @endif
	        </div>
        </div>
    </div>
</div>
@endsection