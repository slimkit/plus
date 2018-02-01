<template>
    <ui-table>
        <!-- table loading -->
        <template slot="table-loading">
            <table-loading :loadding="loading" :colspan-num="7"></table-loading>
        </template>
        <template v-if="!loading">
            <!-- table header -->
            <tr slot="table-thead-th">
                <th>用户ID</th>
                <th>用户名</th>
                <th>积分</th>
                <th>交易信息</th>
                <th>类型</th>
                <th>状态</th>
                <th>时间</th>
            </tr>
            <!-- table content -->
            <tr slot="table-tbody-tr" v-for="item in items">
                <td>{{ item.owner_id }}</td>
                <td>{{ item.user ? item.user.name : '未知' }}</td>
                <td>{{ item.amount }}</td>
                <td>{{ item.body }}</td>
                <td>{{ item.type | type }}</td>
                <td>{{ item.state | state}}</td>
                <td>{{ item.created_at | localDate }}</td>
            </tr>
        </template>
    </ui-table>
</template>
<script>
    import Table from '../../commons/Table';

    export default {
        name: 'water-list',
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
        },
        filters: {
            type(val) {
                return val == 1 ? '增加' : '减少';
            },
            state(val) {
                switch (val) {
                    case 0:
                        return '等待';
                        break;
                    case 1:
                        return '成功';
                        break;
                    case -1:
                        return '失败';
                        break;
                    default:
                        return '未知';
                        break;
                }
            }
        },
    }
</script>