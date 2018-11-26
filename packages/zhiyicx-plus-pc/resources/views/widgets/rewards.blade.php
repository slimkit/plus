@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
@if(isset($config['bootstrappers']['site']['reward']['status']) && $config['bootstrappers']['site']['reward']['status'])
    <div class="reward_cont">
        <p><button class="btn btn-warning btn-lg" onclick="rewarded.show({{ $rewards_id }}, '{{ $rewards_type }}')">打 赏</button></p>
        <p class="reward_info tcolor">
            <span style="color: #F76C6A; ">{{ $rewards_info['count'] }} </span>人打赏，共
            <span style="color: #F76C6A; ">{{ $rewards_info['amount'] ?? 0 }} </span>{{ $config['bootstrappers']['site']['currency_name']['name'] }}
        </p>

        {{-- 打賞 --}}
        @if (!empty($rewards_data) && count($rewards_data))
            <div class="reward_user">
                @foreach ($rewards_data as $key => $reward)
                    @if ($key < 10)
                        <a href="{{ route('pc:mine', $reward['user']['id']) }}" class="user_item">
                            <img class="lazy round" src="{{ getAvatar($reward['user'], 42) }}" width="42" />
                            @if ($reward['user']['verified'])
                                <img class="verified_icon" src="{{ $reward['user']['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                            @endif
                        </a>
                    @endif
                @endforeach
                <span class="more_user" onclick="rewarded.list({{ $rewards_id }}, '{{ $rewards_type }}')"></span>
            </div>
        @endif
    </div>
@endif
