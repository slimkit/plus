<template>
    <div>
        <table class="table table-hover table-bordered table-responsive table-middle">
            <thead>
                <tr>
                    <th># ID</th>
                    <th width="10%">标题</th>
                    <th>发布者(ID)</th>
                    <th>缩略图</th>
                    <th>分类</th>
                    <th>发布时间</th>
                    <th>修改时间</th>
                    <th>状态</th>
                    <th width="20%">操作</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="news in datas" :class="statusToTips(news.audit_status).cls">
                    <td>{{ news.id }}</td>
                    <td>
                        <p class="td-title" :title="news.title">{{ news.title }}</p>
                    </td>
                    <td>{{ news.user ? `${news.user.name} #${news.user_id}` : `$#{news.user_id}` }}</td>
                    <td class="td-thumbnail">
                        <template>
                            <a class="thumbnail" v-if='news.image' :href='"/api/v2/files/" + news.image.id' target="_blank">
                                <img :src='"/api/v2/files/" + news.image.id' class="small_img" :alt="news.image.id">
                            </a>
                            <span v-else>暂无缩略图</span>
                        </template>
                    </td>
                    <td>{{ news.category.name }}</td>
                    <td>{{ news.created_at }}</td>
                    <td>{{ news.updated_at }}</td>
                    <td>{{ statusToTips(news.audit_status).label }}</td>
                    <td style="white-space: pre-wrap">
                        <div class="btn-group" style="margin: 5px 0;">
                            <!-- 编辑 -->
                            <router-link type="button" class="btn btn-primary btn-sm" :to="`/manage/${news.id}`">编辑</router-link>
                            <!-- 通过 -->
                            <button class="btn btn-primary btn-sm" :disabled="news.audit_status!==1" @click="checkNews(news.id,0)">通过</button>
                            <!-- 驳回 -->
                            <button class="btn btn-danger btn-sm" :disabled="news.audit_status!==1" @click="checkNews(news.id,3)">驳回</button>
                        </div>
                        <div class="btn-group" style="margin: 5px 0;">
                            <!-- 取消置顶 -->
                            <button v-if="(news.pinned && new Date(news.pinned.expires_at) - new Date() > 0)" class="btn btn-success btn-sm" :disabled="news.audit_status !== 0" @click="cancelPinned(news.id)">取消置顶</button>
                            <!-- 置顶 -->
                            <button v-else class="btn btn-success btn-sm" :disabled="news.audit_status !== 0" data-toggle="modal" data-target="#myModal" @click="cur_news = news.id">置顶</button>
                            <!-- 推荐 -->
                            <!-- 取消推荐 -->
                            <button class="btn btn-success btn-sm" :disabled="news.audit_status !== 0" @click="recommendNews(news.id)">{{ news.is_recommend ? "取消" : ""}}推荐</button>
                            <!-- 删除 -->
                            <button class="btn btn-danger btn-sm" @click="deleteNews(news)">删除</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <ui-modal :title="`选择截至时间`" :doSave="pinnedNews" :onClose="updateTime">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- 名称 -->
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="date" class="form-control" v-model="user_time">
                            </div>
                        </div>
                        <!-- /名称 -->
                    </div>
                </div>
            </div>
            <slot name='load'></slot>
        </ui-modal>
    </div>
