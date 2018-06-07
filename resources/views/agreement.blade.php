<!DOCTYPE html>
<html>
    <head>
      <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
        <title>注册协议</title>
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                /*font-weight: 100;*/
                font-family: 'Lato';
                color: #333;
            }

            .container {
                text-align: center;
                margin-top: 20px;
                vertical-align: middle;
            }

            .content {
                max-width:90%;
                display: inline-block;
            }
            .content p{
              text-align: left;
              word-wrap: break-word;
              font-size: 16px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                {!! $content !!}
            </div>
        </div>
    </body>
</html>
