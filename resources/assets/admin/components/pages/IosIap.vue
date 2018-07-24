<template>
<div class="clearfix">
    <div class="container-fluid">
    	<module-alert></module-alert>
		<div class="panel panel-default">
			<div class="panel-heading">苹果IAP设置</div>
			<div class="panel-heading">
				<div class="alert alert-success" style="margin-bottom:0;padding:4px;">
					1、IOS端需要提交到APPStore审核时，需要开启该项开关。<br/>
					2、开启只允许IAP开关时，IOS端将关闭钱包转积分和积分提现到钱包功能
				</div>
			</div>
			<div class="panel-heading">
				<div>
					<label for="">IAP：</label>
	                <label class="radio-inline">
	                  <input type="radio" :value="radio.on" v-model="status"> 开启
	                </label>
	                <label class="radio-inline">
	                  <input type="radio" :value="radio.off" v-model="status"> 关闭
	                </label>
				</div>
				<br/>
				<div>
					<label for="">规则：</label>
					<textarea class="form-control" v-model="rule"></textarea>
				</div>
				<button class="btn btn-default" @click="handleSubmit">确认</button>
			</div>
			<div class="panel-body">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#ID</th>
							<th>名称</th>
							<th>金额(分)</th>
							<th>apple id</th>
							<th>操作</th>
						</tr>		
					</thead>
					<tbody>
						<tr v-for="item in items">
							<td>{{ item.product_id }}</td>
							<td>{{ item.name }}</td>
							<td>{{ item.amount }}</td>
							<td>{{ item.apple_id }}</td>
							<td>
								<button class="btn btn-danger btn-sm" @click="handleDelete(item.product_id)">删除</button>
							</td>
						</tr>
						<tr>
							<td><input type="text" v-model="product.product_id" class="form-control" placeholder="产品ID"></td>
							<td><input type="text" v-model="product.name" class="form-control" placeholder="产品名"></td>
							<td><input type="number" v-model="product.amount" class="form-control" placeholder="产品定价(分)"></td>
							<td><input type="text" v-model="product.apple_id" class="form-control" placeholder="apple id"></td>
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
import Alert from '../modules/Alert';
import request, { createRequestURI } from '../../util/request';

export default {
    components: {
	    [Alert.name]: Alert,
    },
	data() {
		return {
			items: [],
			status: null,
			rule: '',
			radio: {
				on: true,
				off: false,
			},
			product: {
				product_id: '',
				name: '',
				amount: '',
				apple_id: '',
			}
		}
	},
	methods: {
        getConfig() {
            request.get(createRequestURI('currency/apple/config'), {
            	validateStatus: status => status === 200
            })
            .then(({data}) => {
                this.status = data.IAP_only;
            }).catch(({response: {data = {message: '获取失败'}} = {}}) => {
                this.$store.dispatch('alert-open', {type: 'danger', message: data});
            });
        },
        getProducts() {
            request.get(createRequestURI('currency/apple/products'), {
            	validateStatus: status => status === 200
            })
            .then(({data}) => {
                this.items = data;
            }).catch(({response: {data = {message: '获取失败'}} = {}}) => {
                this.$store.dispatch('alert-open', {type: 'danger', message: data});
            });
        },		
        handleSubmit() {
            request.patch(
            	createRequestURI('currency/apple/config'), 
            	{ IAP_only:this.status, rule: this.rule },
            	{ validateStatus: status => status === 201 }
            ).then(({ data })=> {
                this.$store.dispatch('alert-open', {type: 'success', message: data});
            }).catch(({response: {data = {message: '获取失败'}} = {}}) => {
                this.$store.dispatch('alert-open', {type: 'danger', message: data});
            });
		},
		handleDelete(id) {
			if (confirm('确定要删除嘛？')) {
	            request.delete(
	            	createRequestURI(`currency/apple/products?product_id=${id}`),
	            	{validateStatus: status => status === 204}
	            )
	            .then(response => {
	            	this.getProducts();
	                this.$store.dispatch('alert-open', {type: 'success', message: { message: '删除成功'} });
	            }).catch(({response: {data = {message: '添加失败'}} = {}}) => {
	                this.$store.dispatch('alert-open', {type: 'danger', message: data});
	            });
			}
		},
		handleCreate() {
            request.post(createRequestURI('currency/apple/products'),
            	{ ...this.product }, 
            	{validateStatus: status => status === 201}
            )
            .then(({data}) => {
            	this.getProducts();
                this.$store.dispatch('alert-open', {type: 'success', message: data});
            }).catch(({response: {data = {message: '添加失败'}} = {}}) => {
                this.$store.dispatch('alert-open', {type: 'danger', message: data});
            });
		},
		handleEdit(id, name, value) {
            request.post(createRequestURI('currency/apple/products'),
            	{ ...this.product }, 
            	{validateStatus: status => status === 201}
            )
            .then(({data}) => {
                this.$store.dispatch('alert-open', {type: 'success', message: data});
            }).catch(({response: {data = {message: '添加失败'}} = {}}) => {
                this.$store.dispatch('alert-open', {type: 'danger', message: data});
            });
		}
	},
	created() {
		this.getConfig();
		this.getProducts();
	}
}
</script>