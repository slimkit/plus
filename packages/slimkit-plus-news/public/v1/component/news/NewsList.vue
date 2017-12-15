<template>
    <div class="component-container container-fluid">
        <!-- 错误提示 -->
        <ModelTips ref="modelTips" />
        <!-- /错误提示 -->
        <!-- 资讯列表面板 -->
        <div class="panel panel-default">
            <div class="well well-sm mb0">
                <router-link to='/manage' class="btn btn-link btn-xs" data-toggle="modal" data-target="#pop_layer" role="button">
                    <span class="glyphicon glyphicon-plus"></span> 添加资讯
                </router-link>
            </div>
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="标题" v-model="search.key">
                            <span class="input-group-btn">
                                <input class="btn btn-default" type="button" @click='getNewsList(1)' value='搜索' />
                            </span>
                        </div>
                    </div>
                    <div class="col-xs-1">
                        <select class="form-control" v-model='search.cate_id' @change='getNewsList(1)'>
                            <option value="0">分类</option>
                            <option v-for="item in cates" :value="item.id">{{item.name}}</option>
                        </select>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group rs">
                            <label class="radio-inline" v-for='state in state'>
                                <input type="radio" name="optionsRadios" v-model='search.state' @click='getNewsList(1)' :value="state.value">{{state.label}}
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-4 text-right">
                        <Page :current='current_page' :last='last_page' @pageGo='pageGo'></Page>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <!-- Table -->
                <table class="table table-hover text-center table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width='25%'>标题</th>
                            <th width='10%'>作者</th>
                            <th width='10%'>缩略图</th>
                            <th width='10%'>创建时间</th>
                            <th width='10%'>修改时间</th>
                            <th width='10%'>审核状态</th>
                            <th width='15%' style="min-width:215px">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if='newsList.length' v-for="news in newsList" :key="news.id" :class="status2style(news.audit_status)">
                            <td width='25%' class="text-left">{{ news.title }}</td>
                            <td width='10%'><span v-if="news.user">{{ news.user.name }}</span></td>
                            <td width='10%'>
                                <a class="thumbnail"  v-if='news.image' :href='"/api/v2/files/" + news.image.id' target="_blank">
                                    <img :src='"/api/v2/files/" + news.image.id' class="small_img" :alt="news.image.id">
                                </a>
                                <span v-else>暂无缩略图</span>
                            </td>
                            <td width='10%'>{{ news.created_at }}</td>
                            <td width='10%'>{{ news.updated_at }}</td>
                            <td width='10%'>{{ status2label(news.audit_status)}}</td>
                            <td width='15%'>
                                <!-- 编辑 -->
                                <router-link type="button" class="btn btn-primary btn-sm" :to="'/manage/'+news.id">编辑</router-link>
                                <!-- 审核 -->
                                <button type="button" class="btn btn-primary btn-sm" :disabled='news.audit_status!==1' @click="checkNews(news)">通过</button>
                                <!-- 驳回 -->
                                <button type="button" class="btn btn-danger btn-sm" :disabled='news.audit_status!==1' @click="backNews(news)">驳回</button>
                                <!-- 删除 -->
                                <button v-if='deleteID === news.id' type="button" class="btn btn-danger btn-sm" disabled="disabled">
                                    <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
                                </button>
                                <button v-else type="button" class="btn btn-danger btn-sm" @click="deleteNews(news.id)">删除</button>
                            </td>
                        </tr>
                        <tr v-else>
                            <td colspan="7" class="danger">暂无数据！！！</td>
                        </tr>
                    </tbody>
                </table>
                <!-- 加载动画 -->
                <div v-if="loadding" class="loadding">
                    <span class="glyphicon glyphicon-refresh loaddingIcon"></span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import request, {
    createRequestURI
} from '../../util/request';

import Page from '../Page.vue';


