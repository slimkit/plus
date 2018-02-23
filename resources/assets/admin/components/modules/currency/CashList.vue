<template>
    <ui-table>
        <!-- table loading -->
        <template slot="table-loading">
            <table-loading :loadding="loading" :colspan-num="6"></table-loading>
        </template>

        <template v-if="!loading">
            <!-- table header -->
            <tr slot="table-thead-th">
                <th>用户ID</th>
                <th>用户名</th>
                <th>积分</th>
                <th>状态</th>
                <th>时间</th>
                <th>操作</th>
            </tr>
            <!-- table content -->
            <tr slot="table-tbody-tr" v-for="item in items">
                <td>{{ item.owner_id }}</td>
                <td>{{ item.user ? item.user.name : '未知' }}</td>
                <td>{{ item.amount }}</td>
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
        </template>
        
    </ui-table>
</template>
<script>
import request, {createRequestURI} from '../../../util/request';
import Table from '../../commons/Table';

export default {
    name: 'cash-list',
    components: {
        'ui-table': Table
    },
    props: {
        items: {
            type: Array,
        },
        loading: {
            type: Boolean,
            default: true,
        },
        colspanNum: {
            type: Number,
            default: 0
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
    methods: {
        
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
                this.$store.dispatch('alert-open', {type: 'danger', message: data});
            });
    },
    }
}
</script>