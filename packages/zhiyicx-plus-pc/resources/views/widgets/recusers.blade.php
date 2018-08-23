@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
@if (!$users->isEmpty())
<div class="recusers">
    <div class="u-tt">推荐用户</div>
    <ul>
        @foreach ($users as $user)
        <li>
            <a href="{{ route('pc:mine', $user['id']) }}">
                <img src="{{ getAvatar($user, 50) }}"/>
                @if($user['verified'])
                    <img class="role-icon" src="{{ $user['verified']['icon'] or asset('assets/pc/images/vip_icon.svg') }}">
                @endif
            </a>
            <span>
                <a href="{{ route('pc:mine', $user['id']) }}">{{ $user['name'] }}</a>
            </span>
        </li>
        @endforeach
    </ul>
    @if ($users->count() == 9)
    <a class="recmore" href="{{ route('pc:users', ['type'=>3]) }}">更多推荐用户</a>
    @endif
</div>
@endif