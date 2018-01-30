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
                	placeholder="用户名/用户ID" 
                	v-model="user">
                </div>
                <div class="form-group">
                	<select class="form-control" v-model="state">
                		<option value="">全部</option>
                		<option value="0">等待</option>
                		<option value="1">成功</option>
						<option value="-1">失败</option>                		
                	</select>
                </div>
                <div class="form-group">
                    <button class="btn btn-default">确认</button>
                </div>
            </div>
        </div>
        <div class="panel-body">
        	<table class="table table-bordered">
                <!-- 加载效果 -->
                <table-loading :loadding="loading" :colspan-num="3"></table-loading>
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
                        <td>{{ item.user ? item.user.currency.sum : 0 }}</td>
        				<td>{{ item.state }}</td>
        				<td>{{ item.created_at | localDate }}</td>
        				<td>
                            <button @click="handleAudit" class="btn btn-primary btn-sm">审核</button>            
                        </td>
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
				items: [],
                total: 0,
				user: '',
				state: '',
                loading: true,
			}
		},
		methods: {
            getList(query = {}) {
                this.loading = true;
                request.get(createRequestURI('currency/cash'),{ 
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
		},
		created() {
            const { query } = this.$route;
            this.getList(query);
		}
	}
</script>