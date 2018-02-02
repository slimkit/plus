<template>
<div class="clearfix">
    <div class="container-fluid">
		<div class="panel panel-default">
			<div class="panel-heading">苹果IAP设置</div>
			<div class="panel-heading">
				<div class="alert alert-success" style="margin-bottom:0;padding:4px;">
					1、该设置仅对IOS端有效。<br/>
					3、商品添加，注意：添加的商品ID不能重复。<br>
					2、编辑商品信息的时候，直接修改输入框内容，失去焦点后程序会自动提交。
				</div>
			</div>
			<div class="panel-heading">
				<div class="form-control">
					<label for="">IOS APP 内购买：</label>
	                <label class="radio-inline">
	                  <input type="radio" :value="radio.on" v-model="status"> 开启
	                </label>
	                <label class="radio-inline">
	                  <input type="radio" :value="radio.off" v-model="status"> 关闭
	                </label>
	                <button class="btn btn-default btn-xs">确认</button>
				</div>
			</div>
			<div class="panel-body">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#ID</th>
							<th>名称</th>
							<th>金额</th>
							<th>appleId</th>
							<th>操作</th>
						</tr>		
					</thead>
					<tbody>
						<tr>
							<td><input type="number" value="1" class="form-control" @change="handleEdit"></td>
							<td><input type="text" value="积分" class="form-control" @change="handleEdit"></td>
							<td><input type="number" value="50" class="form-control" @change="handleEdit"></td>
							<td><input type="text" value="id1233" class="form-control" @change="handleEdit"></td>
							<td>
								<button class="btn btn-danger btn-sm" @click="handleDelete">删除</button>
							</td>
						</tr>
						<tr>
							<td><input type="number" v-model="good.id" class="form-control" placeholder="商品ID"></td>
							<td><input type="text" v-model="good.name" class="form-control" placeholder="商品名称"></td>
							<td><input type="number" v-model="good.price" class="form-control" placeholder="商品金额(分)"></td>
							<td><input type="text" v-model="good.appid" class="form-control" placeholder="APPID"></td>
							<td>
								<button class="btn btn-success btn-sm" @click="handleCreate">添加</button>
							</td>
						</tr>	
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</template>
<script>
export default {
	data() {
		return {
			status: true,
			radio: {
				on: true,
				off: false,
			},
			good: {
				id: '',
				name: '',
				price: '',
				appid: '',
			}
		}
	},
	methods: {
		handleStaus() {

		},
		handleDelete() {

		},
		handleCreate() {
			console.log(this.good);
		},
		handleEdit(item) {

		}
	},
	created() {

	}
}
</script>