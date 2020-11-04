@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
@foreach ($users as $data)
    <div class="user_item @if($loop->iteration % 2 == 0) user_item_right @endif">
        <div class="user_header">
            <a class="avatar_box" href="{{route('pc:mine',['user_id'=>$data['id']])}}">
                <img src="{{ getAvatar($data, 60) }}" class="user_avatar" alt="{{ $data['name'] }}"/>
                @if($data['verified'])
                    <img class="role-icon"
                         src="{{ $data['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                @endif
            </a>
        </div>
        <div class="user_body">
            <a href="{{route('pc:mine',['user_id'=>$data['id']])}}">
                <span class="user_name">{{ $data['name'] ?? $data['phone'] }}</span>
            </a>
            @if (($TS['id'] ?? 0) !== $data['id'])
                @if ($data['follower'])
                    <span id="data" class="follow_btn followed" uid="{{ $data['id'] }}" status="1">已关注</span>
                @else
                    <span id="data" class="follow_btn" uid="{{ $data['id'] }}" status="0">+关注</span>
                @endif
            @endif
            <div class="user_subtitle">{{ $data['bio'] ?? '这家伙很懒，什么都没留下'}}</div>
            <div class="user_number">
                <a href="{{ route('pc:follower', ['user_id' => $data['id']]) }}"
                   class="user_num">粉丝<span>{{ $data['extra']['followers_count'] ?? 0 }}</span></a>
                <a href="{{ route('pc:following', ['user_id' => $data['id']]) }}"
                   class="user_num right">关注<span>{{ $data['extra']['followings_count'] ?? 0 }}</span></a>
            </div>
        </div>
    </div>
@endforeach
