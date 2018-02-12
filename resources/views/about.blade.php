<!DOCTYPE html>
<html>
    <head>
      <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
        <title>关于我们</title>
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
                <img src="{{ asset('plus.png') }}" width="30%" alt="ThinkSNS+">
                <p>T君知道,大家对现在的版本非常不满意</p>
                <p>T君正在加班加点, 一定给大家一个满意的答案</p>
                <p>希望各位测试君以邮件形式，以图+文的形式反馈发现的问题，记住是问题哦，不是增加哪些功能的各种鸡汤建议哦，那样我会吐的，不小心就把你关在小黑屋了！
                最最重要一点，T君喜欢美丽的测试数据，不要发我不喜欢的哦，否则我会删！删！删！</p>
                <p>BUG和优化建议请发送至邮箱</p>
                <p>发送邮箱：lihecong@zhishisoft.com</p>
                <p>咨询电话：18108035545</p>    
                <p>业务QQ：3298713109</p>
            </div>
        </div>
    </body>
</html>
