<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7">
                <div class="panel panel-default">
                    <div class="panel-heading">环信配置</div>
                    <div class="panel-body">

                        <sb-ui-loading v-if="loading" />

                        <form class="form-horizontal" v-else>
                            <!-- 开关 -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">开关</label>
                                <div class="col-sm-9">
                                    <select class="form-control" v-model="form.open">
                                        <option :value="true">开启</option>
                                        <option :value="false">关闭</option>
                                    </select>
                                    <span class="help-block">开启或者关闭即时聊天</span>
                                </div>
                            </div>

                            <!-- 应用 Key -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">App Key</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" v-model="form.appKey">
                                    <span class="help-block">请填写创建应用后的 App Key</span>
                                </div>
                            </div>

                            <!-- Clien ID -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Client ID</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" v-model="form.clientId">
                                </div>
                            </div>

                            <!-- Clien ISecretD -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Client Secret</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" v-model="form.clientSecret">
                                </div>
                            </div>

                            <!-- 注册方式 -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">注册方式</label>
                                <div class="col-sm-9">
                                    <select class="form-control" v-model="form.registerType">
                                        <option :value="0">开放注册</option>
                                        <option :value="1">授权注册</option>
                                    </select>
                                    <span class="help-block">请正确的选择你应用使用的注册方式</span>
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
                        环信配置用于用户间的即时聊天，你需要去「<a target="_blank" href="https://www.easemob.com/">环信官网</a>」注册帐号、创建应用后将应用信息填入次页。
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { easemob } from '../../api/vendor';
export default {
    data: () => ({
        form: {
            open: false,
            appKey: '',
            clientId: '',
            clientSecret: '',
            registerType: 0,
        },
        loading: true,
    }),
    methods: {
        onSubmit(event) {
            easemob.update(this.form).then(() => {
                this.$store.dispatch("alert-open", { type: "success", message: '提交成功' });
            }).catch(({ response: { data: message = "提交失败，请刷新页面重试！" } }) => {
                this.$store.dispatch("alert-open", { type: "danger", message });
            }).finally(event.stopProcessing);
        }
    },
    created() {
        easemob.get().then(({ data }) => {
            this.loading = false;
            this.form = data;
        }).catch(({ response: { data: message = "获取失败，请刷新页面重试！" } }) => {
            this.$store.dispatch("alert-open", { type: "danger", message });
        });
    }
}
</script>

