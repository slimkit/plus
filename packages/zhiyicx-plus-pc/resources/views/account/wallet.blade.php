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

        <div class="account_r" onclick="layer.alert(buyTSInfo)">
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
                        @if($config['bootstrappers']['wallet']['cash']['status'])
                            <span class="switch @if($type == 3) active @endif" type="3">提现记录</span>
                        @endif
                    </div>
                    <div class="wallet-body" id="wallet-info">
                        <div class="wallet-info clearfix">
                            <div class="remaining-sum"> </div>
                            <div class="operate">
                                @if($config['bootstrappers']['wallet']['recharge']['status'])
                                    <a href="javascript:;"><button>充值</button></a>
                                @endif
                                @if($config['bootstrappers']['wallet']['cash']['status'])
                                    <a href="javascript:;">
                                        <button class="gray">提现</button>
                                    </a>
                                    @endif
                            </div>
                            <p class="gcolor">开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：17311245680。</p>
                        </div>
                        <p>使用规则</p>
                        {{ $wallet['rule'] ?? ''}}
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

</script>
@endsection
