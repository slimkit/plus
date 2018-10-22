@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp
@if ($type == 2)
    {{-- 积分明细 --}}
    @if(!empty($currency))
        <div class="wallet-table">
            <table class="table tborder" border="0" cellspacing="0" cellpadding="0">
                @if ($loadcount == 1)
                    <thead>
                    <tr>
                        <th width="30%">时间</th>
                        <th width="30%">途径</th>
                        <th width="20%">状态</th>
                        <th width="20%">数量</th>
                    </tr>
                    </thead>
                @endif
                <tbody>
                @foreach ($currency as $item)
                    <tr>
                        <td width="30%">{{ getTime($item['created_at']) }}</td>
                        <td width="30%"><p class="ptext">{{ $item['title'] }}</p></td>
                        <td width="20%">
                            @if ($item['state'] == 0) 等待 @endif
                            @if ($item['state'] == 1) 成功 @endif
                            @if ($item['state'] == -1) 失败 @endif
                        </td>
                        <td width="20%">
                            <font color="#FF9400">{{ $item['type'] == 1 ? '+' : '-' }}{{ $item['amount']}}{{ $config['bootstrappers']['site']['currency_name']['name'] }}</font>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@elseif ($type == 3)
    {{-- 充值记录 --}}
    @if(!empty($currency))
        <div class="wallet-table">
            <table class="table tborder" border="0" cellspacing="0" cellpadding="0">
                @if ($loadcount == 1)
                    <thead>
                    <tr>
                        <th width="30%">时间</th>
                        <th width="40%">状态</th>
                        <th width="30%">数量</th>
                    </tr>
                    </thead>
                @endif
                <tbody>
                @foreach ($currency as $item)
                    <tr @if($item['state'] != 1) class="color_gray" @endif>
                        <td width="30%">{{ getTime($item['created_at']) }}</td>
                        <td width="40%">
                            @if ($item['state'] == 0) 等待 @endif
                            @if ($item['state'] == 1) 成功 @endif
                            @if ($item['state'] == -1) 失败 @endif
                        </td>
                        <td width="30%">
                            <p class="ptext"><font @if($item['state'] == 1) color="#FF9400" @endif>{{ $item['type'] == 1 ? '+' : '-' }}{{ $item['state'] == -1 ? 0 : $item['amount']}}{{ $config['bootstrappers']['site']['currency_name']['name'] }}</font></p>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@elseif ($type == 4)
    {{-- 提取记录 --}}
    @if(!empty($currency))
        <div class="wallet-table">
            <table class="table tborder" border="0" cellspacing="0" cellpadding="0">
                @if ($loadcount == 1)
                    <thead>
                    <tr>
                        <th width="30%">时间</th>
                        <th width="40%">状态</th>
                        <th width="30%">数量</th>
                    </tr>
                    </thead>
                @endif
                <tbody>
                @foreach ($currency as $item)
                    <tr @if($item['state']  != 1) class="color_gray" @endif>
                        <td width="30%">{{ getTime($item['created_at']) }}</td>
                        <td width="40%">
                            @if ($item['state'] == 0) 等待 @endif
                            @if ($item['state']  == 1) 成功 @endif
                            @if ($item['state']  == -1) 失败 @endif
                        </td>
                        <td width="30%">
                            <p class="ptext"><font @if($item['state']  == 1) color="#FF9400" @endif>{{ $item['type'] == 1 ? '+' : '-' }}{{ $item['amount']}}{{ $config['bootstrappers']['site']['currency_name']['name'] }}</font></p>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@else
    @if($currency)
        <div class="rules">
            {{$currency['rule']}}
        </div>
    @endif
@endif
