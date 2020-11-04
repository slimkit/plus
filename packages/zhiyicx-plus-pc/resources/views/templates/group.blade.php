@php
    use Illuminate\Support\Str;
    use Zhiyi\PlusGroup\Models\GroupMember;
    use Illuminate\Support\Str;
@endphp
@foreach ($group as $item)
    <div class="group_item @if($loop->iteration % 2 == 0) group_item_right @endif">
        <dl class="clearfix">
            <dt>
                <a href="{{Route('pc:groupread', $item['id'])}}" >
                    <img src="{{ $item['avatar'] ? $item['avatar']['url'] : asset('assets/pc/images/default_picture.png') }}" width="120" height="120">
                </a>
            </dt>
            <dd>
                <a class="title" href="{{Route('pc:groupread', $item['id'])}}" alt="{{ $item['name'] }}" >{{ Str::limit($item['name'], 16, '...') }}
                    @if ($item['mode'] == 'paid')
                    <span class="paid">付费</span>
                    @endif
                </a>
                <div class="tool">
                    <span>帖子 <font class="mcolor">{{ $item['posts_count'] }}</font></span>
                    <span>成员 <font class="mcolor" id="join-count-{{ $item['id'] }}">{{ $item['users_count'] }}</font></span>
                </div>
                <div class="join">
                    @if ($item['joined'])
                        @if ($item['joined']['role'] == 'administrator')
                            <span class="role" >管理员</span>
                        @elseif ($item['joined']['role'] == 'founder')
                            <span class="role" >圈主</span>
                        @else
                             <button
                                class="J-join joined"
                                gid="{{$item['id']}}"
                                state="1"
                                mode="{{$item['mode']}}"
                                money="{{$item['money']}}"
                            >已加入</button>
                        @endif
                    @else
                        @php
                            $joined = (bool) GroupMember::where('user_id', $TS['id'])->where('group_id', $item['id'])->exists();
                        @endphp

                        @if ($joined)
                            <button class="J-join joined" >待审核</button>
                        @else
                            <button
                                class="J-join"
                                gid="{{$item['id']}}"
                                state="0"
                                mode="{{$item['mode']}}"
                                money="{{$item['money']}}"
                            >+加入</button>
                        @endif
                    @endif
                </div>
            </dd>
        </dl>
    </div>
@endforeach