export default {
    name: 'news_list',
    components: {
        Page
    },
    data: () => ({
        loading: false,
        // 资讯状态值
        state: [{
            label: '全部',
            value: null
        }, {
            label: '已审核',
            value: 0
        }, {
            label: '待审核',
            value: 1
        }, {
            label: '草稿',
            value: 2
        }, {
            label: '驳回',
            value: 3
        }],
        search: {
            cate_id: 0,
            key: null,
            state: null
        },

        // 搜索记录
        searchTemp: {
            cate_id: null,
            key: null,
            state: null
        },
        current_page: 1,
        last_page: 0,
        newsList: [],
        cates: [],
        deleteID: null
    }),
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
        getCatesList(page) {
            this.cates = [];
            request.get('/news/admin/news/cates')
                .then(({ status, data :{ data = [] } = {}}) => {
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
        // 获取资讯列表
        getNewsList(page) {
            this.news = [];
            this.loadding = true;
            request.get('/news/admin/news', {
                params: {
                    page: page || this.current_page,
                    ...this.search
                }
            }).then(({ data: { status, data:{ data = [], last_page = 0, current_page = 0 } = {}, }}) => {
                this.loadding = false;
                if (!status){
                  this.showTips("error", '获取资讯列表失败！');
                  return ;
                }
                this.newsList = data;
                this.last_page = last_page;
                this.current_page = current_page;
                this.clearTips();
                if(!data.length){
                  this.showTips("info", '暂无数据！');
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

        // 数据格式化
        status2style(state) {
            switch (state) {
                case 1:
                    return 'info';
                case 3:
                    return 'danger';
                default:
                    return '';
            }
        },

        status2label(state) {
            /*文章状态 0-正常 1-待审核 2-草稿 3-驳回*/
            switch (state) {
                case 0:
                    return '正常';
                case 1:
                    return '待审核';
                case 2:
                    return '草稿';
                case 3:
                    return '驳回';
                case 4:
                    return '已删除';
                case 5:
                    return '已退款';
                default:
                    return '未知'
            }
        },

        // 资讯操作 删除 审核 驳回
        deleteNews(_id) {
            this.deleteID = _id;
            request.delete(`/news/admin/news/del/${_id}/news`)
                .then(({data:{ status} = {}}) => {
                    this.deleteID = null;
                    if (!status){
                      this.showTips("error", "删除资讯失败！");
                      return ;
                    };
                    this.getNewsList();
                }).catch(({
                    response: {
                        data: {
                            error = '删除资讯失败！'
                        } = {}
                    } = {}
                }) => {
                    this.showTips("error", error);
                    this.deleteID = null;
                });
        },
        checkNews(news) {
            if (news && news.id && news.audit_status === 1) {
                request.post(`/news/admin/news/audit/${news.id}`, {
                    state: 0
                }).then(({ data:{ status } = {} }) => {
                    if (!status) {
                      this.showTips("error", "审核操作失败！");
                      return;
                    }
                    this.getNewsList();
                }).catch(({
                    response: {
                        data: {
                            error = '审核操作失败！'
                        } = {}
                    } = {}
                }) => {
                  this.showTips("error", error);
                });
            }
        },
        backNews(news) {
            if (news && news.id && news.audit_status === 1) {
                request.post(`/news/admin/news/audit/${news.id}`, {
                    state: 3
                }).then(({data:{ status } = {}}) => {
                    if (!status){
                      this.showTips("error", "驳回操作失败！");
                       return ;
                    }
                    this.getNewsList();
                }).catch(({
                    response: {
                        data: {
                            error = "驳回操作失败！"
                        } = {}
                    } = {}
                }) => {
                    this.showTips("error", error);
                    this.deleteID = null;
                });
            }
        },

        // 分页功能
        pageGo(page) {
            this.current_page = page;
            this.getNewsList();
        }
    },
    created() {
        // 获取分类列表
        this.getCatesList();

        //获取资讯列表
        this.getNewsList();
    },
}
</script>
<style>
.table th {
    text-align: center;
    vertical-align: middle;
}

.table td {
    vertical-align: middle !important;
}

.table .small_img {
    display: block;
    margin: auto;
    height: 60px;
    line-height: 60px;
}

.table .big_img {
    height: 120px;
}

.table .thumbnail{
    margin-bottom: 0;
}

.mb0 {
    margin-bottom: 0 !important;
}

.ma0 {
    margin: 0 !important;
}

.rs {
    margin-top: 7px;
    margin-bottom: 0px;
}

.loadding {
    text-align: center;
    font-size: 22px;
}

.loaddingIcon {
    animation-name: "TurnAround";
    animation-duration: 1.4s;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
}
</style>