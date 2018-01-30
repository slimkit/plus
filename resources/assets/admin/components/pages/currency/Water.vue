<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            积分流水
            <router-link to="/currency/statistics" class="btn btn-default btn-xs pull-right">返回</router-link>
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
                	<select class="form-control" v-model="filters.action">
                		<option value="">全部</option>
                		<option value="1">增加</option>
                		<option value="-1">减少</option>
                	</select>
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
                    <router-link :to="{ path: '', query: filters }" class="btn btn-default">确认</router-link>
                </div>
            </div>
        </div>
        <div class="panel-body">
	        <!-- 流水列表 -->
        	<table class="table table-bordered">
		        <!-- 加载效果 -->
		        <table-loading :loadding="loading" :colspan-num="7"></table-loading>
				
				<template v-if="!loading">
					<thead>
	        			<tr>
	        				<th>用户ID</th>
	        				<th>用户名</th>
	        				<th>积分</th>
	        				<th>交易信息</th>
	        				<th>类型</th>
	        				<th>状态</th>
	        				<th>时间</th>
	        			</tr>
	        		</thead>

	        		<tbody>
	        			<tr v-for="item in items">
	        				<td>{{ item.owner_id }}</td>
	        				<td>{{ item.user ? item.user.name : '未知' }}</td>
	        				<td>{{ item.amount }}</td>
	        				<td>{{ item.body }}</td>
	        				<td>{{ item.type  | type }}</td>
	        				<td>{{ item.state | state}}</td>
	        				<td>{{ item.created_at | localDate }}</td>
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
				total: 0,
				items: [],
				filters: {
					user: '',
					name: '',
					action: '',
					state: '',
				},
				loading: true,
			}
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
		watch: {
			'$route': function ({ query }) {
		      this.total = 0;
		      this.getList(query);
		    }
		},
		computed: {
		    offset () {
		      const { query: { offset = 0 } } = this.$route;

		      return parseInt(offset);
		    },
		},
		methods: {
			getList(query = {}) {
				this.loading = true;
                request.get(createRequestURI('currency/list'),{ 
                	validateStatus: status => status === 200, params: { 
                		...query, limit: 15 
                	}}
                )
                .then(({data, headers: { 'x-total': total }}) => {
                	this.items = data;
                	this.total = parseInt(total);
                	this.loading = false;
                }).catch(({response: {data = {message: '获取失败'}} = {}}) => {
                	this.loading = false;
                    this.$store.dispatch('alert-open', {type: 'danger', message: data});
                });
			},
		    buildRoute(offset) {
		       const { query } = this.$route;

		      return { path: '/currency/waters', query: { ...query, offset } };
		    },
		},
		created() {
			this.getList(this.$route.query);
		}
	}
</script>