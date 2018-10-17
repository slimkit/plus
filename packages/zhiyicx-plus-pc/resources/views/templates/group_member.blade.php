@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
@if (!empty($members))
    @if ($type == 'audit')
        <div class="f-mt20">
            <div id="member-box"> </div>
            @if (!empty($members))
            @foreach ($members as $member)
                <dl class="m-row">
                    <dt>
                        <img src="{{ getAvatar($member['user'], 50) }}" width="50" class="avatar">
                        @if($member['user']['verified'])
                            <img class="role-icon" src="{{ $member['user']['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                        @endif
                    </dt>
                    <dd><div>{{$member['user']['name']}}</div>
                        <div class="u-opt">
                            <span>管理</span>
                            <svg class="icon f-fs2"><use xlink:href="#icon-setting"></use></svg>
                            <ul class="u-menu f-dn">
                                <a href="javascript:;" onclick="MAG.audit({{$group['id']}}, {{$member['id']}}, 1);"><li>通过</li></a>
                                <a href="javascript:;" onclick="MAG.audit({{$group['id']}}, {{$member['id']}}, 2);"><li>驳回</li></a>
                            </ul>
                        </div>
                    </dd>
                </dl>
            @endforeach
            @else
                <p class="no-member">暂无成员</p>
            @endif
        </div>
    @elseif($type == 'blacklist')
        <div class="f-mt20">
            <div id="member-box"> </div>
            @if (!empty($members))
            @foreach ($members as $member)
                <dl class="m-row">
                    <dt>
                        <img src="{{ getAvatar($member['user'], 50) }}" width="50" class="avatar">
                        @if($member['user']['verified'])
                            <img class="role-icon" src="{{ $member['user']['verified']['icon'] ?? asset('assets/pc/images/vip_icon.svg') }}">
                        @endif
                    </dt>
                    <dd><div>{{$member['user']['name']}}</div>
                        <div class="u-opt">
                            <span>管理</span>
                            <svg class="icon f-fs2"><use xlink:href="#icon-setting"></use></svg>
                            <ul class="u-menu f-dn">
                                <a href="javascript:;" onclick="MAG.black({{$group['id']}}, {{$member['id']}}, 0);"><li>移除黑名单</li></a>
                                <a href="javascript:;" onclick="MAG.delete({{$group['id']}}, {{$member['id']}});"><li>踢出圈子</li></a>
                            </ul>
                        </div>
                    </dd>
                </dl>
            @endforeach
            @else
                <p class="no-member">暂无成员</p>
            @endif
        </div>
    @endif
@endif