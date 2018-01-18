<template>
    <div class="component-container container-fluid">
        <!-- 资讯列表面板 -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="标题" v-model="search.key">
                            <span class="input-group-btn">
                                <input class="btn btn-default" type="button" @click='getPinnedsList(1)' value='搜索' />
                            </span>
                        </div>
                    </div>
                    <div class="col-xs-1">
                        <select class="form-control" v-model='search.cate_id' @change='getPinnedsList(1)'>
                            <option value="0">分类</option>
                            <option v-for="item in cates" :value="item.id">{{item.name}}</option>
                        </select>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group rs">
                            <label class="radio-inline" v-for='state in state'>
                                <input type="radio" name="optionsRadios" v-model='search.state' @click='getPinnedsList(1)' :value="state.value">{{state.label}}
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-4 text-right">
                        <!-- <Page :current='current_page' :last='last_page' @pageGo='pageGo'></Page> -->
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <!-- Table -->
                <table class="table table-hover text-center table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width='10%'>ID</th>
                            <th width='10%'>申请人</th>
                            <th width='25%'>标题</th>
                            <th width='10%'>创建时间</th>
                            <th width='10%'>过期时间</th>
                            <th width='10%'>审核状态</th>
                            <th width='15%'>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if='PinnedsList.length' v-for="pinned in PinnedsList" :key="pinned.id" :class="status2style(pinned.state)">
                            <td width='10%'>{{pinned.id}}</td>
                            <td width='10%'>
                                <span v-if="pinned.user">{{pinned.user.name}}</span>
                                <span v-else>该用户不存在或已删除</span>
                            </td>
                            <td width='25%' class="text-left">
                                <span v-if="pinned.news">{{ pinned.news.title }}</span>
                                <span v-else>该资讯不存在或已删除</span>
                            </td>
                            <td width='10%'>{{ pinned.created_at }}</td>
                            <td width='10%'>{{ pinned.expires_at }}</td>
                            <td width='10%'>{{ status2label(pinned.state)}}</td>
                            <td width='15%'>
                                <!-- 通过 -->
                                <button type="button" class="btn btn-primary btn-sm" :disabled='pinned.state!==0' @click="checkPinned(pinned.id)(`accept`)">通过</button>
                                <!-- /通过 -->
                                <!-- 驳回 -->
                                <button type="button" class="btn btn-danger btn-sm" :disabled='pinned.state!==0' @click="checkPinned(pinned.id)(`reject`)">驳回</button>
                                <!-- /驳回 -->
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
        <!-- 错误提示 -->
        <ModelTips ref="modelTips" />
        <!-- /错误提示 -->
    </div>
</template>
<script>
import request, {
    createRequestURI
} from '../../util/request';
import Page from '../Page.vue';
export default {
    name: "NewsPinneds",
    components: {
        Page
    },
    data() {
        return ({
            search: {
                key: "",
                cate_id: null,
                state: null,
            },
            /* 审核状态0: 待审核, 1 审核通过, 2 被拒    */
            state: [{
                label: '全部',
                value: null
            }, {
                label: '待审核',
                value: 0
            }, {
                label: '审核通过',
                value: 1
            }, {
                label: '被拒',
                value: 2
            }],
            current_page: 1,
            last_page: 0,
            PinnedsList: [],
            cates: [],
            loadding: true,
        });
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

        // 获取分类列表
        getCatesList(page) {
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
                            error = '获取分类列表失败！'
                        } = {}
                    } = {}
                }) => {
                    this.showTips("error", error);
                });
        },

        // 获取资讯列表
        getPinnedsList(page) {
            this.news = [];
            this.loadding = true;
            request.get('/news/admin/news/pinneds', {
                params: {
                    page: page || this.current_page,
                    ...this.search
                }
            }).then(({ status, data = [] }) => {
                this.loadding = false;
                if (!status) {
                    this.showTips("error", '获取审核列表失败！');
                    return;
                }
                this.PinnedsList = data;

                this.clearTips();
                if (!data.length) {
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
                case 0:
                    return 'info';
                case 2:
                    return 'danger';
                default:
                    return '';
            }
        },

        status2label(state) {
            /* 审核状态0: 待审核, 1审核通过, 2被拒    */
            switch (state) {
                case 0:
                    return '待审核';
                case 1:
                    return '审核通过';
                case 2:
                    return '被拒';
                default:
                    return '未知'
            }
        },

        checkPinned(id) {
            return (action) => {
                request.patch(`/news/admin/news/pinned/${id}`, {
                    action
                }, {
                    validateStatus: state => state === 204
                }).then(({ status }) => {
                    status && this.showTips("success", "操作成功!");
                }, ({response: { data: { message: [ err = "操作失败!"] = [] } = {} }}) => {
                    err && this.showTips("error", err);
                });
            }
        },
        // 分页功能
        pageGo(page) {
            this.current_page = page;
            this.getPinnedsList();
        }
    },
    created() {
        // 获取分类列表
        this.getCatesList();

        //获取资讯列表
        this.getPinnedsList();
    },
}
</script>