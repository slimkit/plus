@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
@if(!empty($post))
    @foreach($post as $postk=>$postv)
        <div rel="{{$genre}}div" @if($postk > 8) current="1" @endif>
            <li>
                @if(isset($postv['extra']))
                    <div class="fans_span1"><span @if($postv['extra']['rank'] <= 3) class="blue" @else class="grey" @endif>{{$postv['extra']['rank']}}</span></div>
                    <div class="fans_span2 txt-hide">
                        <a href="{{ route('pc:mine', ['user_id'=>$postv['id']]) }}">
                            <img src="{{ getAvatar($postv, 30) }}" class="fans_img" />
                            @if($postv['verified'])
                                <img class="role-icon" src="{{ $postv['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                            @endif
                        </a>
                        <a href="{{ route('pc:mine', ['user_id'=>$postv['id']]) }}">{{$postv['name']}}</a>
                    </div>
                    @if($tabName !== '')
                        <div class="fans_span3">{{$postv['extra']['count'] ?? 0}}</div>
                    @endif
                @endif
            </li>
        </div>
    @endforeach
@endif