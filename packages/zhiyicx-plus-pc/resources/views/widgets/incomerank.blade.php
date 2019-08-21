@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
@if(!empty($incomes))
<div class="income-rank">
    <div class="title">收入达人排行榜</div>
    <ul class="income-list">
            @foreach($incomes as $income)
                <li>
                    <div class="fans-span">{{$loop->iteration}}</div>
                    <div class="income-avatar">
                        <a href="{{ route('pc:mine', $income['id']) }}">
                            <img src="{{ getAvatar($income, 60) }}" alt="{{$income['name']}}">
                            @if($income['verified'])
                                <img class="role-icon" src="{{ $income['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                            @endif
                        </a>
                    </div>
{{--                    <div class="income-name">--}}
{{--                        <a class="name" href="{{ route('pc:mine', $income['id']) }}">{{$income['name']}}</a>--}}
{{--                        <div class="answers-count">回答数：{{$income['extra']['answers_count']}}</div>--}}
{{--                    </div>--}}
                </li>
            @endforeach
    </ul>
</div>
@endif