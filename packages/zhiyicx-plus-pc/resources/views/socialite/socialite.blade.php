<html>
<head>
    <script>
        setTimeout('redirect()', 500); //跳转
        function redirect() {
            var status = "{{$status}}";
            if (status == 1) {
                window.opener.getToken();
                window.close();
            } else if (status == -1) {
                var other_type = "{{$data['other_type'] ?? ''}}";
                var access_token = "{{$data['access_token'] ?? ''}}";
                var name = "{{$data['name'] ?? ''}}";
                window.opener.toBind(other_type, access_token, name);
                window.close();
            }
        }
    </script>
</head>
<body>{{$message}}</body>
</html>