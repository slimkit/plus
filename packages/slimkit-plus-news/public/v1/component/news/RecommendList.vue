<template>
    <div class="component-container container-fluid">
        <!-- 错误提示 -->
        <ModelTips ref="modelTips" />
        <!-- /错误提示 -->
        <!-- 推荐资讯列表面板 -->
        <div class="panel panel-default" style="min-width:1235px;">
            <div class="well well-sm mb0">
                <router-link to='/rec' class="btn btn-link btn-xs" data-toggle="modal" data-target="#pop_layer" role="button">
                    <span class="glyphicon glyphicon-plus"></span> 添加推荐资讯
                </router-link>
            </div>
            <div class="panel-body">
                <!-- Table -->
                <table class="table table-hover text-center table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Banner图</th>
                            <th>跳转方式</th>
                            <th>跳转值</th>
                            <th>所属分类</th>
                            <th>创建时间</th>
                            <th>修改时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="rec in recommendList" :key="rec.id">
                            <td width='30%'>
                                <a v-if='rec.cover' :href='"/api/v2/filesChange/" + rec.cover.id' target="_blank">
                                    <img :src='"/api/v2/files/" + rec.cover.id' class="big_img" :alt="rec.cover.id">
                                </a>
                                <span v-else>暂无Banner图</span>
                            </td>
                            <td width='10%'>{{ rec.type }}</td>
                            <td width='10%'>{{ rec.data }}</td>
                            <td width='10%'>{{ cates2label(rec.cate_id) }}</td>
                            <td width='10%'>{{ rec.created_at }}</td>
                            <td width='10%'>{{ rec.updated_at}}</td>
                            <td width='10%'>
                                <!-- 编辑 -->
                                <router-link type="button" class="btn btn-primary btn-sm" :to="'/rec/'+rec.id">编辑</router-link>
                                <!-- 删除 -->
                                <button v-if='deleteID === rec.id' type="button" class="btn btn-danger btn-sm" disabled="disabled">
                                    <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
                                </button>
                                <button v-else type="button" class="btn btn-danger btn-sm" @click="deleteRec(rec.id)">删除</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- 加载动画 -->
        <div v-show="loadding" class="loadding">
            <span class="glyphicon glyphicon-refresh loaddingIcon"></span>
        </div>
    </div>
</template>
<script>
import request, {
    createRequestURI
} from '../../util/request';

export default {
    data: () => ({
        loadding: false,
        currentPage: '1',
        tips: {
            error: null,
            success: null
        },
        deleteID: null,
        cates: [],
        recommendList: []
    }),
    created() {
        this.getCatesList();
        this.getRecList();
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

        getRecList() {
            this.recommendList = [];
            this.loadding = true;
            request.get('/news/admin/news/recommend')
                .then(({status,data:{data = []}={}}) => {
                    this.loadding = false;
                    if (!status) {
                      this.showTips("error", "获取推荐资讯列表失败！");
                      return ;
                    }
                    this.recommendList = data;
                    if (!data.length){
                      this.showTips("info", "暂无数据！");
                    }
                }).catch(({
                    response: {
                        data: {
                            error = '加载数据失败'
                        } = {}
                    } = {}
                }) => {
                    this.showTips("error", error);
                    this.loadding = false;
                });
        },

        // 获取分类列表
        getCatesList(page) {
            this.cates = [];
            request.get('/news/admin/news/cates')
                .then(({status, data:{ data =[] } = {}}) => {
                    if (!status) {
                      this.showTips("error", "获取分类列表失败！");
                      return ;
                    }
                    this.cates = data;
                }).catch(({
                    response: {
                        data: {
                            error = '获取分类列表失败！'
                        } = {}
                    } = {}
                }) => {
                  this.showTips("error", error);
                });
        },
        cates2label(cate_id) {
            let r = '';
            this.cates.forEach((item) => {
                if (item.id === cate_id) {
                    return r = item.name;
                }
            })
            return r;
        },
        deleteRec(_id) {
            this.deleteID = _id;
            request.delete(`/news/admin/news/del/${_id}/recommend`)
                .then(({data:{ status }={}}) => {
                    if (!status) {
                      this.showTips("error", "删除推荐失败！");
                      return ;
                    }
                    this.deleteID = null;
                    this.getRecList();
                }).catch(({
                    response: {
                        data: {
                            error = "删除推荐失败！"
                        } = {}
                    } = {}
                }) => {
                    this.deleteID = null;
                });
        },
    }
}
</script>