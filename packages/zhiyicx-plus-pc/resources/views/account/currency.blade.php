@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp
@section('title')
    我的积分
@endsection

@extends('pcview::layouts.default')

@section('bgcolor')style="background-color:#f3f6f7"@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/pc/css/account.css')}}"/>
@endsection

@section('content')

    <div class="account_container">
        <div class="account_wrap">

            {{-- 左侧导航 --}}
            @include('pcview::account.sidebar')

            <div class="account_r">
                <div class="account_c_a" id="J-warp">
                    <div class="account_tab">
                        <div class="perfect_title">
                            <span class="switch @if($type == 1) active @endif" type="1">我的积分</span>
                            <span class="switch @if($type == 2) active @endif" type="2">积分明细</span>
                            <span class="switch @if($type == 3) active @endif" type="3">充值记录</span>
                            <span class="switch @if($type == 4) active @endif" type="4">提取记录</span>
                        </div>
                        <div class="wallet-body" id="wallet-info">
                            <div class="currency-info clearfix">
                                <div class="remaining-sum">{{ $TS['currency']['sum'] or 0 }}</div>
                                <div class="operate">
                                    @if($config['bootstrappers']['currency:recharge']['open'])
                                        <a href="{{ route('pc:currencypay') }}">
                                            <button>充值</button>
                                        </a>
                                    @endif
                                    @if($config['bootstrappers']['currency:cash']['open'])
                                    <a href="{{ route('pc:currencydraw') }}">
                                        <button class="gray">提取</button>
                                    </a>
                                    @endif
                                </div>

                                <p class="gcolor">当前积分</p>
                            </div>
                            @if($type==1)
                                <p>积分规则</p>
                                <div class="rules">
                                    {{$currency['rule']}}
                                </div>
                            @endif
                        </div>
                        <div class="wallet-body" id="wallet-records">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var currency = {!! json_encode($currency) !!};
        $(function(){
            var type = {{ $type }};
            // 点击切换分类
            $('.perfect_title .switch').click(function(){
                switchType($(this).attr('type'));
                $(this).parents('.perfect_title').find('span').removeClass('active');
                $(this).addClass('active');
            })
            switchType(type);
        })

        // 切换类型加载数据
        var switchType = function(type){
            $('.loading').hide();
            $('.click_loading').hide();
            if (type == 1) { // 我的钱包
                $('#wallet-records').html('').hide();
                $('#wallet-info').show();
                $('.currency_list').show();
            } else {
                $('#wallet-info').hide();
                $('.currency_list').hide();
                $('#wallet-records').html('').show();

                var params = {
                    type: type,
                    new: 1
                }
                loader.init({
                    container: '#wallet-records',
                    loading: '#wallet-records',
                    url: '/settings/currency/record',
                    params: params
                });
            }
        };

        // 充值检测
        var checkWallet = function (obj) {
            if (currency['recharge_type'] && $.inArray('alipay_pc_direct', currency['recharge_type']) != -1) {
                var url = $(obj).data('url');
                window.location.href = url;
            } else {
                noticebox('未配置支付环境', 0);
                return false;
            }
        };
    </script>
@endsection