@section('title')
    我的钱包
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
            <div class="account_c_c" id="J-warp">
                {{-- 我的钱包 --}}
                <div class="account_tab">
                    <div class="perfect_title">
                        <span class="switch @if($type == 1) active @endif" type="1">我的钱包</span>
                        <span class="zy_select t_c gap12">
                            <span @if($type == 2) class="active" @endif>交易明细</span>
                            <ul>
                                <li @if($type == 2) class="active" @endif data-value="1">全部</li>
                                <li data-value="2">收入</li>
                                <li data-value="3">支出</li>
                            </ul>
                            <i></i>
                        </span>
                        @if($config['bootstrappers']['wallet:cash']['open'])
                            <span class="switch @if($type == 3) active @endif" type="3">提现记录</span>
                        @endif
                    </div>
                    <div class="wallet-body" id="wallet-info">
                        <div class="wallet-info clearfix">
                            <div class="remaining-sum">
                                {{ ((int) $TS['newWallet']['balance']) / 100 }}</div>
                            <div class="operate">
                                @if($config['bootstrappers']['wallet:recharge']['open'])
                                    <a href="javascript:;" data-url="{{ route('pc:walletpay') }}" onclick="checkWallet(this)"><button>充值</button></a>
                                @endif
                                @if($config['bootstrappers']['wallet:cash']['open'])
                                    <a href="{{ route('pc:walletdraw') }}">
                                        <button class="gray">提现</button>
                                    </a>
                                    @endif
                            </div>
                            <p class="gcolor">账户余额（元）</p>
                        </div>
                        <p>使用规则</p>
                        {{ $wallet['rule'] or ''}}
                    </div>

                    <div class="wallet-body" id="wallet-records">
                    </div>
                </div>
                {{-- /我的钱包 --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var wallet = {!! json_encode($wallet) !!};
    $(function(){
        var type = {{ $type }};

        // 点击切换分类
        $('.perfect_title .switch').click(function(){
            switchType($(this).attr('type'));
            $(this).parents('.perfect_title').find('span').removeClass('active');
            $(this).addClass('active');
        })

        $('.zy_select li').click(function(){
            var cate = $(this).data('value');
            $(this).parents('.perfect_title').find('span').removeClass('active');
            $(this).addClass('active');
            $('.zy_select span').addClass('active');
            switchType(2, cate);
        })

        switchType(type);
    })

    // 切换类型加载数据
    var switchType = function(type, cate){
        cate = cate || 0;
        $('.loading').hide();
        $('.click_loading').hide();
        if (type == 1) { // 我的钱包
            $('#wallet-records').html('').hide();
            $('#wallet-info').show();
        } else {
            $('#wallet-info').hide();
            $('#wallet-records').html('').show();

            var params = {
                type: type,
                new: 1
            }
            if (cate != 0) params.cate = cate;
            loader.init({
                container: '#wallet-records',
                loading: '#wallet-records',
                url: '/settings/wallet/records',
                params: params
            });
        }
    };

    // 充值检测
    var checkWallet = function (obj) {
        if (wallet['recharge_type'] && $.inArray('alipay_pc_direct', wallet['recharge_type']) != -1) {
            var url = $(obj).data('url');
            window.location.href = url;
        } else {
            noticebox('未配置支付环境', 0);
            return false;
        }
    };
</script>
@endsection