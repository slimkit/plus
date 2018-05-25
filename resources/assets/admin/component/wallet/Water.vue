<template>
	<div class="container-fluid" style="margin-top:10px;">
		<div class="panel panel-default">
			<div class="panel-heading">
				钱包流水
			</div>
			<div class="panel-heading">
				<div class="form-inline">
	                <div class="form-group">
	                    <input type="text"
	                           class="form-control"
	                           placeholder="用户ID"
	                           v-model="filters.user">
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
	                    <router-link :to="{ path: '/wallet/waters', query: filters }" class="btn btn-default">确认
	                    </router-link>
	                </div>
				</div>
			</div>
			<div class="panel-body">
				<table class="table table-bordered">
	                <!-- 加载效果 -->
	                <table-loading :loadding="loading" :colspan-num="9	"></table-loading>
	                <template v-if="!loading">
						<thead>
							<tr>
								<th>#ID</th>
								<th>用户ID</th>
								<th>目标类型</th>
								<th>标题</th>
								<th>内容</th>
								<th>动作</th>
								<th>金额</th>
								<th>状态</th>
								<th>时间</th>
							</tr>						
						</thead>
						<tbody>
							<tr v-for="item in items">
								<td>{{ item.id }}</td>
								<td>{{ item.owner_id }}</td>
								<td>{{ item.target_type | targetType }}</td>
								<td>{{ item.title }}</td>
								<td>{{ item.body }}</td>
								<td>{{ item.type | action }}</td>
								<td>{{ item.amount }}</td>
								<td>{{ item.state | state }}</td>
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
	</div>	
</template>
<script>
import request, { createRequestURI } from "../../util/request";

export default {
	data() {
		return {
			items: [],
			total: 0,
			loading: true,
			filters: {
				user: "",
				state: ""
			}
		};
	},
	filters: {
		targetType(val) {
			switch (val) {
				case "user":
					return "用户之间转账";
					break;
				case "recharge_ping_p_p":
					return "Ping ++ 充值";
					break;
				case "Wechat-Native":
					return "微信充值";
					break;
				case "Alipay-Native":
					return "支付宝充值";
					break;
				case "reward":
					return "打赏";
					break;
				case "widthdraw":
					return "提现";
					break;
				case "transform":
					return "兑换货币、积分";
					break;
				default:
					return "未知";
					break;
			}
		},
		action(val) {
			switch (val) {
				case 1:
					return "增加";
					break;
				case -1:
					return "减少";
					break;
				default:
					return "未知";
					break;
			}
		},
		state(val) {
			switch (val) {
				case 0:
					return "等待";
					break;
				case 1:
					return "成功";
					break;
				case -1:
					return "失败";
					break;
				default:
					return "未知";
					break;
			}
		}
	},
	watch: {
		$route: function({ query }) {
			this.total = 0;
			this.getList(query);
		}
	},
	computed: {
		offset() {
			const { query: { offset = 0 } } = this.$route;

			return parseInt(offset);
		}
	},
	methods: {
		getList(query = {}) {
			this.loading = true;
			request
				.get(createRequestURI("new-wallet/waters"), {
					validateStatus: status => status === 200,
					params: { ...query, limit: 15 }
				})
				.then(({ data = [], headers: { "x-total": total } }) => {
					this.items = data;
					this.total = parseInt(total);
					this.loading = false;
				})
				.catch(({ response: { data = { message: "加载流水失败" } } = {} }) => {
					this.loading = false;
					window.alert(message);
				});
		},
		buildRoute(offset) {
			const { query } = this.$route;

			return { path: "/wallet/waters", query: { ...query, offset } };
		}
	},
	created() {
		const { query } = this.$route;
		this.getList(query);
	}
};
</script>
