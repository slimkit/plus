<template>
    <div class="panel panel-primary">
        <div class="panel-heading">
            编辑资讯
        </div>
        <div class="panel-body">
            <div class="form-horizontal">
                <!-- 标题 -->
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="row">
                            <label for="title" class="col-sm-2 control-label">标题</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="title" aria-describedby="title-help-block" placeholder="请输入标题" v-model="news.title" @blur="isVoid(news.title, '标题不能为空')">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <span class="tips">请输入文章标题。</span>
                    </div>
                </div>
                <!-- 摘要 -->
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="row">
                            <label for="subject" class="col-sm-2 control-label">摘要</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="subject" aria-describedby="subject-help-block" placeholder="请输入摘要" v-model="news.subject">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <span class="tips">请输入文章摘要，若为空，默认截取文章前60字。</span>
                    </div>
                </div>
                <!-- 分类 -->
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="row">
                            <label for="title" class="col-sm-2 control-label">分类</label>
                            <div class="col-sm-10">
                                <select class="form-control" :value="cateID" v-model='params.cate_id'>
                                    <option value="">分类</option>
                                    <option v-for="item in cates" :value="item.id">{{item.name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <span class="tips">请选择文章分类。</span>
                    </div>
                </div>
                <!-- 来源 -->
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="row">
                            <label for="from" class="col-sm-2 control-label">来源</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="from" aria-describedby="from-help-block" placeholder="请输入来源" v-model="news.from">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <span class="tips">请输入文章来源。</span>
                    </div>
                </div>
                <!-- 发布者 -->
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="row">
                            <label for="author" class="col-sm-2 control-label">发布者</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="author" aria-describedby="author-help-block" placeholder="请输入发布者" v-model="news.author" @blur="isVoid(news.author, '发布者不能为空')">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <span class="tips">请输入文章发布者。</span>
                    </div>
                </div>
                <!-- 标签 -->
                <div class="form-group">
                    <label class="col-sm-1 control-label">标签</label>
                    <div class="col-sm-11">
                        <module-tag-select :value1="tagsIDs" :max="5" v-model="params.tags" @tips="publishMessage"></module-tag-select>
                    </div>
                </div>
                <!-- 封面图 -->
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="row">
                            <label class="col-sm-2 control-label">封面图</label>
                            <div class="col-sm-10">
                                <module-post-cover :img="imageID" v-model="params.storage"></module-post-cover>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 正文 -->
                <div class="form-group">
                    <label for="content" class="col-sm-1 control-label">正文</label>
                    <div class="col-sm-11">
                        <mavon-editor
                            ref="editor"
                            v-model="news.content"
                            :apiHost="apiHost"
                            @imgAdd="$imgAdd"
                        >
                                
                            </mavon-editor>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="btn-group">
                <button class="btn btn-sm btn-default" @click="checkParams" @keydown.ctrl.alt.s.prevent.stop="checkParams">保存</button>
                <button class="btn btn-sm btn-default" onclick="window.history.go(-1)">返回</button>
            </div>
        </div>
        <ui-alert :type="message.type" v-show="message.open">
            {{ message.data | plusMessageFirst("") }}
        </ui-alert>
    </div>
</template>
<script>
import { mavonEditor } from "@slimkit/plus-editor";
import "@slimkit/plus-editor/dist/css/index.css";
import "highlight.js/styles/github.css";
import { admin, api } from "../../axios";
import components from "../modules/managenews";
const apiHost =
    document.head.querySelector('meta[name="domain"]').content || "";
export default {
    name: "manage-news",
    components: {
        ...components,
        mavonEditor
    },
    data() {
        return {
            apiHost,
            cates: [],
            news: {
                titlle: "",
                subject: "",
                from: "",
                author: "",
                content: "",
                image: {},
                tags: [],
                category: {}
            },

            // 组装数据
            params: {
                news_id: "",
                cate_id: "",
                storage: "",
                tags: []
            },

            message: {
                open: false,
                type: "",
                data: {}
            }
        };
    },
    computed: {
        cateID() {
            const { category } = this.news;
            this.params.cate_id = category.id;
            return category ? category.id : undefined;
        },
        imageID() {
            const { image } = this.news;
            return image ? image.id : undefined;
        },
        tagsIDs() {
            let r = this.news.tags.map(tag => {
                return tag.id;
            });

            this.params.tags = r;
            return r;
        }
    },
    methods: {
        /**
         * 文件上传.
         * @param  {Object}   file
         * @param  {Function} callback
         * @return {void}
         */
        uploadFile(file, callback) {
            let param = new FormData();
            param.append("file", file);
            let reader = new FileReader();
            reader.readAsDataURL(file);

            reader.onload = function(e) {
                api
                    .post("files", param, {
                        validateStatus: status => status === 201
                    })
                    .then(response => {
                        callback(response.data.id);
                    })
                    .catch(error => {
                        this.publishMessage(err, "error");
                    });
            };
        },
        /**
         * [$imgAdd description]
         * @Author   Wayne
         * @DateTime 2018-07-10
         * @Email    qiaobin@zhiyicx.com
         * @return   {[type]}            [description]
         */
        $imgAdd(pos, $file) {
            // 第一步.将图片上传到服务器.
            var formdata = new FormData();
            formdata.append("file", $file);
            this.uploadFile($file, id => {
                this.$refs.editor.$img2Url(pos, id);
            });
        },
        /**
         * 显示提示信息
         * @param  {Object} data
         * @param  {String} type
         * @param  {Number} ms
         * @return {void}
         */
        publishMessage(data, type, ms = 5000) {
            this.message = { open: true, data, type };
            setTimeout(() => {
                this.message.open = false;
            }, ms);
        },

        /**
         * 获取分类列表
         * @param  {Function} cb
         * @return {void}
         */
        getCates(cb) {
            admin
                .get(`/news/cates`, {
                    validateStatus: status => status === 200
                })
                .then(({ data = [] }) => {
                    this.cates = [...data];
                    cb();
                })
                .catch(err => {
                    this.publishMessage(err, "danger");
                });
        },

        /**
         * 根据ID查询资讯详情
         * @param  {Number} id
         * @return {void}
         */
        getNewsById(id) {
            admin
                .get(`/news/info/${id}`, {
                    validateStatus: status => status === 200
                })
                .then(({ data = {} }) => {
                    this.news = { ...this.news, ...data };
                })
                .catch(err => {
                    this.publishMessage(err, "danger");
                });
        },

        /**
         * 检查输入值是否为空
         * @param  {      }  val
         * @param  {String}  tips
         * @return {Boolean}
         */
        isVoid(val, tips) {
            if (val) {
                return true;
            }
            this.publishMessage({ input: tips }, "danger");
            return false;
        },

        /**
         * 表单验证
         * @return {[type]}
         */
        checkParams() {
            const { title, content, author, subject, from } = this.news;

            let params = this.params;
            Object.assign(params, {
                title,
                content,
                author,
                subject,
                from: from || "原创"
            });

            if (params.tags.length === 0) {
                this.publishMessage({ input: "请选择资讯标签" }, "danger");
                return false;
            }

            const rule = {
                title: { value: title, tips: "标题不能为空" },
                author: { value: author, tips: "发布者不能为空" },
                content: { value: content, tips: "内容不能为空" },
                cate_id: { value: params.cate_id, tips: "请选择资讯分类" }
            };

            return Object.keys(rule)
                .map(key => {
                    return this.isVoid(rule[key].value, rule[key].tips);
                })
                .indexOf(false) === -1
                ? this.doSave(params)
                : false;
        },

        doSave(params) {
            admin
                .post("/news/handle_news", {
                    ...params,
                    validateStatus: status => status === 201
                })
                .then(({ data }) => {
                    this.params.news_id = data;
                    this.publishMessage({ message: "保存成功!" }, "success");
                    setTimeout(() => {
                        this.$router.go(-1);
                    }, 800);
                })
                .catch(err => {
                    this.publishMessage(err, "error");
                });
        }
    },
    created() {
        this.getCates(() => {
            let id = this.$route.params.newsID;
            if (id > 0) {
                this.params.news_id = id;
                this.getNewsById(id);
            }
        });
    }
};
</script>
