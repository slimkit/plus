<template>
    <div class="component-container container-fluid">
        <!-- 错误提示 -->
        <ModelTips ref="modelTips" />
        <!-- /错误提示 -->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-12">{{ _opt.tips}}</div>
                </div>
            </div>
            <!-- 加载动画 -->
            <div v-if="loadding" class="loadding">
                <span class="glyphicon glyphicon-refresh loaddingIcon"></span>
            </div>
            <div class="panel-body" v-else>
                <div class="form-horizontal">
                    <!-- 跳转方式 -->
                    <div class="form-group row">
                        <label for="title" class="col-sm-offset-2 col-sm-1 control-label">跳转方式</label>
                        <div class="col-sm-5">
                            <select name="title" id="title" class="form-control" v-model='_opt.type'>
                                <option value="">选择跳转方式</option>
                                <option value="url">url跳转</option>
                                <option value="news">资讯跳转</option>
                            </select>
                        </div>
                    </div>
                    <!-- 跳转值 -->
                    <div class="form-group row">
                        <label for="summary" class="col-sm-offset-2 col-sm-1 control-label">跳转值</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="summary" aria-describedby="summary-help-block" placeholder="请输入跳转值" v-model="_opt.data">
                        </div>
                    </div>
                    <!--所属分类 -->
                    <div class="form-group row">
                        <label for="categories" class="col-sm-offset-2 col-sm-1 control-label">所属分类</label>
                        <div class="col-sm-5">
                            <select name="categories" id="categories" class="form-control" v-model='_opt.cate_id'>
                                <option value=''>选择分类</option>
                                <option v-for="item in cates" :value="item.id">{{item.name}}</option>
                            </select>
                        </div>
                    </div>
                    <!-- 选择缩略图 -->
                    <div class="form-group row">
                        <label for="thumbnails" class="col-sm-offset-2 col-sm-1 control-label">选择Banner图</label>
                        <div class="col-sm-5">
                            <Upload :imgs='_opt.cover' @getTask_id="getTask_id" @updata='updataImg' />
                        </div>
                    </div>
                    <!-- Button -->
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button v-if="adding" type="button" class="btn btn-primary" disabled="disabled">
                                <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
                            </button>
                            <button v-else type="button" class="btn btn-primary" @click="ManageRec">{{_opt.tips}}</button>
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
export default {
    name: 'add_recommend',
    components: {
        Upload
    },
    data: () => ({
        loadding: false,
        adding: false,
        cates: [],
        storage: null,
        old_rec: null
    }),
    computed: {
        // 计算属性 _opt
        _opt() {
            let tips = '添加推荐',
                cate_id = '',
                cover = '',
                data = '',
                type = '',
                storage = '',
                id = '';
            if (this.old_rec && this.old_rec.id) {
                tips = "修改推荐";
                cate_id = this.old_rec.cate_id;
                cover = this.old_rec.cover;
                data = this.old_rec.data;
                type = this.old_rec.type;
                id = this.old_rec.id;
            }
            return {
                cate_id,
                cover,
                data,
                type,
                tips,
                id,
                storage: this.storage || cover,
            }
        }
    },
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

        getTask_id(task_id) {
            this.storage = task_id;
        },

        // 获取分类列表
        getCatesList() {
            this.cates = [];
            request.get('/news/admin/news/cates')
                .then(({ status, data: { data = [] } = {} }) => {
                    if (!status) {
                        this.showTips("error", "获取分类列表失败！");
                        return;
                    }
                    this.cates = data;
                }).catch(({
                    response: {
                        data: {
                            error = "获取分类列表失败！"
                        } = {}
                    } = {}
                }) => {

                    this.showTips("error", error);
                });
        },

        // 通过ID获取推荐资讯
        getRecById(rid) {
            request.get(`/news/admin/news/rec/${rid}`, {
                validateStatus: status => status === 200
            }).then(({ status, data: { data = {} } = {} }) => {
                if (status) {
                    this.old_rec = data;
                } else {
                    this.showTips("error", "获取推荐资讯详情失败！");
                }
            }).catch(({
                response: {
                    data: {
                        error = '加载数据失败'
                    } = {}
                } = {}
            }) => {
                this.showTips("error", "获取推荐资讯详情失败！");
            });
        },

        // 错误检测
        test(v, error) {
            if (v) return true;
            this.showTips("error", error);
            return false;
        },

        // 编辑推荐资讯
        ManageRec() {
            this.clearTips();

            let {
                id,
                type,
                cate_id,
                data,
                storage
            } = this._opt;

            let r = this.test(type, '请选择跳转方式！') && this.test(data, '请输入跳转值！') && this.test(cate_id, '请至少选择一个分类！');
            if (r) {
                this.adding = true;
                this.clearTips();
                request.post('/news/admin/news/handle_rec', {
                    rid: id,
                    type,
                    cate_id,
                    storage_id: storage,
                    data
                }).then(({ data: { status, message } = {} }) => {
                    this.adding = false;
                    if (status) {
                        this.showTips("success", "操作成功！");
                    } else {
                        this.showTips("info", message);
                    }
                }).catch(({
                    response: {
                        data: {
                            error = "操作失败"
                        } = {}
                    } = {}
                }) => {
                    this.showTips("error", error);
                    this.loadding = false;
                });
            }
        },
        updataImg() {
            this.storage = null;
            if (this.old_rec.cover) {
                this.old_rec.cover = null;
            }
        }
    },
    created() {
      // 获取分类列表
      this.getCatesList();
    },
    beforeMount(){
      // 通过id查询资讯详情
      if (this.$route.params.rid) {
          this.getRecById(this.$route.params.rid);
      }
    }
};
</script>