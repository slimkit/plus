@section('title')
    我的{{ $config['bootstrappers']['site']['currency_name']['name'] }}
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
                <div class="account_c_a" id="J-warp">
                    <div class="account_tab">
                        <div class="perfect_title">
                            <span class="switch @if($type == 1) active @endif" type="1">我的{{ $config['bootstrappers']['site']['currency_name']['name'] }}</span>
                            <span class="switch @if($type == 2) active @endif" type="2">{{ $config['bootstrappers']['site']['currency_name']['name'] }}明细</span>
                            <span class="switch @if($type == 3) active @endif" type="3">充值记录</span>
                            <span class="switch @if($type == 4) active @endif" type="4">提取记录</span>
                        </div>
                        <div class="wallet-body" id="wallet-info">
                            <div class="currency-info clearfix">
                                <div class="remaining-sum"></div>
                                <div class="operate">
                                    @if($config['bootstrappers']['currency']['recharge']['status'])
                                        <a href="javascript:;">
                                            <button>充值</button>
                                        </a>
                                    @endif
                                    @if($config['bootstrappers']['currency']['cash']['status'])
                                    <a href="javascript:;">
                                        <button class="gray">提取</button>
                                    </a>
                                    @endif
                                </div>
                                <p class="gcolor">开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：17311245680。</p>
                            </div>
                            @if($type==1)
                                <p>{{ $config['bootstrappers']['site']['currency_name']['name'] }}规则</p>
                                <div class="rules">
                                    {{$config['bootstrappers']['currency']['rule']}}
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
        var currency = {!! json_encode($config['bootstrappers']['currency']) !!};
        $(function(){
            var type = {{ $type }};
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
    </script>
@endsection
