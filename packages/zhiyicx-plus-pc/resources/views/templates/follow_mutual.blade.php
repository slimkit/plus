@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
@foreach ($users as $item)
<li data-id="{{ $item['id'] }}">
    <img src="{{ getAvatar($item) }}"/>
    <span>{{ $item['name'] }}</span>
    <svg class="icon chat_del_user_btn hide" aria-hidden="true">'
        <use xlink:href="#icon-choosed"></use>
    </svg>
    <svg class="icon chat_add_user_btn" aria-hidden="true">
        <use xlink:href="#icon-liaotiantubiao_9"></use>
    </svg>
</li>
@endforeach