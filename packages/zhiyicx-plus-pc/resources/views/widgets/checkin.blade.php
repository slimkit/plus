@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp

@if (($config['bootstrappers']['checkin']['switch'] ?? false))
<div class="checkin_cont">
    <div class="checkin_user">
        <span>
            {{$TS['name']}}
        </span>
        <a class="avatar" href="{{ route('pc:mine') }}">
            <img class="round" src="{{ getAvatar($TS, 100) }}"/>
            @if($TS['verified'])
                <img class="role-icon" src="{{ $TS['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
            @endif
        </a>
    </div>
    @if(!$data['checked_in'])
    <div class="checkin_div" onclick="checkIn({{ $data['checked_in'] }}, {{ $data['checkin_count'] }});" id="checkin">
        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-checkin"></use></svg>每日签到
    </div>
    @else
    <div class="checkin_div checked_div">
        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-checkin"></use></svg>
        <span>累计签到{{ $data['checkin_count'] }}天</span>
    </div>
    @endif
</div>
@endif
