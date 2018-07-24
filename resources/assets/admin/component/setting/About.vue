<template>
    <div class="container-fluid" style="margin-top:10px;">
        <div class="panel panel-default">
            <div class="panel-heading">
                关于我们设置
            </div>
            <div class="panel-body">
                <loading :loadding="loadding"></loading>
                <div class="form-horizontal" v-show="!loadding">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">关于我们页面</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="填写其他页面，优先级高" name="url" v-model="url" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-3 control-label" for="rule-content">关于我们内容</label>
                        <div class="col-sm-9">
                          <mavon-editor placeholder="输入关于我们的markdown内容" v-model="content" @imgAdd="$imgAdd" ref="editor" :apiHost="apiHost" />
                        </div>
                    </div>
                    <!-- Button -->
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-4">
                          <button v-if="loadding" type="button" class="btn btn-primary" disabled="disabled">
                            <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
                          </button>
                          <button v-else type="button" class="btn btn-primary" @click="saveConfig">保存设置</button>
                        </div>
                        <div class="col-sm-4">
                            <p class="text-success">{{ message }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import request, { createRequestURI } from "../../util/request";
import { mavonEditor } from "@slimkit/plus-editor";
import "@slimkit/plus-editor/dist/css/index.css";
import "highlight.js/styles/github.css";
import { uploadFile } from "../../util/upload";
const domain = window.TS.domain || "";
export default {
    name: "AboutUs",
    components: {
        mavonEditor
    },
    data: () => ({
        content: "",
        apiHost: domain,
        loadding: true,
        url: "",
        message: ""
    }),
    methods: {
        // 绑定@imgAdd event
        $imgAdd(pos, $file) {
            // 第一步.将图片上传到服务器.
            var formdata = new FormData();
            formdata.append("image", $file);
            uploadFile($file, id => {
                this.$refs.editor.$img2Url(pos, id);
            });
        },
        /**
         * 保存
         * @Author   Wayne
         * @DateTime 2018-07-04
         * @Email    qiaobin@zhiyicx.com
         * @return   {[type]}            [description]
         */
        saveConfig() {
            const { url, content } = this;
            let aboutUs = { url, content };
            request.patch(createRequestURI("about-us"), aboutUs).then(() => {
                this.message = "操作成功";
                setTimeout(() => {
                    this.message = "";
                }, 2000);
            });
        }
    },
    created() {
        request.get(createRequestURI("about-us")).then(({ data }) => {
            if (data.aboutUs) {
                this.url = data.aboutUs.url;
                this.content = data.aboutUs.content;
            }
            this.loadding = false;
        });
    }
};
</script>

<style scoped>
</style>
