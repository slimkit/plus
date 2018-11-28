@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@if ($type == 2)
    {{-- 交易记录 --}}
    @if(!empty($records))
    <div class="wallet-table">
        <table class="table tborder" border="0" cellspacing="0" cellpadding="0">
            @if ($loadcount == 1)
            <thead>
                <tr>
                    <th width="20%">时间</th>
                    <th width="40%">名称</th>
                    <th width="20%">状态</th>
                    <th width="20%">金额</th>
                </tr>
            </thead>
            @endif
            <tbody>
                @foreach ($records as $item)
                <tr>
                    <td width="20%">{{ getTime($item['created_at']) }}</td>
                    <td width="40%"><p class="ptext">
                        {{ $item['target_type'] == 'widthdraw' ? '提现' : ($item['target_type'] == 'user' ? '转账' : $item['body']) }}
                        </p></td>
                    <td width="20%">
                        @if ($item['state'] == 0) 等待 @endif
                        @if ($item['state'] == 1) 成功 @endif
                        @if ($item['state'] == -1) 失败 @endif
                    </td>
                    <td width="20%">
                        <font color="#FF9400">{{ $item['type']=='1' ? '+'.sprintf("%.2f", $item['amount'] / 100) : '-'.sprintf("%.2f", $item['amount'] / 100) }}</font>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
@elseif ($type == 3)
    {{-- 提现记录 --}}
    @if(!empty($records))
    <div class="wallet-table">
        <table class="table tborder" border="0" cellspacing="0" cellpadding="0">
            @if ($loadcount == 1)
            <thead>
                <tr>
                    <th width="20%">时间</th>
                    <th width="50%">备注</th>
                    <th width="15%">金额</th>
                    <th width="15%">状态</th>
                </tr>
            </thead>
            @endif
            <tbody>
                @foreach ($records as $item)
                <tr>
                    <td width="20%">{{ getTime($item['created_at']) }}</td>
                    <td width="50%"><p class="ptext">{{ $item['type'] == 'alipay' ? '支付宝' : '微信' }}账户提现</p></td>
                    <td width="15%"><font color="#FF9400">{{ sprintf("%.2f", $item['value'] / 100) }}</font></td>
                    <td width="15%">
                        @if ($item['status'] == 0) 待审批 @endif
                        @if ($item['status'] == 1) 已审批 @endif
                        @if ($item['status'] == 2) 拒绝 @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
@endif