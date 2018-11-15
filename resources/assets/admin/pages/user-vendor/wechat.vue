<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7">
                <div class="panel panel-default">
                    <div class="panel-heading">微信登录</div>
                    <div class="panel-body">
                        <sb-ui-loading v-if="loading" />
                        <form class="form-horizontal" v-else>

                            <!-- App Key -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">App Key</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" v-model="form.appKey">
                                </div>
                            </div>

                            <!-- App Secret -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">App Secret</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" v-model="form.appSecret">
                                </div>
                            </div>

                            <!-- 提交按钮 -->
                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <ui-button type="button" class="btn btn-primary" @click="onSubmit" />
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="panel panel-default">
                    <div class="panel-heading">帮助</div>
                    <div class="panel-body">
                        微信登录登录需要配置的数据，请前往「<a target="_blank" href="https://open.weixin.qq.com/">微信开发平台</a>」进行申请。
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { wechat } from '../../api/vendor';
export default {
    data: () => ({
        form: {
            appKey: '',
            appSecret: '',
        },
        loading: true,
    }),
    methods: {
        onSubmit(event) {
            wechat.update(this.form).then(() => {
                this.$store.dispatch("alert-open", { type: "success", message: '提交成功' });
            }).catch(({ response: { data: message = "提交失败，请刷新页面重试！" } }) => {
                this.$store.dispatch("alert-open", { type: "danger", message });
            }).finally(event.stopProcessing);
        }
    },
    created() {
        wechat.get().then(({ data }) => {
            this.loading = false;
            this.form = data;
        }).catch(({ response: { data: message = "获取失败，请刷新页面重试！" } }) => {
            this.$store.dispatch("alert-open", { type: "danger", message });
        });
    }
}
</script>

