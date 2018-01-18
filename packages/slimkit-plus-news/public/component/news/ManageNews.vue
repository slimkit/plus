<template>
    <div class="component-container container-fluid">
        <!-- 错误提示 -->
        <ModelTips ref="modelTips" />
        <!-- /错误提示 -->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-12">编辑资讯</div>
                </div>
            </div>
            <!-- 加载动画 -->
            <div v-if="loadding" class="loadding">
                <span class="glyphicon glyphicon-refresh loaddingIcon"></span>
            </div>
            <div class="panel-body" v-else>
                <div class="form-horizontal">
                    <!-- 标题 -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title" class="col-sm-2 control-label">标题</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="title" @blur="test(opt.title,'请输入资讯标题！')" aria-describedby="title-help-block" placeholder="请输入标题" v-model="opt.title">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <span class="tips">请输入文章标题。</span>
                        </div>
                    </div>
                    <!-- 摘要 -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="summary" class="col-sm-2 control-label">摘要</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="summary" aria-describedby="summary-help-block" placeholder="请输入文章摘要" v-model="opt.subject">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <span class="tips">请输入文章摘要,默认截取文章前60字。</span>
                        </div>
                    </div>
                    <!-- 文章来源 -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="source" class="col-sm-2 control-label">来源</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="source" aria-describedby="source-help-block" placeholder="请输入文章来源" v-model="opt.source">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <span class="tips">请输入文章来源。</span>
                        </div>
                    </div>
                    <!-- 发布者 -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="author" class="col-sm-2 control-label">发布者</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="author" aria-describedby="author-help-block" placeholder="请输入文章发布者" v-model="opt.author">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <span class="tips">请输入文章发布者，用于发布文章时显示</span>
                        </div>
                    </div>
                    <!-- 选择文章分类 -->
                    <div class="form-group row">
                        <label for="categories" class="col-sm-1 control-label">分类</label>
                        <div class="col-sm-11" style="min-height:41px;">
                            <Vselect :options='cates' v-model='cate_id' :value='cate_id' :trackBy="'id'" :label="'name'" />
                        </div>
                    </div>
                    <!-- 选择文章标签 -->
                    <div class="form-group row">
                        <label for="tags" class="col-sm-1 control-label">标签</label>
                        <div class="col-sm-11" style="min-height:41px;">
                            <Vselect :options="tagsAll" v-model='tags' :value='tags' :trackBy="'id'" :label="'name'" :multiple="true" :group="true" :children="'tags'"  />
                        </div>
                    </div>
                    <!-- /选择文章标签 -->
                    <!-- 选择缩略图 -->
                    <div class="form-group row">
                        <label for="thumbnails" class="col-sm-1 control-label">配图</label>
                        <div class="col-sm-11">
                            <Upload :imgs='image' @getTask_id="getTask_id" @updata='updataImg' />
                        </div>
                    </div>
                    <!-- 文章内容 -->
                    <div class="form-group row">
                        <label for="content" class="col-sm-1 control-label">正文</label>
                        <div class="col-sm-11">
                            <vue-editor id="editor" v-model="opt.content" />
                            <!-- <mavon-editor v-model="opt.content"/> -->
                            <!-- <Editor :value='opt.content' v-model='opt.content' /> -->
                        </div>
                    </div>
                    <!-- Button -->
                    <div class="form-group">
                        <div class="col-sm-offset-1 col-sm-11">
                            <button v-if="adding" type="button" class="btn btn-primary" disabled="disabled">
                                <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
                            </button>
                            <button v-else type="button" class="btn btn-primary" @click="ManageNews">保存</button>
                            <button type="button" onClick="window.history.go(-1)" class="btn btn-default">返回</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import request, {
    createRequestURI
} from '../../util/request';

import Upload from '../Upload_v2';
import Vselect from '../select';

// 编辑器待定
// import { mavonEditor } from 'mavon-editor';
// import 'mavon-editor/dist/css/index.css';

// 编辑器待定
// import Editor from "./J_Editor.vue";


