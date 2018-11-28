@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
@if (isset($temp))
    @foreach($rewards as $reward)
        <li>
            <a class="u-avatar" href="{{ route('pc:mine', $reward['user']['id']) }}">
                <img src="{{ getAvatar($reward['user'], 40) }}" class="lazy avatar" width="40"/>
                @if($reward['user']['verified'])
                <img class="role-icon" src="{{ $reward['user']['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                @endif
            </a>
            <a href="{{ route('pc:mine', $reward['user']['id']) }}" class="uname">{{ $reward['user']['name'] }}</a>
            <font color="#aaa">打赏了 {{ $app }}</font>
        </li>
    @endforeach
@else
    <div class="reward_popups">
        <p class="reward_title ucolor font14">打赏列表</p>
        <div class="reward_popups_con reward-box">
            <ul class="reward_list" id="J-reward-list">

            </ul>
        </div>
    </div>
    <script>
        var rewardType = "{{$type}}";
        var params = {type:rewardType, post_id:"{{$post_id}}", limit: 6, getinfo: true};
        loader.init({
            container: '#J-reward-list',
            loading: '.reward_list',
            url: '/reward/view',
            paramtype: 1,
            params: params,
            loadtype: 2,
        });
    </script>
@endif
