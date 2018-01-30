<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            提现审批
        </div>
        <div class="panel-heading">
            <div class="form-inline">
                <div class="form-group">
                    <input type="text"
                           class="form-control"
                           placeholder="用户名"
                           v-model="filters.name">
                </div>
                <div class="form-group">
                    <input type="text"
                           class="form-control"
                           placeholder="用户ID"
                           v-model="filters.user">
                </div>
                <div class="form-group">
                    <select class="form-control" v-model="filters.state">
                        <option value="">全部</option>
                        <option value="0">等待</option>
                        <option value="1">成功</option>
                        <option value="-1">失败</option>
                    </select>
                </div>
                <div class="form-group">
                    <router-link :to="{ path: '/currency/cashes', query: filters }" class="btn btn-default">确认
                    </router-link>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <!-- 加载效果 -->
                <table-loading :loadding="loading" :colspan-num="7"></table-loading>
                <template v-if="!loading">
                    <thead>
                    <tr>
                        <th>用户ID</th>
                        <th>用户名</th>
                        <th>积分</th>
                        <th>余额</th>
                        <th>状态</th>
                        <th>时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in items">
                        <td>{{ item.owner_id }}</td>
                        <td>{{ item.user ? item.user.name : '未知' }}</td>
                        <td>{{ item.amount }}</td>
                        <td>{{ item.user ? (item.user.currency ? item.user.currency.sum : 0) : 0 }}</td>
                        <td>{{ item.state | state }}</td>
                        <td>{{ item.created_at | localDate }}</td>
                        <td>
                            <button @click="handleAudit(item.id,1)" class="btn btn-primary btn-sm"
                                    :disabled="item.state != 0">通过
                            </button>
                            <button @click="handleAudit(item.id,-1)" class="btn btn-danger btn-sm"
                                    :disabled="item.state != 0">拒绝
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </template>
            </table>
            <ui-offset-paginator class="pagination" :total="total" :offset="offset" :limit="15">
                <template slot-scope="pagination">
                    <li :class="(pagination.disabled ? 'disabled': '') + (pagination.currend ? 'active' : '')">
                        <span v-if="pagination.disabled || pagination.currend">{{ pagination.page }}</span>
                        <router-link v-else :to="buildRoute(pagination.offset)">{{ pagination.page }}</router-link>
                    </li>
                </template>
            </ui-offset-paginator>
        </div>
    </div>
</template>
<script>
    import request, {createRequestURI} from '../../../util/request';

    export default {
        data() {
            return {
                items: [],
                total: 0,
                filters: {
                    user: '',
                    name: '',
                    state: '',
                },
                loading: true,
            }
        },
        filters: {
            state(val) {
                switch (val) {
                    case 0:
                        return '待审核';
                        break;
                    case 1:
                        return '通过';
                        break;
                    case -1:
                        return '驳回';
                        break;
                    default:
                        return '未知';
                        break;
                }
            }
        },
        watch: {
            '$route': function ({query}) {
                this.total = 0;
                this.getList(query);
            }
        },
        computed: {
            offset() {
                const {query: {offset = 0}} = this.$route;

                return parseInt(offset);
            },
        },
        methods: {
            getList(query = {}) {
                this.loading = true;
                request.get(createRequestURI('currency/cash'), {
                        validateStatus: status => status === 200, params: {
                            ...query, limit: 15
                        }
                    }
                )
                    .then(({data, headers: {'x-total': total}}) => {
                        this.items = data;
                        this.total = parseInt(total);
                        this.loading = false;
                    }).catch(({response: {data = {message: '获取失败'}} = {}}) => {
                    this.loading = false;
                    this.$store.dispatch('alert-open', {type: 'danger', message: data});
                });
            },
            handleAudit(id, state) {
                var mark = '';
                if (state == -1) {
                    mark = prompt('请填写驳回的理由');
                    if (mark == null) {
                        return;
                    }
                    if (!mark) {
                        this.$store.dispatch('alert-open', {type: 'danger', message: {message: '请填写驳回理由'}});
                        return;
                    }
                }
                request.patch(
                    createRequestURI(`currency/cash/${id}/audit`),
                    {state: state, mark: mark},
                    {validateStatus: status => status === 200}
                )
                    .then(({data = {}}) => {
                        this.items.forEach(function (element, index) {
                            if (element.id == id) element.state = state;
                        });
                        this.$store.dispatch('alert-open', {type: 'success', message: data});
                    }).catch(({response: {data = {message: '获取失败'}} = {}}) => {
                    this.loading = false;
                    this.$store.dispatch('alert-open', {type: 'danger', message: data});
                });
            },
            buildRoute(offset) {
                const {query} = this.$route;

                return {path: '/currency/cashes', query: {...query, offset}};
            },
        },
        created() {
            const {query} = this.$route;
            this.getList(query);
        }
    }
</script>