export default {
    name: 'ManageNews',
    components: {
        Upload,
        Vselect,
        // Editor,
        // mavonEditor
    },
    data: () => ({
        adding: false,
        loadding: false,
        cates: [],
        tagsAll: [],
        cate_id: null,
        tags: [],
        image: null,
        opt: {
            id: null,
            user_id: null,
            title: '',
            subject: '',
            category: { id: null },
            content: '',
            from: '',
            author: ''
        }
    }),
    computed: {},
    methods: {

        //  显示提示信息
        showTips(type, mesg) {
            if (type && mesg) {
                this.$refs.modelTips.show({
                    type,
                    mesg,
                });
            }
        },
        // 清除错误
        clearTips() {
            this.$refs.modelTips.clear();
        },

        // 获取分类列表
        getCatesList() {
            request.get('/news/admin/news/cates')
                .then(({ data: { status, data = [] } }) => {
                    if (!status) return this.showTips("error", '获取分类列表失败！');
                    this.cates = data;
                })
                .catch(({ response: { data: { error = '获取分类列表失败！' } = {} } = {} }) => {
                    this.showTips('error', error);
                });
        },

        // 获取标签列表
        getTagesAllList() {
            request.get('/api/v2/tags')
                .then(({ status, data = [] }) => {
                    if (!status) return this.showTips("error", '获取标签列表失败！');
                    this.tagsAll = data;
                })
                .catch(({ response: { data: { error = '获取标签列表失败！' } = {} } = {} }) => {
                    this.showTips('error', error);
                });
        },

        // 获取资讯详情
        getNewsById(id) {

            request.get(`/news/admin/news/info/${id}`)
                .then(({ data: { status, data = {} } }) => {
                    if (!status) return this.showTips("error", '获取资讯详情失败！');
                    const {
                        category = { id: null },
                            image = { id: null },
                            tags = []
                    } = data || {};
                    this.opt = { ...data };
                    this.cate_id = category ? category.id : null;
                    this.tags = tags.map((item)=>item.id) || [];
                    this.image = image ? image.id : null;
                })
                .catch(({ response: { data: { error = '获取资讯详情失败！' } = {} } = {} }) => {
                    this.showTips('error', error);
                });
        },

        // 表单验证
        test(v, error) {
            if (v) return true;
            this.showTips('error', error);
            return false;
        },

        // 编辑文章
        ManageNews() {
            this.clearTips();

            const {
                id,
                title,
                source,
                subject,
                cate_id = this.cate_id,
                author,
                content
            } = this.opt;
            let r = this.test(title, '请输入资讯标题！') &&
                this.test(cate_id, '请选择一个资讯分类！') &&
                this.test(content, '资讯内容不能为空！');
            if (r) {
                this.adding = true;
                this.clearTips();
                request.post('/news/admin/news/handle_news', {
                    news_id: id,
                    title,
                    source,
                    content,
                    subject,
                    cate_id,
                    storage: this.image,
                    author: author || window.TS.user.name,
                    tags: this.tags
                }).then(({ data:{status,message,data} = {} }) => {
                    this.adding = false;
                    if(data && status){
                        this.opt.id = this.opt.id === data ?
                        this.showTips('success', message):
                        this.showTips('success', "添加资讯成功");
                    }else{
                        this.showTips("error", message);
                    };
                }).catch(({
                    response: {
                        data: {
                            error = '操作失败'
                        } = {}
                    } = {}
                }) => {
                    this.adding = false;
                    this.showTips('error', error);
                });
            }
        },

        // 图片上传
        // 获取图片ID || 图片上传任务ID
        getTask_id(task_id) {
            this.image = task_id;
        },

        // 清除图片ID || 任务ID
        updataImg() {
            this.image = null;
        },

        // 改变当前分类
        changeCates(v) {
            this.cate_id = v;
        },

        // 改变当前标签
        changeTags(v) {
            this.tags = v;
        }
    },

    created() {
        // 获取分类列表
        this.getCatesList();

        // 获取全局标签列表
        this.getTagesAllList()
    },
    beforeMount() {
        // 通过id查询资讯详情
        if (this.$route.params.newsId) {
            this.getNewsById(this.$route.params.newsId);
        }
    }
};
</script>
<style lang='scss'>
.tips {
    opacity: 0.8;
    height: 36px;
    line-height: 36px;
    vertical-align: middle;
}

#editor {
    width: 100%;
    height: 580px;
    min-height: 580px;
}
</style>