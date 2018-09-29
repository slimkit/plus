<template>
    <div class="panel panel-default">
        <div class="panel-heading">支付验证密码</div>
        <div class="panel-body">
            <sb-ui-loading v-if="loading" />
            <div class="form-horizontal" v-else>
                <div class="form-group">
                    <label class="col-sm-2 control-label">开关</label>
                    <div class="col-sm-4">
                        <sb-ui-button
                            class="btn btn-primary"
                            :label="setting ? '关闭' : '开启'"
                            @click="changeSwitchHandler"
                        >
                        </sb-ui-button>
                    </div>
                    <div class="col-sm-6 help-block">
                        默认关闭，如果开启那么用户发生付款行为需要用户输入登录密码进行安全验证，关闭后泽不需要输入密码验证！
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import request, { createRequestURI } from '../../../util/request';
export default {
    data: () => ({
        loading: false,
        setting: false,
    }),
    created() {
        this.loading = true;
        request.get(createRequestURI('setting/security/pay-validate-password'), {
            validateStatus: status => status === 200,
        }).then(({ data }) => {
            this.setting = data.switch || false;
            this.loading = false;
        }).catch(() => {
            this.$store.dispatch('alert-open', { type: 'danger', message: '获取配置失败，请联系技术人员！' });
        });
    },
    methods: {
        changeSwitchHandler({ stopProcessing }) {
            request.put(
                createRequestURI('setting/security/pay-validate-password'),
                { switch: !this.setting },
                {
                    validateStatus: status => status === 204
                }
            ).then(() => {
                this.setting = ! this.setting;
                this.submiting = false;
                stopProcessing();
            }).catch(() => {
                this.$store.dispatch('alert-open', { type: 'danger', message: '更新配置失败，请联系技术人员！' });
                stopProcessing();
            });
        }
    }
}
</script>

