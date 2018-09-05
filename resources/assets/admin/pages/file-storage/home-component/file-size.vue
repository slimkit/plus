<template>
    <div class="panel panel-default">
        <div class="panel-heading">文件大小</div>
        <div class="panel-body">

            <div class="alert alert-warning">
                设置文件最小限制，有助于限制用户上传过小的非法文件，例如 <code>1px * 1px</code> 这类图片，当然图片有像素单独限制，
                但是无法排除用户进行文件伪造上传，所以文件限制是第二道防护关卡。<br>
                而文件最大限制，是为了防止用户仅无限制的大型文件上传以浪费文件储存空间！你的系统最大允许 <code>{{ system.max }} Byte</code>
                ，所以最大不建议超出这个值！不正确的文件上传大小限制，将会影响到正常用户的上传！请配置适合你应用的限制信息。
            </div>

            <sb-ui-loading v-if="loading" />

            <form class="form-horizontal" v-else>
                <!-- 宽度设置 -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">限制字节</label>
                    <div class="col-sm-4">
                        <input
                            type="number"
                            class="form-control"
                            placeholder="允许上传的最小像素尺寸"
                            v-model.number="size.min"
                            min="0"
                            :max="size.max"
                        >
                    </div>
                    <div class="col-sm-1 text-center">-</div>
                    <div class="col-sm-4">
                        <input
                            type="number"
                            class="form-control"
                            placeholder="允许上传的最大像素尺寸"
                            :min="size.min"
                            v-model.number="size.max"
                        >
                    </div>
                </div>

                <!-- 提交按钮 -->
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <ui-button type="button" class="btn btn-primary" @click="submitHandler" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import request, { createRequestURI } from '../../../util/request';
export default {
    data: () => ({
        size: {
            min: 0,
            max: 0,
        },
        system: {
            max: 0,
        },
        loading: true,
    }),
    created() {
        this.loading = true;
        request.get(
            createRequestURI('file-storage/file-size'),
            {
                validateStatus: status => status === 200
            }
        )
        .then(({ data: { size, system } }) => {
            this.size = size;
            this.system = system;
            this.loading = false;
        })
        .catch(({ response: { data: message = "获取文件大小设置失败，请检查网络或者刷新页面重试" } }) => {
            this.$store.dispatch("alert-open", { type: "danger", message });
        });
    },
    methods: {
        submitHandler({ stopProcessing }) {
            request.patch(
                createRequestURI('file-storage/file-size'),
                {
                    size: this.size
                },
                {
                    validateStatus: status => status === 204,
                }
            )
            .then(() => {
                this.$store.dispatch("alert-open", {
                    type: "success",
                    message: "更新成功！"
                });
            })
            .catch(({ response: { data: message = "提交失败！" } }) => {
                this.$store.dispatch("alert-open", { type: "danger", message });
            })
            .finally(stopProcessing);
        }
    }
}
</script>

