@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatContent;
@endphp

@foreach ($mention as $item)
@php
    $user = array_first($users, function($user) use ($item) {return $user['id'] === $item['user_id'];})
@endphp
<li data-id="{{ $item['id'] }}" class="chat_mention_card">
    <img src="{{ getAvatar($user, 40) }}"/>
    <div class="mention_info">
        <span class="name">{{ $user['name'] }}</span>
        <span class="time">{{ getTime($item['created_at'], true, false) }}</span>
        @switch($item['resourceable']['type'])
            @case('feeds')
                <span class="content">{!! formatContent($item['feeds']['feed_content']) !!}</span>
                @break
        @endswitch
    </div>
</li>
@endforeach
