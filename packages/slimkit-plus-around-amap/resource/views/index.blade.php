<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>附近的人配置</title>
	<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
	<nav class="navbar navbar-default">
	  	<div class="container-fluid">
	    	<div class="navbar-header">
	    		<a class="navbar-brand" href="#">附近的人</a>
	      		<p class="navbar-text navbar-right">设置高德地图</a></p>
	    	</div>
	  	</div>
	</nav>
	<div class="container">
		<form action="{{ route('around-amap:admin-save') }}"  method="post">
			{{ csrf_field() }}
		  <div class="form-group">
		    <label for="amap-key">高德WEB服务KEY</label>
		    <input type="text" class="form-control" value="{{ $key }}" name="amap-key" id="amap-key" placeholder="应用管理中的key">
		  </div>
		  <div class="form-group">
		    <label for="amap-sig">应用密钥</label>
		    <input type="text" class="form-control" value="{{ $sig }}" id="amap-sig" name="amap-sig" placeholder="在应用设置中获取">
		  </div>
		  <div class="form-group">
		    <label for="amap-tableid">自定义地图tableid</label>
		    <input type="text" class="form-control" value="{{ $tableid }}" id="amap-tableid" name="amap-tableid" placeholder="在自定义地图的管理台获取">
		  </div>
		  <div class="form-group">
		    <label for="amap-tableid">jssdk链接</label>
		    <input type="text" class="form-control" value="{{ $jssdk }}" id="amap-jssdk" name="amap-jssdk" placeholder="网页端需要的jssdk，用于控制访问协议">
		  </div>
		  <button type="submit" class="btn btn-default">提交设置</button>
		</form>
		<br />
		@if (session('status'))
	        <div class="alert alert-success alert-dismissible fade in" role="alert">
		      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
		      {{ session('status') }}
		    </div>
	    @endif
	    @if (count($errors) > 0)
	        @foreach ($errors->all() as $error)
	            <div class="alert alert-danger alert-dismissible fade in" role="alert">
			      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			      {{ $error }}
			    </div>
	        @endforeach
	    @endif
	</div>

	<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript">
        $(function(){
            setTimeout( function() {
            	$('.bg-success').remove();
            }, 2000);
        })
    </script>
</body>
</html>