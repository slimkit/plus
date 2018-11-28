<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7">
                <div class="panel panel-default">
                    <div class="panel-heading">微博登录</div>
                    <div class="panel-body">
                        <sb-ui-loading v-if="loading" />
                        <form class="form-horizontal" v-else>

                            <!-- App id -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">App ID</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" v-model="form.appId">
                                </div>
                            </div>

                            <!-- Secret -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Secret</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" v-model="form.secret">
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
                        新浪微博登录登录需要配置的数据，请前往「<a target="_blank" href="http://open.weibo.com/">微博·开放平台</a>」进行申请。
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { weibo } from '../../api/vendor';
export default {
    data: () => ({
        form: {
            appId: '',
            secret: '',
        },
        loading: true,
    }),
    methods: {
        onSubmit(event) {
            weibo.update(this.form).then(() => {
                this.$store.dispatch("alert-open", { type: "success", message: '提交成功' });
            }).catch(({ response: { data: message = "提交失败，请刷新页面重试！" } }) => {
                this.$store.dispatch("alert-open", { type: "danger", message });
            }).finally(event.stopProcessing);
        }
    },
    created() {
        weibo.get().then(({ data }) => {
            this.loading = false;
            this.form = data;
        }).catch(({ response: { data: message = "获取失败，请刷新页面重试！" } }) => {
            this.$store.dispatch("alert-open", { type: "danger", message });
        });
    }
}
</script>

