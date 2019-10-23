@extends('list')
<link type="text/css" rel="stylesheet" href="/assets/music/css/fileinput.min.css"/>
@section('nav')
    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="javascript:">音乐</a></li>
        <li role="presentation"><a href="{{ route('music:special') }}">专辑</a></li>
        <li role="presentation"><a href="{{ route('music:singers') }}">歌手</a></li>
        <li role="presentation"><a href="{{ route('music:all:comments') }}">评论</a></li>
    </ul>
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            添加歌曲
            <a href="javascript:history.back();" class="btn btn-link pull-right btn-xs" role="button">
                <span class="glyphicon glyphicon-plus"></span>
                返回上一页
            </a>
        </div>
    </div>
    <form class="form-horizontal" method="post" action="{{ route('music:store') }}">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">歌曲名称</label>
            <div class="col-sm-6">
                <input type="text" maxlength="20" name="title" class="form-control" value="{{$old['title'] ?? ''}}"
                       placeholder="歌曲名称">
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">上传歌曲</label>
            <div class="col-sm-6">
                <input type="file" placeholder="{{isset($old['storage']) ? '已上传，无需再次上传' : ''}}" name="file"
                       class="projectfile" id="projectfile" accept="audio/*" data-preview-file-type="audio" value=""/>
                <input type="hidden" name="storage" value="{{$old['storage'] ?? 0}}" id="storage"/>
                <p class="text-info">支持MP3格式文件，大小不超过10m</p>
            </div>
        </div>
        <div class="form-group">
            <label for="sort" class="col-sm-2 control-label">歌曲权重</label>
            <div class="col-sm-6">
                <input type="number" name="sort" value="{{$old['sort'] ?? 0}}" class="form-control"
                       placeholder="歌曲权重, 越大越靠前"/>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">付费开关</label>
            <div class="btn-group col-sm-6" data-toggle="buttons">
                <label class="btn btn-primary active">
                    <input type="checkbox" value="free" checked class="paid_node no-pay" name="paid_node[]" checked=""
                           autocomplete="off"> 免费
                </label>
                <label class="btn btn-primary">
                    <input type="checkbox" value="download" class="paid_node pay" name="paid_node[]" autocomplete="off">
                    收费
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">收费金额</label>
            <div class="col-sm-6">
                <input type="number" value="{{$old['download'] ?? ''}}" id="download-pay" name="download" disabled=""
                       class="form-control" placeholder="金额单位: 元">
            </div>
        </div>
        <div class="form-group">
            <label for="listen" class="col-sm-2 control-label">歌曲长度</label>
            <div class="col-sm-6">
                <input type="number" id="last_time" value="{{$old['last_time'] ?? 0}}" name="last_time"
                       class="form-control" placeholder="单位: 秒">
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">所属专辑</label>
            <div class="btn-group col-sm-6" data-toggle="buttons">
                @foreach ($specials as $special)
                    <label
                        class="btn btn-primary {{isset($old['special']) ? (in_array($special->id, $old['special']) ? 'active' : '') : ''}}">
                        <input {{isset($old['special']) ? (in_array($special->id, $old['special']) ? 'checked' : '') : ''}}
                               type="checkbox" name="special[]" value="{{ $special->id }}"
                               autocomplete="off"> {{ $special->title }}
                    </label>
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">歌手</label>
            <div class="btn-group col-sm-6" data-toggle="buttons">
                <!-- <label for="" class="btn btn-primary">
                    <input type="radio" name="singer"> 无歌手
                </label> -->
                @foreach ($singers as $singer)
                    <label
                        class="btn btn-primary {{isset($old['singer']) ? ((int)$old['singer'] == $singer->id ? 'active' : '') : ''}}">
                        <input type="radio"
                               {{isset($old['singer']) ? ($singer->id == $old['singer'] ? 'checked' : '') : ''}}
                               value="{{ $singer->id }}" name="singer" autocomplete="off"> {{ $singer->name }}
                    </label>
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="lyric">歌词</label>
            <div class="col-sm-6">
                <textarea name="lyric" class="form-control" rows="5"
                          placeholder="请输入歌词">{{$old['lyric'] ?? ''}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-6 col-sm-offset-2">
                <button type="submit" class="btn btn-default btn-primary">添加</button>
            </div>
        </div>
    </form>
    <script type="text/javascript" src="/assets/music/js/fileinput.js"></script>
    <script type="text/javascript" src="/assets/music/js/zh.js"></script>
    <script>
        $(function () {
            $("#projectfile").fileinput({
                showUpload: true,
                showRemove: true,
                language: 'zh',
                allowedPreviewTypes: ['audio'],
                allowedFileExtensions: ['mp3'],
                uploadExtraData: {
                    _token: '{{ csrf_token() }}'
                },
                maxFileSize: 100000,
                uploadUrl: "{{ route('music:storage') }}",
                dropZoneEnabled: false,
            });
            $("#projectfile").on("fileuploaded", function (event, data, previewId, index) {
                $("#storage").val(data.response.id);
                $(this).val('');
            });

            $('#uploadfile').on('fileerror', function (event, data, msg) {
                alert(msg);
            });

            $('.paid_node').on('change', function () {
                var value = $(this).val();
                if (value === 'download') {
                    $("#" + value + "-pay").attr('disabled', false).parent().addClass('active');
                    $(".no-pay").removeAttr('checked');
                    $(".no-pay").parent().removeClass('active');
                } else {
                    $(this).addClass('active').attr('checked');
                    $("#download-pay").val('');
                    $("#download-pay").attr('disabled', true);
                    $(".pay").removeAttr('checked').parent().removeClass('active');
                }
            });
        });
    </script>
@endsection
