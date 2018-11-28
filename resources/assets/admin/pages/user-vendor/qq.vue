<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7">
                <div class="panel panel-default">
                    <div class="panel-heading">QQ 登录</div>
                    <div class="panel-body">
                        <sb-ui-loading v-if="loading" />
                        <form class="form-horizontal" v-else>

                            <!-- App ID -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">App ID</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" v-model="form.appId">
                                </div>
                            </div>

                            <!-- App Key -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">App Key</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" v-model="form.appKey">
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
                        腾讯 QQ 登录需要配置的数据，请前往「<a target="_blank" href="https://connect.qq.com">QQ 互联</a>」进行申请。
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { qq } from '../../api/vendor';
export default {
    data: () => ({
        form: {
            appId: '',
            appKey: '',
        },
        loading: true,
    }),
    methods: {
        onSubmit(event) {
            qq.update(this.form).then(() => {
                this.$store.dispatch("alert-open", { type: "success", message: '提交成功' });
            }).catch(({ response: { data: message = "提交失败，请刷新页面重试！" } }) => {
                this.$store.dispatch("alert-open", { type: "danger", message });
            }).finally(event.stopProcessing);
        }
    },
    created() {
        qq.get().then(({ data }) => {
            this.loading = false;
            this.form = data;
        }).catch(({ response: { data: message = "获取失败，请刷新页面重试！" } }) => {
            this.$store.dispatch("alert-open", { type: "danger", message });
        });
    }
}
</script>

