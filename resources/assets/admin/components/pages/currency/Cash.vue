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
        		<thead>
        			<tr>
        				<th>用户ID</th>
        				<th>用户名</th>
        				<th>积分</th>
        				<th>余额</th>
        				<th>状态</th>
        				<th>备注</th>
                        <th>时间</th>
                        <th>操作</th> 
        			</tr>
        		</thead>
        		<tbody>
        			<tr>
        				<td></td>
        				<td></td>
        				<td></td>
        				<td></td>
        				<td></td>
        				<td></td>
                        <td></td>
                        <td></td>
        			</tr>
        		</tbody>
        	</table>
        </div>
    </div>
</template>
<script>
	export default {
		data() {
			return {
				items: [],
				user: '',
				state: '',
			}
		},

		methods: {

		},

		created() {

		}
	}
</script>