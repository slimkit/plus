<template>
    <div class="panel panel-default">
        <div class="panel-heading">文件 MIME 类型</div>
        <div class="panel-body">

            <!-- Tips -->
            <div class="alert alert-warning">
                设置文件 MIME 可以限制用户上传的文件类型，为了简化设置，你只需要在添加的位置设置文件后缀，程序会自动转换为正确的 MIME 值。如果设置了不同后缀但是却是同类型的文件，则取其一，例如 <code>jpg</code> 和<code>jpeg</code> 这两个文件其实都是 <code>image/jpeg</code> 类型文件，只是文件名后缀不同而已！
            </div>

            <!-- 加载状态 -->
            <sb-ui-loading v-if="loading" />

            <form class="form-horizontal" v-else>
                <div class="form-group">
                    <div class="col-sm-12">
                        <!-- Extensions -->
                        <span class="label label-info" v-for="extension in extensions" :key="extension">
                            {{ extension }}
                            <span class="label-remove" title="删除" @click="removeLavelHandler(extension)">&times;</span>
                        </span>

                        <!-- add input -->
                        <div class="input-group add-input" v-if="showAddInput">
                            <input v-model="add" type="text" class="form-control" placeholder="请输入文件后缀名称...">
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="button" @click="addToExtentionHandler">
                                    <span class="glyphicon glyphicon-plus"></span> 添加
                                </button>
                            </span>
                        </div>

                        <!-- Show add button -->
                        <span class="label label-danger label-add" v-else @click="showAddInputHandler">
                            <span class="glyphicon glyphicon-plus"></span> 添加
                        </span>
                    </div>
                </div>

                <!-- 提交按钮 -->
                <div class="form-group">
                    <div class="col-sm-12">
                        <ui-button type="button" class="btn btn-primary" @click="submitHandler" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import lodash from 'lodash';
import request, { createRequestURI } from '../../../util/request';
export default {
    data: () => ({
        extensions: [],
        loading: true,
        showAddInput: false,
        add: '',
    }),
    created() {
        this.loading = true;
        request.get(
            createRequestURI('file-storage/file-mime-types'),
            {
                validateStatus: status => status === 200
            }
        )
        .then(({ data = [] }) => {
            this.extensions = data;
            this.loading = false;
        })
        .catch(({ response: { data: message = "获取文件 MIME Types 设置失败，请检查网络或者刷新页面重试" } }) => {
            this.$store.dispatch("alert-open", { type: "danger", message });
        });
    },
    methods: {
        submitHandler({ stopProcessing }) {
            request.patch(
                createRequestURI('file-storage/file-mime-types'),
                {
                    extensions: this.extensions
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
        },
        showAddInputHandler() {
            this.showAddInput = true;
            this.add = '';
        },
        addToExtentionHandler() {
            this.extensions = lodash.union(this.extensions, [this.add]);
            this.add = '';
            this.showAddInput = false;
        },
        removeLavelHandler(extension) {
            this.extensions = lodash.reduce(this.extensions, function (extensions, n) {
                if (n !== extension) {
                    extensions.push(n);
                }

                return extensions;
            }, []);
        }
    }
}
</script>

<style scoped>
.label-remove {
    color: red;
    cursor: pointer;
}
.label-add {
    cursor: pointer;
}
.add-input {
    margin-top: 15px;
}
.label {
    margin-left: 8px;
}
.label:first-child {
    margin-left: 0px;
}
</style>


