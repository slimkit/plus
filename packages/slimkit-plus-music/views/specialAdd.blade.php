@extends('list')
<link type="text/css" rel="stylesheet" href="/assets/music/css/fileinput.min.css" />
@section('nav')
    <ul class="nav nav-tabs">
        <li role="presentation"><a href="{{ route('music:list') }}">音乐</a></li>
        <li role="presentation" class="active"><a href="javascript:">专辑</a></li>
        <li role="presentation"><a href="{{ route('music:singers') }}">歌手</a></li>
        <li role="presentation"><a href="{{ route('music:all:comments') }}">评论</a></li>
    </ul>
@endsection

@section('content')
	<div class="panel panel-default">
        <div class="panel-heading">
            添加专辑
            <a href="javascript:history.back();" class="btn btn-link pull-right btn-xs" role="button">
                <span class="glyphicon glyphicon-plus"></span>
                返回上一页
            </a>
        </div>
    </div>
    <form class="form-horizontal" method="post" action="{{ route('special:store') }}">
    	{{ csrf_field() }}
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">专辑名</label>
            <div class="col-sm-6">
                <input type="text" name="title" class="form-control" value="" placeholder="专辑名">
            </div>
        </div>
        <div class="form-group">
        	<label for="" class="col-sm-2 control-label">专辑封面</label>
        	<div class="col-sm-6">
        		<input type="file" name="file" class="projectfile" id="projectfile" accept="image/*" data-preview-file-type="image" value="" />
        		<input type="hidden" name="storage" id="storage" />
            	<p class="text-info">支持[jpeg,jpg,gif,png,bmp]，大小不超过1m</p>
        	</div>
        </div>
        <div class="form-group">
        	<label for="sort" class="col-sm-2 control-label">专辑权重</label>
        	<div class="col-sm-6">
        		<input type="number" name="sort" value="0" class="form-control" placeholder="专辑权重, 排序越大越靠前" />
        	</div>
        </div>
        <div class="form-group">
        	<label for="sort" class="col-sm-2 control-label">专辑简介</label>
        	<div class="col-sm-6">
        		<textarea name="intro" id="intro" class="form-control" rows="5" placeholder="专辑简介, 50字以内"></textarea>
        	</div>
        </div>
        <div class="form-group">
        	<label for="" class="col-sm-2 control-label">付费开关</label>
        	<div class="btn-group col-sm-6" data-toggle="buttons">
			  	<label class="btn btn-primary active">
			    	<input type="checkbox" value="free" checked class="paid_node no-pay" name="paid_node[]" checked="" autocomplete="off"> 免费
			  	</label>
				<label class="btn btn-primary">
			    	<input type="checkbox" value="need-pay" class="paid_node pay" name="paid_node[]" autocomplete="off"> 收费
			  	</label>
			</div>
        </div>
		<div class="form-group">
			<label for="" class="col-sm-2 control-label">收费金额</label>
			<div class="col-sm-6">
				<input type="number" id="amount" name="amount" disabled class="form-control" placeholder="金额单位: 元">
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
                dropZoneEnabled: false
			});
			$("#projectfile").on("fileuploaded", function(event, data, previewId, index) {
				$("#storage").val(data.response.id);
				$(this).val('');
			});

			$('#uploadfile').on('fileerror', function(event, data, msg) {
				alert(msg);
			});

			$('.paid_node').on('change', function() {
				var value = $(this).val();
				if (value === 'need-pay') {
					$("#amount").attr('disabled',false);
					$(".no-pay").removeAttr('checked').removeClass('active');
					$(".no-pay").parent().removeClass('active');
				} else {
					$(this).addClass('active').attr('checked');
					$("#amount").attr('disabled', true);
					$(".pay").removeClass('active')
					$(".pay").removeAttr('checked').parent().removeClass('active');
				}
			})
		});
	</script>
@endsection