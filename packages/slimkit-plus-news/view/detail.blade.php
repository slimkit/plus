<!DOCTYPE html>
<html>
<head>
    <title>{{$title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<style type="text/css">
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    html, body {
      font-family: -apple-system-font,"Helvetica Neue","PingFang SC","Hiragino Sans GB","Microsoft YaHei",sans-serif;
    }
    .wrap {
      width: 100%;
      height: auto;
      padding: 20px 15px 15px;
      background-color: #fff;
    }

    .wrap .title {
      margin-bottom: 10px;
      line-height: 1.4;
      font-weight: 400;
      font-size: 21px;
      text-align: center;
      color: #59b6d7;
    }

    .wrap .content {
      width: 100%;
      max-width: 100%;
      height: auto;
      overflow-x: hidden;
      color: #3e3e3e;
      text-align: justify;
    }
    .content img{max-width:100%!important;}
  </style>
<body>
<div class="wrap">
  <h2 class="title">{{$title}}</h2>
  <div class="content">
  {!! $content !!}
  </div>
</div>
</body>
</html>