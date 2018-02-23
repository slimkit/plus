<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            提现审批
        </div>
        <div class="panel-heading">
            <cash-search></cash-search>
        </div>
        <div class="panel-body">

            <cash-list :items="items" :loading="loading"></cash-list>

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
import components from '../../modules/currency';
export default {
    components: components,
    data() {
        return {
            items: [],
            total: 0,
            loading: true,
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
            })
            .then(({data, headers: {'x-total': total}}) => {
                this.items = data;
                this.total = parseInt(total);
                this.loading = false;
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