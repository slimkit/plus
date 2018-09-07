<template>
    <div class="panel panel-default">
        <div class="panel-heading">图片设置</div>
        <div class="panel-body">

            <div class="alert alert-warning">
                设置允许上传图片的尺寸信息，最小像素设置是为了避免用户上传过小图片影响布局，最大像素设置首先可以控制图片大小，其次第三方云储存均有最大尺寸限制。<br>
                如果超出最大像素，第三方将不会进行图片处理，导致原图直接吐出。<br>
                限制尺寸信息还有助于避免服务器错误，例如你是用 <code>local</code> 文件系统，进行图像裁剪的时候可以考虑到系统内存情况进行设置，不至于内存溢出。
            </div>

            <sb-ui-loading v-if="loading" />

            <form class="form-horizontal" v-else>
                <!-- 宽度设置 -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">宽度</label>
                    <div class="col-sm-4">
                        <input
                            type="number"
                            class="form-control"
                            placeholder="允许上传的最小像素尺寸"
                            v-model.number="width.min"
                            min="0"
                            :max="width.max"
                        >
                    </div>
                    <div class="col-sm-1 text-center">-</div>
                    <div class="col-sm-4">
                        <input
                            type="number"
                            class="form-control"
                            placeholder="允许上传的最大像素尺寸"
                            :min="width.min"
                            v-model.number="width.max"
                        >
                    </div>
                </div>

                <!-- 高度设置 -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">高度</label>
                    <div class="col-sm-4">
                        <input
                            type="number"
                            class="form-control"
                            placeholder="允许上传的最小像素尺寸"
                            v-model.number="height.min"
                            min="0"
                            :max="height.max"
                        >
                    </div>
                    <div class="col-sm-1 text-center">-</div>
                    <div class="col-sm-4">
                        <input
                            type="number"
                            class="form-control"
                            placeholder="允许上传的最大像素尺寸"
                            :min="height.min"
                            v-model.number="height.max"
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
        width: {
            min: 0,
            max: 0,
        },
        height: {
            min: 0,
            max: 0,
        },
        loading: true,
    }),
    methods: {
        submitHandler({ stopProcessing }) {
            request.patch(
                createRequestURI('file-storage/image-dimension'),
                {
                    width: this.width,
                    height: this.height,
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
    },
    created() {
        this.loading = true;
        request.get(
            createRequestURI('file-storage/image-dimension'),
            {
                validateStatus: status => status === 200
            }
        )
        .then(({ data: { width, height } }) => {
            this.width = width;
            this.height = height;
            this.loading = false;
        })
        .catch(({ response: { data: message = "获取图像尺寸设置失败，请检查网络或者刷新页面重试" } }) => {
            this.$store.dispatch("alert-open", { type: "danger", message });
        });
    }
}
</script>

