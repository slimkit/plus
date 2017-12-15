<template>
    <div class="component-container container-fluid">
        <!-- 错误提示 -->
        <ModelTips ref="modelTips" />
        <!-- /错误提示 -->
        <!-- 分类列表面板 -->
        <div class="panel panel-default" style="min-width:1235px;">
            <div class="well well-sm mb0">
                <span class="btn btn-link btn-xs" @click="showLayer" role="button">
                    <span class="glyphicon glyphicon-plus"></span> 添加分类
                </span>
            </div>
            <!-- 加载动画 -->
            <div v-if="loadding" class="loadding">
                <span class="glyphicon glyphicon-refresh loaddingIcon"></span>
            </div>
            <div v-else class="panel-body">
                <!-- Table -->
                <table class="table table-hove text-center table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>分类名称</th>
                            <th>资讯数量</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cates in cates" :key="cates.id">
                            <td>{{ cates.name }}</td>
                            <td>{{ cates.news_count }}</td>
                            <td>
                                <!-- 编辑 -->
                                <button type="button" class="btn btn-primary btn-sm" @click='showLayer(cates)'>编辑</button>
                                <!-- /编辑 -->
                                <!-- 删除 -->
                                <button v-if='deleteID === cates.id' type="button" class="btn btn-danger btn-sm" disabled="disabled">
                                    <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" v-else @click="deleteCates(cates.id)">删除</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- 弹出层  -->
        <!-- <PopLayer :currentView='currentView' :dataItem='catesItem' @update='getCatesList' /> -->
        <!-- /弹出层  -->
        <!-- 弹层 -->
        <Layer v-model="open" :title="`编辑`" @update="getCatesList">
            <ManageCate :curId="cur_id" :curName="cur_name" />
        </Layer>
        <!-- /弹层 -->
    </div>
</template>
<script>
import request, {
    createRequestURI
} from '../../util/request';
import Page from '../Page.vue';

import Layer from "../Layer/Layer";

import ManageCate from './ManageCate';


export default {
    name: 'categories_list',
    components: {
        Page,
        Layer,
        ManageCate,
    },
    data: () => ({
        current_page: 1,
        last_page: 0,
        cates: [],
        deleteID: null,
        loadding: false,
        open: false,
        cur_name: "",
        cur_id: null,
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

        // 显示弹层
        showLayer({ id = null, name = "" } = {}) {
            this.open = true;
            this.cur_id = id;
            this.cur_name = name;
        },

        // 分页获取分类列表
        getCatesList(page) {
            this.cates = [];
            this.loadding = true;
            request.get('/news/admin/news/cates', {
                params: {
                    // page: page || this.current_page
                }
            }).then(({ status, data: { data = [], last_page = 0, current_page = 0 } = {} }) => {
                if (!status) {
                    this.showTips("error", "获取数据失败！");
                    return;
                }
                this.loadding = false;
                this.cates = data;
                this.last_page = last_page;
                this.current_page = current_page;
                if (!data.length) {
                    this.showTips("info", "暂无数据！");
                }
            }).catch(({
                response: {
                    data: {
                        error = "加载数据失败"
                    } = {}
                } = {}
            }) => {
                this.loadding = false;
                this.showTips("error", error);
            });
        },

        // 按ID删除分类
        deleteCates(_id) {
            this.deleteID = _id;
            request.delete(`/news/admin/news/del/${_id}/cate`)
                .then(({ data: { status } }) => {
                    this.deleteID = null;
                    if (!status) {
                        this.showTips("error", "删除分类失败！");
                        return;
                    }
                    this.getCatesList();
                })
                .catch(({
                    response: {
                        data: {
                            error = '删除失败'
                        } = {}
                    } = {}
                }) => {
                    this.deleteID = null;
                    this.showTips("error", error);
                });
        },

        addCates() {
            this.catesItem = null;
        },

        // 分页功能
        pageGo(page) {
            this.current_page = page;
            this.getCatesList();
        }
    },
    created() {
        // 获取分类列表
        this.getCatesList();
    },
}
</script>