@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
<div class="answer-rank">
    <div class="title">问答达人排行</div>
    <div class="rank-tab" id="J-rank-tab">
        <span class="active" type="day">今日</span>
        <span type="week">一周</span>
        <span type="month">本月</span>
    </div>

    {{-- 天排行榜 --}}
    <ul class="answer-rank-list" id="J-tab-day">
        @if(!empty($qrank['day']))
            @foreach($qrank['day'] as $day)
                <li>
                <a href="{{ route('pc:mine', $day['id']) }}">
                    <div class="rank-num">
                        <span @if($loop->index <= 2) class="blue" @elseif($loop->index >= 2) class="grey" @endif>
                            {{ $day['extra']['rank'] }}
                        </span>
                    </div>
                    <div class="rank-avatar">
                        <img src="{{ getAvatar($day, 60) }}" width="60" height="60" class="avatar"/>
                        @if ($day['verified'])
                            <img class="role-icon" src="{{ $day['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                        @endif
                    </div>
                    <div class="rank-info">
                        <span class="tcolor">{{ $day['name'] }}</span>
                        <span class="ucolor txt-hide">回答数：{{ $day['extra']['count'] }}</span>
                    </div>
                </a>
                </li>
            @endforeach
        @else
            <div class="no-data">暂无相关信息</div>
        @endif
    </ul>

    {{-- 周排行榜 --}}
    <ul class="answer-rank-list" id="J-tab-week" style="display: none;">
        @if(!empty($qrank['week']))
            @foreach($qrank['week'] as $week)
                <li>
                <a href="{{ route('pc:mine', $week['id']) }}">
                    <div class="rank-num">
                        <span @if($loop->index <= 2) class="blue" @elseif($loop->index >= 2) class="grey" @endif>
                            {{ $week['extra']['rank'] }}
                        </span>
                    </div>
                    <div class="rank-avatar">
                        <img src="{{ getAvatar($week, 60) }}" width="60" height="60" class="avatar"/>
                        @if ($week['verified'])
                            <img class="role-icon" src="{{ $week['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                        @endif
                    </div>
                    <div class="rank-info">
                        <span class="tcolor">{{ $week['name'] }}</span>
                        <span class="ucolor txt-hide">回答数：{{ $week['extra']['count'] }}</span>
                    </div>
                </a>
                </li>
            @endforeach
        @else
            <div class="no-data">暂无相关信息</div>
        @endif
    </ul>

    {{-- 月排行榜 --}}
    <ul class="answer-rank-list" id="J-tab-month" style="display: none;">
        @if(!empty($qrank['month']))
            @foreach($qrank['month'] as $month)
                <li>
                <a href="{{ route('pc:mine', $month['id']) }}">
                    <div class="rank-num">
                        <span @if($loop->index <= 2) class="blue" @elseif($loop->index >= 2) class="grey" @endif>
                            {{ $month['extra']['rank'] }}
                        </span>
                    </div>
                    <div class="rank-avatar">
                        <img src="{{ getAvatar($month, 60) }}" width="60" height="60" class="avatar"/>
                        @if ($month['verified'])
                            <img class="role-icon" src="{{ $month['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                        @endif
                    </div>
                    <div class="rank-info">
                        <span class="tcolor">{{ $month['name'] }}</span>
                        <span class="ucolor txt-hide">回答数：{{ $month['extra']['count'] }}</span>
                    </div>
                </a>
                </li>
            @endforeach
        @else
            <div class="no-data">暂无相关信息</div>
        @endif
    </ul>
</div>

<script>
$('#J-rank-tab > span').hover(function(){
    $('#J-rank-tab > span').removeClass('active');
    $(this).addClass('active');

    $('.answer-rank-list').hide();
    $('#J-tab-'+$(this).attr('type')).show();
})
</script>