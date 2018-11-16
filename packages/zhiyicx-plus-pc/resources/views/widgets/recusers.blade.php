@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
@if (!empty($users))
<div class="recusers">
    <div class="u-tt">推荐用户</div>
    <ul>
        @foreach ($users as $user)
        <li>
            <a href="{{ route('pc:mine', $user['id']) }}">
                @if($user['verified'])
                    <img class="role-icon" src="{{ $user['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                @endif
                <img src="{{ getAvatar($user, 50) }}"/>
            </a>
            <span>
                <a href="{{ route('pc:mine', $user['id']) }}">{{ $user['name'] }}</a>
            </span>
        </li>
        @endforeach
    </ul>
    @if (count($users) == 9)
    <a class="recmore" href="{{ route('pc:users', ['type'=>3]) }}">更多推荐用户</a>
    @endif
</div>
@endif