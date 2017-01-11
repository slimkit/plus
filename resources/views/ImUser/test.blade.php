<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
    </head>
    <body>

    </body>
</html>
<script type="text/javascript" src="{{ elixir('js/app.js') }}"></script>
<script type="text/javascript">
	$(function(){
		$.ajax({
			url:" {{ route('imuser.create') }}",
			type: 'POST',
			dataType: 'json',
			data: {param1: 'value1'}
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});


	});
</script>
