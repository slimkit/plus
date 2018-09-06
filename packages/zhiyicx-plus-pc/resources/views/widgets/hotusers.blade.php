@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
<div class="hot_users clearfix">
    <div class="hot_users_title">
        热门用户
    </div>
    <ul>
        @foreach($users as $user)
        <li>
            <div class="hot_user_avatar avatar_box">
                <a href="{{ route('pc:mine', $user['id']) }}">
                    <img src="{{ getAvatar($user, 60) }}">
                    @if($user['verified'])
                        <img class="role-icon" src="{{ $user['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                    @endif
                </a>
            </div>
            <div class="hot_user_info">
                <a href="{{ route('pc:mine', $user['id']) }}"><span class="hot_user_name">{{ $user['name'] }}</span></a>
                <div class="hot_user_intro">{{ $user['bio'] ?? '这家伙很懒，什么都没留下'}}</div>
            </div>
        </li>
        @endforeach
    </ul>
</div>