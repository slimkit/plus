<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>移动端版本管理</title>
</head>
<body>
	<div id="app"></div>
	
	<script>
		window.Version = {
			'token': "{{$token}}",
			'api': "{{ url('api/v2') }}",
			'base_url': "{{ url('/') }}",
			'csrf_token': "{{ csrf_token() }}"
		};
	</script>
	<script src="{{ mix('js/main.js', 'assets/appversion') }}"></script>
</body>
</html>