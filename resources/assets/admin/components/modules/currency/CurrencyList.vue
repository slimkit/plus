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
                <th>电话</th>
                <th>邮箱</th>
                <th>积分数量</th>
                <th>操作</th>
            </tr>
            <!-- table content -->
            <tr slot="table-tbody-tr" v-for="item in items">
                <td>{{ item.id }}</td>
                <td>{{ item.name }}</td>
                <td>{{ item.phone }}</td>
                <td>{{ item.email }}</td>
                <td>{{ item.currency ? item.currency.sum : 0  }}</td>
                <td>
                    <button class="btn btn-primary btn-sm" @click="handleAssign(item.id)">积分赋值</button>
                </td>
            </tr>
        </template>
    </ui-table>
</template>
<script>
import request, {createRequestURI} from '../../../util/request';
import Table from '../../commons/Table';

export default {
    name: 'currency-list',
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
    methods: {
        handleAssign(uid) {
            var num = prompt('请填写输入赋值数量(正整数增加,负整数减少)');
            // 点击取消.
            if (num === null) {
                return;
            }
            var reg = /^-?\d+$/;
            // 验证参数正确性.
            if (! num || !reg.test(num)) {
                this.$store.dispatch('alert-open', {type: 'danger', message: { message: '数量类型错误'}});
                return;
            }
            request.post(createRequestURI('currency/add'),
                {user_id: uid, num: num},
                {validateStatus: status => status === 200}
            )
            .then(({ data }) => {
                this.items.forEach( function(element, index) {
                    if (element.id == uid) element.currency = data.currency;
                });
                this.$store.dispatch('alert-open', {type: 'success', message: data});
            }).catch(({response: {data = {message: '获取失败'}} = {}}) => {
                this.$store.dispatch('alert-open', {type: 'danger', message: data});
            });
        },
    }
}
</script>