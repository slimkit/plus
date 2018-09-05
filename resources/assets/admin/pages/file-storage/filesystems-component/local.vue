<template>
    <div class="panel panel-default">
        <div class="panel-heading">本地存储</div>
        <div class="panel-body">
            <sb-ui-loading v-if="loading" />

            <div class="form-horizontal" v-else>

                <!-- 磁盘选择 -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">使用磁盘</label>
                    <div class="col-sm-3">
                        <select class="form-control" v-model="disk">
                            <option
                                v-for="disk in disks"
                                :key="disk"
                                :value="disk"
                            >
                                {{ disk }}
                            </option>
                        </select>
                    </div>
                    <div class="col-sm-7 help-block">
                        磁盘选择取决于你应用中 <code>config/filesystems.php</code> 中的 <code>disks</code>
                        所允许的磁盘，如果选择的磁盘没有在其磁盘配置中正确的配置，会影响到所选系统的正常运行。
                        如果你没有自定义过磁盘，那么选择 <code>local</code> 磁盘是最为保险且有效的设置。
                        其他磁盘选择如果你的应用自定义过磁盘，请按照你自定义的设置进行选择。
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
                            v-model.number="timeout"
                            min="0"
                        >
                    </div>
                    <div class="col-sm-7 help-block">
                        过期时间，适用于创建本地上传任务开始，上传链接的生命时间，如果你的应用没有特殊的大型文件上传，设置在一小时或者半小时较为合理。
                        默认是 <code>3600 Second</code> 即一小时，过期时间设置单位为<code>秒</code>。
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
        disk: 'local',
        timeout: 3600,
        loading: true,
        disks: [],
    }),
    created() {
        this.loading = true;
        request.get(
            createRequestURI('file-storage/filesystems/local'),
            {
                validateStatus: status => status === 200
            }
        )
        .then(({ data: { disk, timeout, disks } }) => {
            this.disk = disk;
            this.disks = disks;
            this.timeout = timeout;
            this.loading = false;
        })
        .catch(({ response: { data: message = "获取默认本地存储设置失败，请检查网络或者刷新页面重试" } }) => {
            this.$store.dispatch("alert-open", { type: "danger", message });
        });
    },
    methods: {
        submitHandler({ stopProcessing }) {
            request.patch(
                createRequestURI('file-storage/filesystems/local'),
                {
                    disk: this.disk,
                    timeout: this.timeout,
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

