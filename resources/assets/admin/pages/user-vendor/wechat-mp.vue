<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7">
                <div class="panel panel-default">
                    <div class="panel-heading">微信公众平台</div>
                    <div class="panel-body">
                        <sb-ui-loading v-if="loading" />
                        <form class="form-horizontal" v-else>

                            <!-- app id -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">APP ID</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" v-model="form.appid">
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
                        请前往「<a target="_blank" href="https://mp.weixin.qq.com/">微信公众平台</a>」进行申请。
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { wechatMp } from '../../api/vendor';
export default {
    data: () => ({
        form: {
            appid: '',
            secret: '',
        },
        loading: true,
    }),
    methods: {
        onSubmit(event) {
            wechatMp.update(this.form).then(() => {
                this.$store.dispatch("alert-open", { type: "success", message: '提交成功' });
            }).catch(({ response: { data: message = "提交失败，请刷新页面重试！" } }) => {
                this.$store.dispatch("alert-open", { type: "danger", message });
            }).finally(event.stopProcessing);
        }
    },
    created() {
        wechatMp.get().then(({ data }) => {
            this.loading = false;
            this.form = data;
        }).catch(({ response: { data: message = "获取失败，请刷新页面重试！" } }) => {
            this.$store.dispatch("alert-open", { type: "danger", message });
        });
    }
}
</script>