</template>
<script>
import { admin } from '../../../axios';
const MES = { message: "开发中..." };
export default {
    name: 'module-news-list',
    props: {
        datas: { type: Array, required: true },
        update: { type: Function, required: true },
        publishMessage: { type: Function, required: true }
    },
    data() {
        return({
            user_time: null,
            cur_news: null
        })
    },
    computed: {
        time() {
            return new Date(this.user_time).getTime();
        }
    },
    methods: {
        /**
         * 状态格式化
         * @param  {Number} status
         * @return {Object}
         */
        statusToTips(status) {
            switch(status) {
                case 0:
                    return { label: "正常", cls: "" };
                case 1:
                    return { label: "待审核", cls: "info" };
                case 2:
                    return { label: "草稿", cls: "warning" };
                case 3:
                    return { label: "驳回", cls: "danger" };
                case 4:
                    return { label: "已删除", cls: "warning" };
                case 5:
                    return { label: "已退款", cls: "success" };
                default:
                    return { label: status, cls: "" };
            }

        },

        /**
         * 审核资讯
         * @param  {Number} id    news id
         * @param  {Number} state 0 审核通过 | 3 审核驳回
         * @return {void}
         */
        checkNews(id, state) {
            admin.post(`/news/audit/${id}`, {
                state,
                validateStatus: status => status === 204,
            }).then(() => {
                this.publishMessage({ success: "操作成功" }, "success");
                this.update();
            }).catch(err => {
                this.update();
                this.publishMessage({ danger: "操作失败" }, "danger");
            });
        },

        /**
         * 删除资讯
         * @param  {Number} options.id
         * @param  {Number} options.audit_status
         * @return {void}
         */
        deleteNews({ id, audit_status }) {
            admin.delete(`/news/del/${id}/news`, {
                validateStatus: status => status === 204,
                data: {
                    is_del: audit_status > 3
                }
            }).then(() => {
                this.publishMessage({ success: "删除成功" }, "success");
                this.update();
            }).catch(err => {
                this.publishMessage({ danger: "删除失败" }, "danger");
                this.update();
            })
        },

        /**
         * 置顶资讯
         * @param  {Number} id
         * @return {[type]}
         */
        pinnedNews() {
            const id = this.cur_news,
                time = this.time / 1000;

            if(!(time > 0)) {
                this.publishMessage({ message: "请选择正确的截至日期" }, "error");
                return false;
            }

            if((time - new Date() / 1000 < 0)) {
                this.publishMessage({ message: "截至日期不能是过去时间" }, "error");
                return false;
            }

            admin.post(`/news/${id}/pinned`, {
                time,
                validateStatus: status => status === 201
            }).then(() => {
                this.publishMessage({ message: "操作成功！" }, "success");
                this.update();
            }).catch(({ response: { data = { message: "操作失败！" } } }) => {
                this.publishMessage(data, "error");
                this.update();
            });
        },

        updateTime() {
            this.cur_news = null;
            this.user_time = 0;
            this.update();
        },

        /**
         * 取消置顶
         * @param  {Number} id
         * @return {[type]}
         */
        cancelPinned(id) {
            admin.delete(`/news/${id}/pinned`, {
                validateStatus: status => status === 201
            }).then(({ data = { message: "操作成功！" } }) => {
                this.publishMessage(data, "success");
                this.update();
            }).catch(({ response: { data = { message: "操作失败!" } } }) => {
                console.log(data);
                this.publishMessage(data, 'error');
                this.update();
            })
        },
        /**
         * 推荐资讯
         * @param  {Number} id
         * @return {[type]}
         */
        recommendNews(id) {
            admin.patch(`/news/${id}/recommend`, {
                validateStatus: status => status === 201
            }).then(({ data = { message: "操作成功！" } }) => {
                this.publishMessage(data, "success");
                this.update();
            }).catch(({ response: { data = { message: "操作失败！" } } }) => {
                console.log(data);
                this.publishMessage(data, "error");
            });
        }

    }
};
</script>
<style lang="scss">
table th {
    text-align: center;
}

.table-middle td {
    text-align: center;
    vertical-align: middle !important;
    span,
    p {
        text-align: left;
    }
}

.td-title {
    overflow: hidden;
    margin-bottom: 0;
    width: 120px;
    white-space: nowrap;
    text-overflow: ellipsis;
    text-align: left;
}

.td-thumbnail {
    width: 145px;
    height: 100px;
    .thumbnail {
        margin-bottom: 0;
    }
}

.small_img {
    height: 60px !important;
}
</style>