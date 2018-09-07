<template>
    <div class="panel panel-default">
        <div class="panel-heading">阿里云 OSS</div>
        <div class="panel-body">
            <sb-ui-loading v-if="loading" />

            <div class="form-horizontal" v-else>

                <!-- Access Key Id -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">AccessKeyID</label>
                    <div class="col-sm-3">
                        <input
                            class="form-control"
                            type="text"
                            placeholder="请输入 AccessKeyID"
                            v-model="form.accessKeyId"
                        >
                    </div>
                    <div class="col-sm-7 help-block">
                        请输入你的阿里云 AccessKeyID
                    </div>
                </div>

                <!-- AccessKeySecret -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">AccessKeySecret</label>
                    <div class="col-sm-3">
                        <input
                            class="form-control"
                            type="text"
                            placeholder="请输入 AccessKeySecret"
                            v-model="form.accessKeySecret"
                        >
                    </div>
                    <div class="col-sm-7 help-block">
                        请输入你的阿里云 AccessKeySecret
                    </div>
                </div>

                <!-- bucket -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Bucket</label>
                    <div class="col-sm-3">
                        <input
                            class="form-control"
                            type="text"
                            placeholder="请输入 Bucket 名称"
                            v-model="form.bucket"
                        >
                    </div>
                    <div class="col-sm-7 help-block">
                        请输入你的 OSS Bucket 名称
                    </div>
                </div>

                <!-- ACL -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">读写权限</label>
                    <div class="col-sm-3">
                        <select class="form-control" v-model="form.acl">
                            <option value="private">私有</option>
                            <option value="public-read">公共读</option>
                            <option value="public-read-write">公共读写</option>
                        </select>
                    </div>
                    <div class="col-sm-7 help-block">
                        OSS ACL 提供 Bucket 级别的权限访问控制, 了解<a target="_blank" href="https://https://help.aliyun.com/document_detail/31954.html">读写权限设置</a>。
                        <br> 私有：对文件的所有访问操作需要进行身份验证。
                    </div>
                </div>

                <!-- 过期时间 -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">过期时间</label>
                    <div class="col-sm-3">
                        <input
                            type="number"
                            class="form-control"
                            placeholder="请输入过期时长..."
                            v-model.number="form.timeout"
                            min="0"
                        >
                    </div>
                    <div class="col-sm-7 help-block">
                        过期时间用于上传的签名有效期，如果是私有读写权限，也是文件签字有效期。
                    </div>
                </div>

                <!-- 域名 -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">访问域名</label>
                    <div class="col-sm-3">
                        <input
                            type="url"
                            class="form-control"
                            placeholder="请输入 Object 访问域名..."
                            v-model="form.domain"
                        >
                    </div>
                    <div class="col-sm-7 help-block">
                        访问域名可以是绑定的别名域名，也可以是 OSS 系统分配的 Bucket 访问域名，输入域名请携带<b>协议</b>，例如 <code>https://image.oss.cn</code>。
                    </div>
                </div>

                <!-- 域名 -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">内部域名</label>
                    <div class="col-sm-3">
                        <input
                            type="url"
                            class="form-control"
                            placeholder="请输入 Object 访问域名..."
                            v-model="form.insideDomain"
                        >
                    </div>
                    <div class="col-sm-7 help-block">
                        如同访问域名一样！如果你使用与 OSS 同可用区域的 ECS，这里可以设置为内网的访问地址，例如：<code>http://seven-local.oss-cn-beijing-internal.aliyuncs.com</code>并且不建议设置为 <code>https://</code> 协议，因为这样会浪费很多的解压时间和压缩时间！如果你没有使用阿里云 ECS 主机，这里建议与<code>「访问域名」</code>保持一致。
                    </div>
                </div>
                
                <!-- 提交按钮 -->
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <ui-button type="button" class="btn btn-primary" @click="submitHandler" />
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
        form: {
            accessKeyId: null,
            accessKeySecret: null,
            bucket: null,
            acl: 'public-read',
            timeout: 3600,
            domain: null,
            insideDomain: null,
        },
        loading: true,
    }),
    created() {
        this.loading = true;
        request.get(
            createRequestURI('file-storage/filesystems/aliyun-oss'),
            {
                validateStatus: status => status === 200
            }
        )
        .then(({ data }) => {
            this.form = data;
            this.loading = false;
        })
        .catch(({ response: { data: message = "获取默认 Aliyun OSS 设置失败，请检查网络或者刷新页面重试" } }) => {
            this.$store.dispatch("alert-open", { type: "danger", message });
        });
    },
    methods: {
        submitHandler({ stopProcessing }) {
            request.patch(
                createRequestURI('file-storage/filesystems/aliyun-oss'),
                this.form,
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

