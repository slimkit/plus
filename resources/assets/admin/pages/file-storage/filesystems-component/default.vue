<template>
    <div class="panel panel-default">
        <div class="panel-heading">默认文件系统</div>
        <div class="panel-body">
            <div class="alert alert-warning">
                默认文件系统是用于在<code>频道</code>没有指定文件系统时候使用的默认文件系统。
            </div>

            <sb-ui-loading v-if="loading" />

            <div class="form-horizontal" v-else>
                <div class="form-group">
                    <label class="col-sm-2 control-label">文件系统</label>
                    <div class="col-sm-10">
                        <select class="form-control" v-model="selected">
                            <option
                                v-for="filesystem in filesystems"
                                :key="filesystem.value"
                                :value="filesystem.value"
                            >
                                {{ filesystem.name }}
                            </option>
                        </select>
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
        selected: 'local',
        loading: true,
        filesystems: [
            { value: 'local', name: '本地存储' },
            { value: 'AliyunOSS', name: '阿里云 OSS' },
        ]
    }),
    created() {
        this.loading = true;
        request.get(
            createRequestURI('file-storage/default-filesystem'),
            {
                validateStatus: status => status === 200
            }
        )
        .then(({ data: { filesystem } }) => {
            this.selected = filesystem;
            this.loading = false;
        })
        .catch(({ response: { data: message = "获取默认文件系统设置失败，请检查网络或者刷新页面重试" } }) => {
            this.$store.dispatch("alert-open", { type: "danger", message });
        });
    },
    methods: {
        submitHandler({ stopProcessing }) {
            request.patch(
                createRequestURI('file-storage/default-filesystem'),
                {
                    filesystem: this.selected
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

