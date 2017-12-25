@extends('list')
<link type="text/css" rel="stylesheet" href="/assets/music/css/fileinput.min.css" />
@section('nav')
    <ul class="nav nav-tabs">
        <li role="presentation"><a href="{{ route('music:list') }}">音乐</a></li>
        <li role="presentation"><a href="{{ route('music:special') }}">专辑</a></li>
        <li role="presentation" class="active"><a href="javascript:">歌手</a></li>
        <li role="presentation"><a href="{{ route('music:all:comments') }}">评论</a></li>
    </ul>
@endsection

@section('content')
	<div class="panel panel-default">
        <div class="panel-heading">
            更新歌手信息
            <a href="javascript:history.back();" class="btn btn-link pull-right btn-xs" role="button">
                <span class="glyphicon glyphicon-plus"></span>
                返回上一页
            </a>
        </div>
    </div>
    <form class="form-horizontal" method="post" action="{{ route('music:singers:update', ['singer' => $singer->id]) }}">
    	{{ csrf_field() }}
    	<input type="hidden" name="_method" value="patch" />
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">歌手名</label>
            <div class="col-sm-6">
                <input type="text" name="name" class="form-control" value="{{ $singer->name }}" placeholder="歌手名">
            </div>
        </div>
        <div class="form-group">
        	<label for="" class="col-sm-2 control-label">歌手封面</label>
        	<div class="col-sm-6">
        		<input type="file" name="file" class="projectfile" id="projectfile" accept="image/*" data-preview-file-type="image" value="" />
        		<input type="hidden" name="cover" id="storage" />
            	<p class="text-info">支持[jpeg,jpg,gif,png,bmp]，大小不超过1m</p>
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
				showUpload : true,
                showRemove : true,
                language : 'zh',
                allowedPreviewTypes: ['image'],
                allowedFileExtensions:  ['jpeg', 'jpg', 'gif', 'png', 'webp', 'bmp'],
                uploadExtraData: {
                	_token: '{{ csrf_token() }}'
                },
                maxFileSize : 1024,
                uploadUrl: "{{ route('special:storage') }}",
                dropZoneEnabled: false,
                minFileCount: 1,
                maxFileCount: 1,
                initialPreview: [
                    "{{ $singer->storageUrl }}"
                ],
                initialPreviewAsData: true,
                initialPreviewFileType: 'image',
                initialPreviewConfig: [
                    {caption: "{{ $singer->file->origin_filename }}", width: "{{ intval($singer->file->width) }}"}
                ],
			});
			$("#projectfile").on("fileuploaded", function(event, data, previewId, index) {
				$("#storage").val(data.response.id);
				$(this).val('');
			});

			$('#uploadfile').on('fileerror', function(event, data, msg) {
				alert(msg);
			});
		});
	</script>
@endsection