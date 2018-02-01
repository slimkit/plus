<template>
    <div class="panel panel-default">
        <div class="panel-heading">积分流水</div>
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
					<input type="text"
						   class="form-control"
						   placeholder="用户名手机"
						   v-model="filters.phone">
				</div>
				<div class="form-group">
					<input type="text"
						   class="form-control"
						   placeholder="用户邮箱"
						   v-model="filters.email">
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
	        				<th>电话</th>
	        				<th>邮箱</th>
	        				<th>积分数量</th>
	        				<th>操作</th>
	        			</tr>
	        		</thead>

	        		<tbody>
	        			<tr v-for="item in items">
	        				<td>{{ item.id }}</td>
	        				<td>{{ item.name }}</td>
	        				<td>{{ item.phone }}</td>
	        				<td>{{ item.email }}</td>
	        				<td>{{ item.currency ? item.currency.sum : 0  }}</td>
	        				<td>
								<button class="btn btn-primary btn-sm" @click="handleAssign(item.id)">积分赋值</button>
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
				total: 0,
				items: [],
				filters: {
					user: '',
					name: '',
					phone: '',
					email: '',
				},
				loading: true,
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
                request.get(createRequestURI('currency'),{
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
		    buildRoute(offset) {
		       const { query } = this.$route;

		      return { path: '/currency', query: { ...query, offset } };
		    },
		},
		created() {
			this.getList(this.$route.query);
		}
	}
</script>