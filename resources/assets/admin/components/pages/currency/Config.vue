<template>
    <div class="panel panel-default">

        <div class="panel-heading">配置</div>
        
        <div class="panel-body">
            
            <ui-loading v-if="loading"></ui-loading>

            <div class="form-horizontal" v-else>
                <div class="form-group">
                    <label class="control-label col-xs-2">积分规则</label>
                    <div class="col-xs-4">
                        <textarea class="form-control" v-model="currency.rule"></textarea>
                    </div>
                    <div class="col-xs-6 help-block">
                        积分规则
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">积分提现开关</label>
                    <div class="col-xs-4">
                        <label class="radio-inline">
                            <input type="radio" :value="radio.on" v-model="currency.cash.status"> 开启
                        </label>
                        <label class="radio-inline">
                            <input type="radio" :value="radio.off" v-model="currency.cash.status"> 关闭
                        </label>
                    </div>
                    <div class="col-xs-6 help-block">
                        积分提现开关
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">积分提现规则</label>
                    <div class="col-xs-4">
                        <textarea class="form-control" v-model="currency.cash.rule"></textarea>
                    </div>
                    <div class="col-xs-6 help-block">
                        积分提现规则
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">积分充值开关</label>
                    <div class="col-xs-4">
                        <label class="radio-inline">
                            <input type="radio" :value="radio.on" v-model="currency.recharge.status"> 开启
                        </label>
                        <label class="radio-inline">
                            <input type="radio" :value="radio.off" v-model="currency.recharge.status"> 关闭
                        </label>
                    </div>
                    <div class="col-xs-6 help-block">
                        积分充值开关
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">积分充值规则</label>
                    <div class="col-xs-4">
                        <textarea class="form-control" v-model="currency.recharge.rule"></textarea>
                    </div>
                    <div class="col-xs-6 help-block">
                        积分充值规则
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-xs-2"></label>
                    <div class="col-xs-4">
                      <ui-button type="button" class="btn btn-primary btn-block" @click="handleCurrencySubmit"></ui-button>
                    </div>
                    <div class="col-xs-6 help-block"></div>
                </div>

                <div class="form-group">
                    <label class="control-label col-xs-2">充值选项</label>
                    <div class="col-xs-4">
                        <input type="text" class="form-control" placeholder="充值选项，人民币分单位" v-model="config['recharge-option']">
                    </div>
                    <div class="col-xs-6 help-block">
                        充值选项，人民币分单位，多个用半角,分割
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">兑换比例</label>
                    <div class="col-xs-4">
                        <input type="number" class="form-control" placeholder="兑换比例，人民币一分钱可兑换的积分数量" v-model="config['recharge-ratio']" readonly>
                    </div>
                    <div class="col-xs-6 help-block">
                        兑换比例，人民币一分钱可兑换的积分数量，默认1:1
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">单笔最小提现额度</label>
                    <div class="col-xs-4">
                        <input type="number" class="form-control" placeholder="单笔最小提现额度" v-model="config['cash-min']">
                    </div>
                    <div class="col-xs-6 help-block">
                        用户单笔最小提现额度
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">单笔最高提现额度</label>
                    <div class="col-xs-4">
                        <input type="number" class="form-control" placeholder="单笔最高提现额度" v-model="config['cash-max']">
                    </div>
                    <div class="col-xs-6 help-block">
                        用户单笔最高提现额度
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">单笔最小充值额度</label>
                    <div class="col-xs-4">
                        <input type="number" class="form-control" placeholder="单笔最小充值额度" v-model="config['recharge-min']">
                    </div>
                    <div class="col-xs-6 help-block">
                        用户单笔最小充值额度
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">单笔最高充值额度</label>
                    <div class="col-xs-4">
                        <input type="number" class="form-control" placeholder="单笔最高充值额度" v-model="config['recharge-max']">
                    </div>
                    <div class="col-xs-6 help-block">
                        用户单笔最高充值额度
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-xs-2"></label>
                    <div class="col-xs-4">
                      <ui-button type="button" class="btn btn-primary btn-block" @click="handleSubmit"></ui-button>
                    </div>
                    <div class="col-xs-6 help-block"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import request, {createRequestURI} from '../../../util/request';
    export default {
        data: () => ({
            loading: false,
            radio: {
                on: true,
                off: false
            },
            config:{},
            currency: {
                rule: null,
                cash: { 
                    status: true,
                    rule: null,
                },
                recharge: {
                    status: true,
                    rule: null,
                }
            }
        }),
        methods: {
            handleSubmit({ stopProcessing }) {
                request.patch(
                  createRequestURI('currency/config?type=detail'), this.config,
                { validateStatus: status => status === 201}
                )
                .then(({data}) => {
                    stopProcessing();
                    this.$store.dispatch('alert-open', {type: 'success', message: data});
                }).catch(({response: {data = {message: '获取失败'}} = {}}) => {
                    stopProcessing();
                    this.$store.dispatch('alert-open', {type: 'danger', message: data});
                });
            },
            handleCurrencySubmit({ stopProcessing }) {
                request.patch(
                  createRequestURI('currency/config?type=basic'), this.currency,
                { validateStatus: status => status === 201}
                )
                .then(({data}) => {
                    stopProcessing();
                    this.$store.dispatch('alert-open', {type: 'success', message: data});
                }).catch(({response: {data = {message: '更新失败'}} = {}}) => {
                    stopProcessing();
                    this.$store.dispatch('alert-open', {type: 'danger', message: data});
                });
            }
        },
        created() {
            this.loading = true;
            request.get(createRequestURI('currency/config'), {
                validateStatus: status => status === 200,
            }).then(({data: { basic_conf, detail_conf }}) => {
                this.loading = false;
                let conf = this.config;
                let curr = this.currency;
                // 基础配置
                curr.rule = basic_conf['rule'];
                curr.cash.rule = basic_conf['cash.rule'];
                curr.cash.status = basic_conf['cash.status'];
                curr.recharge.rule = basic_conf['recharge.rule'];
                curr.recharge.status = basic_conf['recharge.status'];
                // 详细配置
                conf['cash-min'] = detail_conf['cash-min'];
                conf['cash-max'] = detail_conf['cash-max'];
                conf['recharge-min'] = detail_conf['recharge-min'];
                conf['recharge-max'] = detail_conf['recharge-max'];
                conf['recharge-option'] = detail_conf['recharge-options'];
                conf['recharge-ratio'] = detail_conf['recharge-ratio'];

            }).catch(({response: {data = {message: '获取失败'}} = {}}) => {
                this.loading = false;
                this.$store.dispatch('alert-open', {type: 'danger', message: data});
            });
        }
    };
</script>
