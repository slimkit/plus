@section('title') 充值 @endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/account.css')}}"/>
@endsection

@section('content')

<div class="pay-box">
    <h1 class="title"> 充值 </h1>
    <div class="pay-form">

        <p class="tcolor">设置充值金额</p>
        @if ($wallet && isset($wallet['labels']))
        <div class="pay-sum">
            @foreach ($wallet['labels'] as $label)
            <label class="opt">¥{{ $label / 100 }}<input class="hide" type="radio" name="sum" value="{{ $label }}"></label>
            @endforeach
        </div>
        @endif

        <p><input min="1" oninput="value=moneyLimit(value)" class="custom-sum" type="number" name="custom" placeholder="自定义充值金额"></p>

        <p class="tcolor">选择充值方式</p>
        <div class="pay-way">
            <img src="{{ asset('assets/pc/images/pay_pic_zfb_on.png') }}"/>
            <input class="hide" id="alipay" type="radio" name="payway" value="AlipayWapOrder" checked>
        </div>

        <button type="submit" class="pay-btn" id="J-pay-btn">充值</button>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
var popInterval;
$('.pay-sum label').on('click', function(){
    $('.pay-sum label').removeClass('active');
    $(this).addClass('active');

    $('input[name="custom"]').val('');
})

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
    var params = {
        type: payway,
        from: 1,
        url: 1,
        redirect: "{{ route('pc:wallet') }}",
        amount: sum ? sum : custom * 100,
    }

    axios.post('/api/v2/walletRecharge/orders', params)
    .then(function (response) {
        window.location.href = response.data;
        // payPop(response.data.id);
    })
    .catch(function (error) {
        showError(error.response.data);
        _this.attr("disabled", false);
    });
});

// function payPop(id){
//     popInterval = setInterval("checkStatus("+id+")", 3000);

//     var html = '<div class="tip">'+
//                     '<p>请您在新打开的支付页面完成付款</p>'+
//                     '<p>付款前请不要关闭此窗口</p>'+
//                 '</div><div class="msg">完成付款后请根据您的情况点击下面的按钮。</div>';
//     layer.confirm(html, {
//         move: false,
//         id: 'pay_tip_pop',
//         title: '充值提示',
//         btn: ['支付成功','遇到问题'],
//         cancel: function(){
//         clearInterval(popInterval);
//         }
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
//     axios.get('/api/v2/wallet/charges/'+id+'?mode=retrieve')
//     .then(function (response) {
//         if (response.data.status == 1) {
//             window.location.href = TS.SITE_URL + '/success?status=1&url={{route('pc:wallet')}}&time=3&message=充值成功';
//         }
//         if (type == 1) {
//             window.location.href = TS.SITE_URL + '/success?status=0&url={{route('pc:wallet')}}&time=3&message=充值失败或正在处理中&content=操作失败';
//         }
//     })
//     .catch(function (error) {
//         showError(error.response.data);
//     });
// }
</script>
@endsection