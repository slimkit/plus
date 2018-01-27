<template>
    <div class="panel panel-default">

        <div class="panel-heading">积分基础配置</div>
        
        <div class="panel-body">
            
            <ui-loading v-if="loading"></ui-loading>

            <div class="form-horizontal" v-else="loading">
                <div class="form-group">
                    <label class="control-label col-xs-2">充值选项</label>
                    <div class="col-xs-4">
                        <input type="text" class="form-control" placeholder="充值选项，人民币分单位" v-model="config['recharge-options']">
                    </div>
                    <div class="col-xs-6 help-block">
                        充值选项，人民币分单位，多个用半角,分割
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">兑换比例</label>
                    <div class="col-xs-4">
                        <input type="number" class="form-control" placeholder="兑换比例，人民币一分钱可兑换的积分数量" v-model="config['recharge-ratio']">
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
                      <ui-button type="button" class="btn btn-primary" @click="handleSubmit"></ui-button>
                    </div>
                    <div class="col-xs-6 help-block"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import request, {createRequestURI} from '../../util/request';
    export default {
        data: () => ({
            loading: false,
            config:{},
        }),
        methods: {
            handleSubmit({ stopProcessing }) {
                request.patch(
                  createRequestURI('currency/config'), this.config,
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
        },
        created() {
            this.loading = true;
            request.get(createRequestURI('currency/config'), {
                validateStatus: status => status === 200,
            }).then(({data}) => {
                this.loading = false;
                let conf = this.config;
                conf['cash-min'] = data['cash-min'];
                conf['cash-max'] = data['cash-max'];
                conf['recharge-min'] = data['recharge-min'];
                conf['recharge-max'] = data['recharge-max'];
                conf['recharge-options'] = data['recharge-options'];
                conf['recharge-ratio'] = data['recharge-ratio'];
            }).catch(({response: {data = {message: '获取失败'}} = {}}) => {
                this.loading = false;
                this.$store.dispatch('alert-open', {type: 'danger', message: data});
            });
        }
    };
</script>
