@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp
@if (!$reports->isEmpty())
    <table class="m-table m-table-row">
        @if ($loadcount == 1)
        <thead>
            <tr>
                <th>举报时间</th>
                <th>举报人</th>
                <th>举报内容</th>
                <th>举报来源</th>
                <th>操作</th>
            </tr>
        </thead>
        @endif
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{getTime($report->created_at)}}</td>
                    <td>{{$report->user->name}}</td>
                    <td><span class="s-fc3 f-pre">{{$report->content or ''}}</span></td>
                    <td>
                        @if ($report->type == 'post')
                            @if (isset($report->resource->summary))
                                <a class="s-fc" href="{{ route('pc:grouppost', [$report->group_id, $report->resource_id]) }}">
                                    帖子：{{str_limit($report->resource->summary, 12)}}
                                </a>
                            @else
                                帖子：该资源已被删除
                            @endif
                        @else
                        @php $body = $report->resource->body ?? '资源已被删除'; @endphp
                            评论：{{str_limit($body, 12)}}
                        @endif
                    </td>
                    <td>
                        @if (!$report->status)
                            <a class="f-mr10 s-fc" onclick="MAG.audit({{$report->id}}, 1);" href="javascript:;">通过</a>
                            <a class="s-fc" onclick="MAG.audit({{$report->id}}, 0);" href="javascript:;">驳回</a>
                        @elseif($report->status == 1)
                            <a class="f-mr10 s-fc2" href="javascript:;">已处理</a>
                        @elseif($report->status == 2)
                            <a class="f-mr10 s-fc2" href="javascript:;">被驳回</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif