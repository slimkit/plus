<template>
    <table class="table table-hover table-bordered table-responsive table-middle">
        <thead>
            <tr>
                <th width='10%'># ID</th>
                <th width='10%'>申请人</th>
                <th width='25%'>标题</th>
                <th width='10%'>创建时间</th>
                <th width='10%'>过期时间</th>
                <th width='10%'>审核状态</th>
                <th width='15%'>操作</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="pinned in datas" :class="statusToTips(pinned).cls">
                <td width="10%">{{ pinned.id }}</td>
                <td width='10%'>
                    <span v-if="pinned.user">{{pinned.user.name}}</span>
                    <span v-else>该用户不存在或已删除</span>
                </td>
                <td width='25%'>
                    <span v-if="pinned.news">{{ pinned.news.title }}</span>
                    <span v-else>该资讯不存在或已删除</span>
                </td>
                <td width='10%'>{{ pinned.created_at }}</td>
                <td width='10%'>{{ pinned.expires_at }}</td>
                <td width='10%'>{{ pinned.label }}</td>
                <td width='15%'>
                    <!-- 通过 -->
                    <button type="button" class="btn btn-primary btn-sm" :disabled='pinned.state!==0' @click="checkPinned(pinned.id)(`accept`)">通过</button>
                    <!-- /通过 -->
                    <!-- 驳回 -->
                    <button type="button" class="btn btn-danger btn-sm" :disabled='pinned.state!==0' @click="checkPinned(pinned.id)(`reject`)">驳回</button>
                    <!-- /驳回 -->
                </td>
            </tr>
        </tbody>
    </table>
</template>
<script>
import { admin } from "../../../axios/";
export default {
    name: "pinneds-list",
    props: {
        datas: { type: Array, required: true },
        update: { type: Function, required: true },
        publishMessage: { type: Function, required: true }
    },
    data() {
        return({
            message: {
                open: false,
                type: '',
                data: {}
            }
        });
    },
    methods: {
        statusToTips(pinned) {
            const {
                state
            } = pinned,
            r = {};
            switch(state) {
                case 0:
                    r.cls = "info";
                    r.label = "待审核";
                    break;
                case 1:
                    r.cls = ""
                    r.label = "审核通过";
                    break;
                case 2:
                    r.cls = "danger";
                    r.label = '被拒';
                    break;
                default:
                    r.cls = "",
                        r.label = "未知";
                    break;
            }

            Object.assign(pinned, r);

            return pinned;
        },
        status2label() {},
        checkPinned(id) {
            return(action) => {
                admin.patch(`/news/pinned/${id}`, {
                    action
                }, {
                    validateStatus: state => state === 204
                }).then(() => {
                    this.publishMessage({ message: "操作成功!" }, "success");
                }, ({ response: { data = { message: "操作失败!" } } }) => {
                    this.publishMessage(data, "danger");
                })
            }
        },
    }
}
</script>