@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp
@if (!$notifications->isEmpty())
    <ul class="tz-cont">
        @foreach($notifications as $notification)
            <li>
                <div class="tz-content">
                    {{$notification['data']['content']}}
                </div>
                <div class="tz-date">{{ getTime($notification['created_at']) }}</div>
            </li>
        @endforeach
    </ul>
@else
    暂无更多
@endif