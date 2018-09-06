<template>
    <div class="panel panel-default">
        <div class="panel-heading">公开频道</div>
        <div class="panel-body">

            <div class="alert alert-warning">
                公开频道关键词为 <code>public</code>，公开频道中
                所存储的文件均为公开免费文件！主要适用场景为<code>用户头像</code>、<code>背景图片</code>以及<code>系统文件</code>
                等场景。
            </div>

            <sb-ui-loading v-if="loading" />

            <div class="form-horizontal" v-else>

                <!-- 选择文件系统 -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">文件系统</label>
                    <div class="col-sm-3">
                        <select class="form-control" v-model="filesystem">
                            <option value="" disabled>-- 请选择文件系统 --</option>
                            <option
                                v-for="filesystem in filesystems"
                                :key="filesystem.value"
                                :value="filesystem.value"
                            >
                                {{ filesystem.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-sm-7 help-block">
                        请选择频道中所使用的文件系统。如果没有选择，将使用<code>默认文件系统</code>。
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
    props: {
        filesystems: {
            required: true,
            type: Array
        }
    },
    data: () => ({
        filesystem: '',
        loading: true
    }),
    created() {
        this.loading = true;
        request.get(
            createRequestURI('file-storage/channels/public'),
            {
                validateStatus: status => status === 200
            }
        )
        .then(({ data: { filesystem } }) => {
            this.filesystem = filesystem;
            this.loading = false;
        })
        .catch(({ response: { data: message = "获取默认公开频道设置失败，请检查网络或者刷新页面重试" } }) => {
            this.$store.dispatch("alert-open", { type: "danger", message });
        });
    },
    methods: {
        submitHandler({ stopProcessing }) {
            request.patch(
                createRequestURI('file-storage/channels/public'),
                {
                    filesystem: this.filesystem
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

