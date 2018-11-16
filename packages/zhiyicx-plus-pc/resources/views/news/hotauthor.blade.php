@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
<div class="infR_top">
    <div class="itop_autor">热门作者</div>
    <div id="j-author-hot-wrapp">
        <div class="R_list hots_author">
        @if (!empty($author))
            @foreach ($author as $user)
                <div class="fl">
                    <a href="{{ Route('pc:mine',['user_id'=>$user]['user']['id']]) }}">
                        <img src="{{ getAvatar($user['user']) }}" />
                    </a>
                </div>
                <div class="i_right">
                    <span><a href="{{ route('pc:mine',['user_id'=>$user['user']['id']]) }}">{{$user['user']['name']}}</a></span>
                    <p class="bio">{{ $user['user']['bio'] ?? '暂无简介信息' }}</p>
                </div>
            @endforeach
        @else
            <div class="loading">暂无相关信息</div>
        @endif
        </div>
    </div>
</div>