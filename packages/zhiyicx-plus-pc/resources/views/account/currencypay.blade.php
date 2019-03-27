@section('title') 充值 @endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/account.css')}}"/>
@endsection

@section('content')

<div class="pay-box">
    <div class="pay-title">
        <h1 class="title"> 充值 </h1>
        <span id="open">
            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-doubt"></use></svg>
            用户充值协议
        </span>
    </div>
    <div class="pay-form">
        <p class="tcolor">充值比率</p>
        <p><font color="#FF9400">1元 = {{$currency['recharge-ratio'] * 100}}{{ $config['bootstrappers']['site']['currency_name']['name'] }}</font></p>
        <p class="tcolor">设置充值金额</p>
        <div class="pay-curr">
            @if($currency['recharge-options'])
                @foreach ($currency['recharge-options'] as $item)
                    <label class="opt">¥{{$item / 100}}<input class="hide"" type="radio" name="sum" value="{{$item}}"></label>
                @endforeach
            @endif
        </div>

        <p><input min="1" oninput="value=moneyLimit(value)" onkeydown="if(!isNumber(event.keyCode)) return false;" type="number" class="custom-sum" name="custom" placeholder="自定义充值金额"></p>

        <p class="tcolor">选择充值方式</p>
        <div class="pay-way">
            <label for="alipay" class="active"><img src="{{ asset('assets/pc/images/pay_pic_zfb_on.png') }}"/></label>
            <input class="hide" id="alipay" type="radio" name="payway" value="AlipayWapOrder" checked>
            @if($config['bootstrappers']['wallet']['transform-currency'])
                <label for="wallet"><img src="{{ asset('assets/pc/images/pay_pic_wallet_on.png') }}"/></label>
                <input class="hide" id="wallet" type="radio" name="payway" value="wallet_pc_direct">
            @endif
        </div>

        <button type="submit" class="pay-btn" id="J-pay-btn">充值</button>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
var popInterval;
$('.pay-curr label').on('click', function(){
    $('.pay-curr label').removeClass('active');
    $(this).addClass('active');
    $('input[name="custom"]').val('');
});
$(document).on('focus keyup change', 'input[name="custom"]', function() {
    $('.pay-curr label').removeClass('active');
});

// 用户充值协议
$('#open').on('click', function () {
    var html = '<div class="out">';
    html += '<div class="agreement">';
    html += '  <div class="title">';
    html += '  <h3>用户充值协议</h3>';
    html += '  </div>';
    html += '    <div class="agreement-info">';
    html += '<p class="info">{{$currency['recharge-rule']}}</p>';
    html += '</div>';
    html += ' </div>';
    html += '</div>';
    ly.loadHtml(html,'','520','440');
});

$('input[name="custom"]').on('focus, change, keyup', function(){
    $('.pay-sum label').removeClass('active');
    $('[name="sum"]').removeAttr('checked');
})

$('.pay-way label').on('click', function(){
    $('.pay-way label').removeClass('active');
    $(this).addClass('active');
})

$('#J-pay-btn').on('click', function(){
    var _this = $(this);
    _this.attr("disabled", true);
    var sum = $('[name="sum"]:checked').val();
    var payway = $('[name="payway"]:checked').val();
    var custom = $('[name="custom"]').val();
    if (sum == undefined && custom == '') {
        _this.attr("disabled", false);
        noticebox('请输入或选择充值金额', 0);
        return;
    }

    if (payway == 'wallet_pc_direct') {
        var params = {
            amount: sum ? sum : custom * 100,
        };
        axios.post('/api/v2/plus-pay/transform', params)
        .then(function (response) {
            noticebox('充值成功', 1, "/settings/currency");
        })
        .catch(function (error) {
            showError(error.response.data);
            _this.attr("disabled", false);
        });
    } else {
        var params = {
            type: payway,
            from: 1,
            url: 1,
            redirect: "{{ route('pc:currency') }}",
            amount: sum ? sum : custom * 100,
        }

        axios.post('/api/v2/currencyRecharge/orders', params)
        .then(function (response) {
            window.location.href = response.data;
        })
        .catch(function (error) {
            showError(error.response.data);
            _this.attr("disabled", false);
        });
    }
});

// function payPop(id){
//     popInterval = setInterval("checkStatus("+id+")", 3000);
//     var html = '<div class="tip">'+
//                     '<p>请您在新打开的支付页面完成付款</p>'+
//                     '<p>付款前请不要关闭此窗口</p>'+
//                 '</div><div class="msg">完成付款后请根据您的情况点击下面的按钮。</div>';
//     layer.confirm(html, {
//       move: false,
//       id: 'pay_tip_pop',
//       title: '充值提示',
//       btn: ['支付成功','遇到问题'],
//       cancel: function(){
//         clearInterval(popInterval);
//       }
//     }, function(){
//         checkStatus(id, 1);
//     }, function(){
//         return false;
//     });
//     $('#J-pay-btn').attr("disabled", false);
// }

// function checkStatus(id, type) {
//     type = type || 0;
//     if (!id) { return; }
//     axios.get('/api/v2/currency/orders/'+id)
//       .then(function (response) {
//         if (response.data.status == 1) {
//             window.location.href = TS.SITE_URL + '/success?status=1&url={{route('pc:currencypay')}}&time=3&message=充值成功';
//         }
//         if (type == 1) {
//             window.location.href = TS.SITE_URL + '/success?status=0&url={{route('pc:currencypay')}}&time=3&message=充值失败或正在处理中&content=操作失败';
//         }
//       })
//       .catch(function (error) {
//         showError(error.response.data);
//       });
// }
</script>
@endsection
