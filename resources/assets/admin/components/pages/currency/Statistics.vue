<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            统计
            <router-link to="/currency/waters" class="btn btn-primary btn-xs pull-right">查看积分流水</router-link>
        </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <!-- 加载效果 -->
                <table-loading :loadding="loading" :colspan-num="3"></table-loading>
                <!-- 列表 -->
                <thead>
                <tr>
                    <th>类型</th>
                    <th>笔数</th>
                    <th>数量</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in items" :key="index">
                    <td>{{ index | alias }}</td>
                    <td>{{ item.count }}</td>
                    <td>{{ item.sum ? item.sum : 0 }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import request, {createRequestURI} from '../../../util/request';

    export default {
        data() {
            return {
                uri: 'wallet/statistics',
                items: [],
                start: '',
                end: '',
                loading: true,
            }
        },
        filters: {
            alias(val) {
                switch (val) {
                    case 'cash':
                        return '提现';
                        break;
                    case 'recharge':
                        return '充值';
                        break;
                    default:
                        return '未知';
                        break;
                }
            }
        },
        methods: {
            getWalletStatistics() {
                request.get(
                    createRequestURI(`currency/overview`),
                    {validateStatus: status => status === 200}
                ).then(({data}) => {
                    this.loading = false;
                    this.items = data;
                }).catch(({response: {data: {errors = '加载数据失败，请重试'} = {}} = {}}) => {
                    this.loading = false;
                    this.$store.dispatch('alert-open', {type: 'danger', message: data});
                });
            },
        },

        created() {
            this.getWalletStatistics();
        }
    };
</script>
